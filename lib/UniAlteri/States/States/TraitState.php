<?php
/**
 * States
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @package     States
 * @subpackage  States
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     $Id$
 */

/**
 * @category    States
 * @copyright   Copyright (c) 2009-2013 Uni Alteri (http://agence.net.ua)
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\States\States;

use \UniAlteri\States\DI;
use \UniAlteri\States\Proxy;

/**
 * Class TraitState
 * @package UniAlteri\States\States
 * Standard implementation of the state interface for state class.
 *
 * It's tray to allow developer to implement easily the interface for theirs state class.
 */
trait TraitState
{
    /**
     * DI Container to use for this object
     * @var DI\ContainerInterface
     */
    protected $_diContainer = null;

    /**
     * List of methods available for this state
     * @var \ArrayObject
     */
    protected $_methodsListArray = null;

    /**
     * Reflection class object of this state to extract closures and description
     * @var \ReflectionClass
     */
    protected $_reflectionClass = null;

    /**
     * Reflections methods of this state to extract description and closures
     * @var \ReflectionMethod[]
     */
    protected $_reflectionsMethods = null;

    /**
     * List of closure already extracted and set into Injection Closure Container
     * @var DI\InjectionClosureInterface[]
     */
    protected $_closuresObjects = null;

    /**
     * //Methods to not return into descriptions
     * @var array
     */
    protected $_methodsNamesToIgnoreArray = array(
        '__construct'                   => '__construct', //Ignore not accessible from proxy
        '__destruct'                    => '__destruct',
        '_getReflectionClass'           => '_getReflectionClass',
        '_buildInjectionClosureObject'  => '_buildInjectionClosureObject',
        '_checkVisibility'              => '_checkVisibility',
        'setDIContainer'                => 'setDIContainer',
        'getDIContainer'                => 'getDIContainer',
        'listMethods'                   => 'listMethods',
        'testMethod'                    => 'testMethod',
        'getMethodDescription'          => 'getMethodDescription',
        'getClosure'                    => 'getClosure'
    );

    /**
     * Build the ReflectionClass for the current object
     * @return \ReflectionClass
     */
    protected function _getReflectionClass()
    {
        if (null === $this->_reflectionClass) {
            $this->_reflectionClass = new \ReflectionClass(\get_class($this));
        }

        return $this->_reflectionClass;
    }

    /**
     * Register a DI container for this object
     * @param DI\ContainerInterface $container
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container)
    {
        $this->_diContainer = $container;
        return $this;
    }

    /**
     * Return the DI Container used for this object
     * @return DI\ContainerInterface
     */
    public function getDIContainer()
    {
        return $this->_diContainer;
    }

    /**
     * Return an array of string listing all methods available in the state
     * @return string[]
     */
    public function listMethods()
    {
        if (null === $this->_methodsListArray) {
            //Extract methods
            $thisReflectionClass = $this->_getReflectionClass();
            $flags = \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE;
            $methodsArray = $thisReflectionClass->getMethods($flags);

            //Extracts methods' names
            $methodsFinalArray = new \ArrayObject();
            foreach ($methodsArray as $methodReflection) {
                //ReflectionClass is not fully tested and can return always static methods
                if (false == $methodReflection->isStatic()) {
                    //Store reflection into local cache
                    $methodNameString = $methodReflection->getName();
                    if (!isset($this->_methodsNamesToIgnoreArray[$methodNameString])) {
                        $methodsFinalArray[] = $methodNameString;
                        $this->_reflectionsMethods[$methodNameString] = $methodReflection;
                    }
                }
            }

            $this->_methodsListArray = $methodsFinalArray;
        }

        return $this->_methodsListArray;
    }

    /**
     * Check if the method is available in the scope
     * @param string $methodName
     * @param string $scope
     * @return bool
     * @throws Exception\InvalidArgument
     */
    protected function _checkVisibility($methodName, $scope)
    {
        $visible = false;
        if (isset($this->_reflectionsMethods[$methodName])) {
            //Check visibility scope
            switch ($scope) {
                case StateInterface::VISIBILITY_PRIVATE:
                    //Private, can access all
                    $visible = true;
                    break;
                case StateInterface::VISIBILITY_PROTECTED:
                    //Can not access to private methods
                    if (false == $this->_reflectionsMethods[$methodName]->isPrivate()) {
                        //It's a private method, do like if there is no method
                        $visible = true;
                    }
                    break;
                case StateInterface::VISIBILITY_PUBLIC:
                    //Can not access to protected and private method.
                    if (true == $this->_reflectionsMethods[$methodName]->isPublic()) {
                        //It's not a public method, do like if there is no method
                        $visible = true;
                    }
                    break;
                default:
                    //Bad parameter, throws exception
                    throw new Exception\InvalidArgument('Error, the visibility scope is not recognized');
                    break;
            }
        }

        return $visible;
    }

