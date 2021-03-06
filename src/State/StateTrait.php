<?php

/**
 * States.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

/**
 * @category    States
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
namespace Teknoo\States\State;

use Teknoo\States\Proxy\ProxyInterface;

/**
 * Class StateTrait
 * Default implementation of the state interface, representing states entities in stated class.
 * A trait implementation has been chosen to allow developer to write theirs owns factory, extendable from any class.
 *
 * @api
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
trait StateTrait
{
    /**
     * List of methods available for this state.
     *
     * @var \ArrayObject
     */
    private $methodsListArray;

    /**
     * Reflection class object of this state to extract closures and description.
     *
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * Reflections methods of this state to extract description and closures.
     *
     * @var \ReflectionMethod[]
     */
    private $reflectionsMethods;

    /**
     * List of closures already extracted and set into Injection Closure Container.
     *
     * @var \Closure[]
     */
    private $closuresObjects;

    /**
     * Methods to not return into descriptions.
     *
     * @var array
     */
    protected $methodsNamesToIgnoreArray = array(
        '__construct' => '__construct',
        '__destruct' => '__destruct',
        'getReflectionClass' => 'getReflectionClass',
        'checkVisibility' => 'checkVisibility',
        'listMethods' => 'listMethods',
        'testMethod' => 'testMethod',
        'getMethodDescription' => 'getMethodDescription',
        'getClosure' => 'getClosure',
        'setPrivateMode' => 'setPrivateMode',
        'isPrivateMode' => 'isPrivateMode',
        'getStatedClassName' => 'getStatedClassName',
        'setStatedClassName' => 'setStatedClassName',
        'setStateAliases' => 'setStateAliases',
        'getStateAliases' => 'getStateAliases',
    );

    /**
     * To know if the private mode is enable or not for this state (see isPrivateMode()).
     *
     * @var bool
     */
    private $privateModeStatus = false;

    /**
     * To know the canonical stated class name of the object owning this state container.
     *
     * @var string
     */
    private $statedClassName;

    /**
     * List of aliases of this state in the stated class.
     *
     * @var string[]
     */
    private $stateAliases = [];

    /**
     * To initialize this state.
     *
     * @param bool     $privateMode     : To know if the private mode is enable or not for this state (see isPrivateMode()).
     * @param string   $statedClassName : To know the canonical stated class name of the object owning this state container.
     * @param string[] $aliases         : List of aliases of this state in the stated class
     */
    public function __construct(bool $privateMode, string $statedClassName, array $aliases = [])
    {
        $this->setPrivateMode($privateMode);
        $this->setStatedClassName($statedClassName);
        $this->setStateAliases($aliases);
    }

    /**
     * To build the ReflectionClass for the current object.
     *
     * @api
     *
     * @return \ReflectionClass
     */
    private function getReflectionClass(): \ReflectionClass
    {
        if (null === $this->reflectionClass) {
            $this->reflectionClass = new \ReflectionClass(\get_class($this));
        }

        return $this->reflectionClass;
    }

    /**
     * To get the canonical stated class name associated to this state.
     *
     * @return string
     */
    public function getStatedClassName(): string
    {
        return $this->statedClassName;
    }

    /**
     * To set the canonical stated class name associated to this state.
     *
     * @param string $statedClassName
     *
     * @return StateInterface
     */
    public function setStatedClassName(string $statedClassName): StateInterface
    {
        $this->statedClassName = $statedClassName;

        return $this;
    }

    /**
     * To update the list of aliases of this state in the current stated class.
     *
     * @param string[] $aliases
     *
     * @return StateInterface
     */
    public function setStateAliases(array $aliases): StateInterface
    {
        $this->stateAliases = $aliases;

        return $this;
    }

    /**
     * Return the list of aliases of this state in the current stated class.
     *
     * @return string[]
     */
    public function getStateAliases()
    {
        return $this->stateAliases;
    }

    /**
     * To know if the mode Private is enabled : private method are only accessible from
     * method present in the same stated class and not from methods of children of this class.
     * By default this mode is disable.
     *
     * @return bool
     */
    public function isPrivateMode(): bool
    {
        return $this->privateModeStatus;
    }

    /**
     * To enable or disable the private mode of this state :
     * If the mode Private is enable, private method are only accessible from
     * method present in the same stated class and not from methods of children of this class.
     * By default this mode is disable.
     *
     * @param bool $enable
     *
     * @return StateInterface
     */
    public function setPrivateMode(bool $enable): StateInterface
    {
        $this->privateModeStatus = !empty($enable);

        return $this;
    }

    /**
     * To return an array of string listing all methods available in the state : public, protected and private.
     * Ignore static method, because there are incompatible with the stated behavior :
     * State can be only applied on instances entities like object,
     * and not on static entities which by nature have no states.
     *
     * @api
     *
     * @return string[]
     */
    public function listMethods()
    {
        if (null === $this->methodsListArray) {
            //Extract methods available in this states (all methods, public, protected and private)
            $thisReflectionClass = $this->getReflectionClass();
            $flags = \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE;
            $methodsArray = $thisReflectionClass->getMethods($flags);

            //Extract methods' names
            $methodsFinalArray = new \ArrayObject();
            foreach ($methodsArray as $methodReflection) {
                //We ignore all static methods, there are incompatible with stated behavior :
                //State can be only applied on instances entities like object,
                // and not on static entities which by nature have no states
                if (false === $methodReflection->isStatic()
                    && (false === $this->privateModeStatus || false === $methodReflection->isPrivate())) {
                    //Store reflection into the local cache
                    $methodNameString = $methodReflection->getName();
                    if (!isset($this->methodsNamesToIgnoreArray[$methodNameString])) {
                        $methodsFinalArray[] = $methodNameString;
                        $this->reflectionsMethods[$methodNameString] = $methodReflection;
                    }
                }
            }

            $this->methodsListArray = $methodsFinalArray;
        }

        return $this->methodsListArray;
    }

    /**
     * To check if the method is available in the required scope (check from the visibility of the method) :
     *  Public method : Method always available
     *  Protected method : Method available only for this stated class's methods (method present in this state or another state) and its children
     *  Private method : Method available only for this stated class's method (method present in this state or another state) and not for its children.
     *
     * @param string      $methodName
     * @param string      $scope
     * @param string|null $statedClassOriginName
     *
     * @return bool
     *
     * @throws Exception\InvalidArgument
     */
    private function checkVisibility(
        string $methodName,
        string $scope,
        string $statedClassOriginName = null
    ): bool {
        $visible = false;
        if (isset($this->reflectionsMethods[$methodName])) {
            //Check visibility scope
            switch ($scope) {
                case StateInterface::VISIBILITY_PRIVATE:
                    //To check if the caller method can be accessible by the method caller :
                    //The called method is protected or public (skip to next test)
                    //The private mode is disable for this state (state is not defined is a parent class)
                    //The caller method is in the same stated class that the called method
                    $privateMethodIsAvailable = true;
                    if (true === $this->privateModeStatus) {
                        if ($statedClassOriginName !== $this->statedClassName) {
                            if (true === $this->reflectionsMethods[$methodName]->isPrivate()) {
                                $privateMethodIsAvailable = false;
                            }
                        }
                    }

                    $visible = $privateMethodIsAvailable;
                    break;
                case StateInterface::VISIBILITY_PROTECTED:
                    //Can not access to private methods
                    if (false === $this->reflectionsMethods[$methodName]->isPrivate()) {
                        //It's a private method, do like if there is no method
                        $visible = true;
                    }
                    break;
                case StateInterface::VISIBILITY_PUBLIC:
                    //Can not access to protect and private method.
                    if (true === $this->reflectionsMethods[$methodName]->isPublic()) {
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
     * To test if a method exists for this state in the required scope (check from the visibility of the method) :
     *  Public method : Method always available
     *  Protected method : Method available only for this stated class's methods (method present in this state or another state) and its children
     *  Private method : Method available only for this stated class's method (method present in this state or another state) and not for its children.
     *
     * @param string      $methodName
     * @param string      $scope                 self::VISIBILITY_PUBLIC|self::VISIBILITY_PROTECTED|self::VISIBILITY_PRIVATE
     * @param string|null $statedClassOriginName
     *
     * @return bool
     *
     * @throws Exception\InvalidArgument when the method name is not a string
     */
    public function testMethod(
        string $methodName,
        string $scope = StateInterface::VISIBILITY_PUBLIC,
        string $statedClassOriginName = null
    ): bool {
        //Method is already extracted
        if (isset($this->reflectionsMethods[$methodName])) {
            if ($this->reflectionsMethods[$methodName] instanceof \ReflectionMethod) {
                return $this->checkVisibility($methodName, $scope, $statedClassOriginName);
            } else {
                return false;
            }
        }

        try {
            //Try extract description
            $this->getMethodDescription($methodName);
        } catch (\Throwable $e) {
            //Method not found, store locally the result
            $this->reflectionsMethods[$methodName] = false;

            return false;
        }

        //Return the result according with the visibility
        return $this->checkVisibility($methodName, $scope, $statedClassOriginName);
    }

    /**
     * To return the description of a method to configure the behavior of the proxy. Return also description of private
     * methods : getMethodDescription() does not check if the caller is allowed to call the required method.
     *
     * getMethodDescription() ignores static method, because there are incompatible with the stated behavior :
     * State can be only applied on instances entities like object,
     * and not on static entities which by nature have no states
     *
     * @api
     *
     * @param string $methodName
     *
     * @return \ReflectionMethod
     *
     * @throws Exception\MethodNotImplemented is the method does not exist
     */
    public function getMethodDescription(string $methodName): \ReflectionMethod
    {
        if (isset($this->methodsNamesToIgnoreArray[$methodName])) {
            throw new Exception\MethodNotImplemented('Error, this method is not implemented by this state');
        }

        $thisReflectionClass = $this->getReflectionClass();

        //Initialize ArrayObject to store Reflection Methods
        if (!($this->reflectionsMethods instanceof \ArrayObject)) {
            $this->reflectionsMethods = new \ArrayObject();
        }

        try {
            //Load Reflection Method if it is not already done
            if (!isset($this->reflectionsMethods[$methodName])) {
                $methodDescription = $thisReflectionClass->getMethod($methodName);
                if (false !== $methodDescription->isStatic()) {
                    //Ignores static method, because there are incompatible with the stated behavior :
                    // State can be only applied on instances entities like object,
                    // and not on static entities which by nature have no states
                    throw new Exception\MethodNotImplemented(
                        \sprintf('Method "%s" is not available for this state', $methodName)
                    );
                }

                $this->reflectionsMethods[$methodName] = $methodDescription;
            }

            return $this->reflectionsMethods[$methodName];
        } catch (\Throwable $e) {
            //Method not found
            throw new Exception\MethodNotImplemented(
                \sprintf('Method "%s" is not available for this state', $methodName),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * To return a closure of the required method to use in the proxy, in the required scope (check from the visibility of the method) :
     *  Public method : Method always available
     *  Protected method : Method available only for this stated class's methods (method present in this state or another state) and its children
     *  Private method : Method available only for this stated class's method (method present in this state or another state) and not for its children.
     *
     * @param ProxyInterface $proxy
     * @param string         $methodName
     * @param string         $scope                 self::VISIBILITY_PUBLIC|self::VISIBILITY_PROTECTED|self::VISIBILITY_PRIVATE
     * @param string|null    $statedClassOriginName
     *
     * @return \Closure
     *
     * @throws Exception\MethodNotImplemented is the method does not exist or not available in this scope
     */
    public function getClosure(
        ProxyInterface $proxy,
        string $methodName,
        string $scope = StateInterface::VISIBILITY_PUBLIC,
        string $statedClassOriginName = null
    ): \Closure {
        if (!($this->closuresObjects instanceof \ArrayObject)) {
            //Initialize locale closure cache
            $this->closuresObjects = new \ArrayObject();
        }

        $proxyIdentifier = \spl_object_hash($proxy);
        if (!isset($this->closuresObjects[$proxyIdentifier])) {
            $this->closuresObjects[$proxyIdentifier] = new \ArrayObject();
        }

        if (!isset($this->closuresObjects[$proxyIdentifier][$methodName])) {
            //The closure is not already generated, so extract them
            $methodReflection = $this->getMethodDescription($methodName);

            $this->closuresObjects[$proxyIdentifier][$methodName] = $methodReflection->getClosure($this)->bindTo($proxy);
        }

        //Check visibility scope
        if (false === $this->checkVisibility($methodName, $scope, $statedClassOriginName)) {
            throw new Exception\MethodNotImplemented(
                \sprintf('Method "%s" is not available for this state', $methodName)
            );
        }

        return $this->closuresObjects[$proxyIdentifier][$methodName];
    }
}