    /**
     * Test if a method exist for this state
     * @param string $methodName
     * @param string $scope self::VISIBILITY_PUBLIC|self::VISIBILITY_PROTECTED|self::VISIBILITY_PRIVATE
     * @return boolean
     * @throws Exception\InvalidArgument when the method name is not a string
     */
    public function testMethod($methodName, $scope=StateInterface::VISIBILITY_PUBLIC)
    {
        if (!is_string($methodName)) {
            throw new Exception\InvalidArgument('Error, the method name is not a valid string');
        }

        //Method is already extracted
        if (isset($this->_reflectionsMethods[$methodName])) {
            if ($this->_reflectionsMethods[$methodName] instanceof \ReflectionMethod) {
                return $this->_checkVisibility($methodName, $scope);
            } else {
                return false;
            }
        }

        try {
            //Try extract description
            $this->getMethodDescription($methodName);
        } catch(\Exception $e) {
            //Method not found, store locally the result
            $this->_reflectionsMethods[$methodName] = false;
            return false;
        }

        return $this->_checkVisibility($methodName, $scope);;
    }

    /**
     * Return the description of a method to configure the behavior of the proxy
     * @param string $methodName
     * @return \ReflectionMethod
     * @throws Exception\MethodNotImplemented is the method does not exist
     * @throws Exception\InvalidArgument when the method name is not a string
     */
    public function getMethodDescription($methodName)
    {
        if (!is_string($methodName)) {
            throw new Exception\InvalidArgument('Error, the method name is not a valid string');
        }

        if (isset($this->_methodsNamesToIgnoreArray[$methodName])) {
            throw new Exception\MethodNotImplemented('Error, this method is not implemented by this state');
        }

        $thisReflectionClass = $this->_getReflectionClass();

        //Initialize ArrayObject to store Reflection Methods
        if (!($this->_reflectionsMethods instanceof \ArrayObject)) {
            $this->_reflectionsMethods = new \ArrayObject();
        }

        try {
            //Load Reflection Methods if it is not already done
            if (!isset($this->_reflectionsMethods[$methodName])) {
                $methodDescription = $thisReflectionClass->getMethod($methodName);
                if (false != $methodDescription->isStatic()) {
                    //Method static are not available
                    throw new Exception\MethodNotImplemented(
                        'Method "'.$methodName.'" is not available for this state'
                    );
                }

                $this->_reflectionsMethods[$methodName] = $methodDescription;
            }

            return $this->_reflectionsMethods[$methodName];
        } catch(\Exception $e) {
            //Method not found
            throw new Exception\MethodNotImplemented(
                'Method "'.$methodName.'" is not available for this state',
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Build a new Injection Closure object
     * @return DI\InjectionClosureInterface
     * @throws Exception\IllegalService when there are no DI Container or Injection Closure Container bought
     */
    protected function _buildInjectionClosureObject()
    {
        $container = $this->getDIContainer();
        if (!$container instanceof DI\ContainerInterface) {
            throw new Exception\IllegalService('Error, no DI Container has been defined');
        }

        $diContainer = $container->get(StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER);
        if (!$diContainer instanceof DI\InjectionClosureInterface) {
            throw new Exception\IllegalService('Error, no Injection Container has been defined');
        }

        return $diContainer;
    }

    /**
     * Return a closure of the required method to use in the proxy
     * @param string $methodName
     * @param Proxy\ProxyInterface $proxy
     * @param string $scope self::VISIBILITY_PUBLIC|self::VISIBILITY_PROTECTED|self::VISIBILITY_PRIVATE
     * @return DI\InjectionClosureInterface
     * @throws Exception\MethodNotImplemented is the method does not exist or not available in this scope
     * @throws Exception\InvalidArgument when the method name is not a string
     * @throws Exception\IllegalProxy when the proxy does not implement the good interface
     * @throws Exception\IllegalService when there are no DI Container or Injection Closure Container bought
     */
    public function getClosure($methodName, $proxy, $scope=StateInterface::VISIBILITY_PUBLIC)
    {
        if (!is_string($methodName)) {
            throw new Exception\InvalidArgument('Error, the method name is not a valid string');
        }

        if (!($this->_closuresObjects instanceof \ArrayObject)) {
            //Initialize locale closure cache
            $this->_closuresObjects = new \ArrayObject();
        }

        if (!$proxy instanceof Proxy\ProxyInterface) {
            throw new Exception\IllegalProxy('Error, the proxy does not implement the required proxy');
        }

        if (!isset($this->_closuresObjects[$methodName])) {
            //The closure is not already generated
            //Extract them
            $methodReflection = $this->getMethodDescription($methodName);
            $closure = $methodReflection->getClosure($this);

            //Bind $this with proxy
            $closure = \Closure::bind($closure, $proxy, get_class($proxy));

            //Include the closure into the container
            $injectionClosure = $this->_buildInjectionClosureObject()->setClosure($closure);
            $injectionClosure->setDIContainer($this->getDIContainer());
            $this->_closuresObjects[$methodName] = $injectionClosure;
        }

        //Check visibility scope
        if (false === $this->_checkVisibility($methodName, $scope)) {
            throw new Exception\MethodNotImplemented(
                'Method "'.$methodName.'" is not available for this state'
            );
        }

        return $this->_closuresObjects[$methodName];
    }
}