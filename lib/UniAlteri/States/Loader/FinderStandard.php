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
 * @subpackage  Loader
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     $Id$
 */

namespace UniAlteri\States\Loader;

use \UniAlteri\States\Loader\Exception;
use \UniAlteri\States\DI;
use \UniAlteri\States\States;
use \UniAlteri\States\Proxy;

/**
 * Class FinderStandard
 * @package UniAlteri\States\Loader
 * @api
 *
 * Default implementation of the finder. It is used with this library to find and load
 * from each stated class all states and the proxy
 */
class FinderStandard implements FinderInterface
{
    /**
     * Current stated class's name
     * @var string
     */
    protected $_statedClassName = null;

    /**
     * Folder/Phar of the stated class
     * @var string
     */
    protected $_pathString = null;

    /**
     * DI Container to use with this finder
     * @var DI\ContainerInterface
     */
    protected $_diContainer = null;

    /**
     * Default proxy class name to use when there are no proxy class name
     * @var string
     */
    protected $_defaultProxyClassName = '\UniAlteri\States\Proxy\Standard';

    /**
     * Initialize finder
     * @param string $statedClassName
     * @param string $pathString
     */
    public function __construct($statedClassName, $pathString)
    {
        $this->_statedClassName = $statedClassName;
        $this->_pathString = $pathString;
    }

    /**
     * Register a DI container for this object
     * @param DI\ContainerInterface $container
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container){
        $this->_diContainer = $container;
        return $this;
    }

    /**
     * Return the DI Container used for this object
     * @return DI\ContainerInterface
     */
    public function getDIContainer(){
        return $this->_diContainer;
    }

    /**
     * List all available state object of the stated class
     * @return string[]
     * @throws Exception\UnavailablePath if the states' folder is not available
     * @throws Exception\UnReadablePath if the states' folder is not readable
     */
    public function listStates()
    {
        //Check if states are stored into the standardized path
        $statesPath = $this->_pathString.DIRECTORY_SEPARATOR.FinderInterface::STATES_PATH;
        if (!is_dir($statesPath)) {
            throw new Exception\UnavailablePath('Error, the path "'.$statesPath.'" was not found');
        }

        //Check if the path is available
        $hD = @opendir($statesPath);
        if (false === $hD) {
            throw new Exception\UnReadablePath('Error, the path "'.$statesPath.'" is not available');
        }

        //Extract all states (No check class exists)
        $statesNameArray = new \ArrayObject();
        while (false !== ($file = readdir($hD))) {
            switch ($file) {
                case '.';
                case '..';
                    break;
                default:
                    if (strlen($file) - 4 == strrpos($file, '.php')) {
                        $stateName = substr($file, 0, -4);
                        $statesNameArray[] = $stateName;
                    }
                    break;
            }
        }

        closedir($hD);
        return $statesNameArray;
    }

    /**
     * Load the required state object of the stated class
     * @param string $stateName
     * @return string
     * @throws Exception\UnReadablePath if the stated file is not readable
     * @throws Exception\UnavailableState if the required state is not available
     */
    public function loadState($stateName)
    {
        $stateClassName = $this->_statedClassName.'\\'.FinderInterface::STATES_PATH.'\\'.$stateName;
        if (!class_exists($stateClassName, false)) {
            $statePath = $this->_pathString
                            .DIRECTORY_SEPARATOR.FinderInterface::STATES_PATH
                            .DIRECTORY_SEPARATOR.$stateName.'.php';

            if (!is_readable($statePath)) {
                throw new Exception\UnReadablePath('Error, the state "'.$stateName.'" was not found');
            }

            include_once($statePath);
            $stateClassName = $this->_statedClassName.'\\'.FinderInterface::STATES_PATH.'\\'.$stateName;
            if (!class_exists($stateClassName, false)) {
                throw new Exception\UnavailableState('Error, the state "'.$stateName.'" is not available');
            }
        }

        return $stateClassName;
    }

    /**
     * Load and build the required state object of the stated class
     * @param string $stateName
     * @return States\StateInterface
     * @throws Exception\UnReadablePath if the state file is not readable
     * @throws Exception\UnavailableState if the required state is not available
     * @throws Exception\IllegalState if the state object does not implement the interface
     */
    public function buildState($stateName)
    {
        //Load the state class if it is not already done
        $stateClassName = $this->loadState($stateName);

        $stateObject = new $stateClassName;
        if (!$stateObject instanceof States\StateInterface) {
            throw new Exception\IllegalState(
                'Error, the state "'.$stateName.'" does not implement the interface "States\StateInterface"'
            );
        }

        return $stateObject;
    }

    /**
     * Extract the class name from the stated class name with namespace
     * @param string $statedClassName
     * @return string
     */
    protected function _getClassedName($statedClassName)
    {
        $parts = explode('\\', $statedClassName);
        return array_pop($parts);
    }

    /**
     * Search and load the proxy class for this stated class.
     * If the class has not proxy, load the default proxy for this stated class
     * @return string
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function loadProxy()
    {
        //Build the class name
        $classPartName = $this->_getClassedName($this->_statedClassName);
        $proxyClassName = $this->_statedClassName.'\\'.$classPartName;
        if (!class_exists($proxyClassName, false)) {
            //Build the class file path for the proxy (standardized into ProxyInterface)
            $proxyPath = $this->_pathString.DIRECTORY_SEPARATOR.FinderInterface::PROXY_FILE_NAME;

            if (!is_readable($proxyPath)) {
                //The stated class has not its own proxy, reuse the standard proxy, as an alias
                class_alias($this->_defaultProxyClassName, $proxyClassName);
                class_alias($this->_defaultProxyClassName, $this->_statedClassName);
                return $proxyClassName;
            }

            include_once($proxyPath);
            if (!class_exists($proxyClassName, false)) {
                throw new Exception\IllegalProxy(
                    'Error, the proxy of "'.$this->_statedClassName.'" must be called <StatedClassName>\''.$classPartName
                );
            } else {
                //To access this class directly without repeat the stated class name
                class_alias($proxyClassName, $this->_statedClassName);
            }
        }

        return $proxyClassName;
    }

    /**
     * Load and build a proxy object for the stated class
     * @param array $arguments argument for proxy
     * @return Proxy\ProxyInterface
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function buildProxy($arguments=null)
    {
        //Load the proxy if it is not already done
        $proxyClassName = $this->loadProxy();

        //Load an instance of this proxy and test if it implements the interface ProxyInterface
        $proxyObject = new $proxyClassName($arguments);
        if ($proxyObject instanceof Proxy\ProxyInterface) {
            return $proxyObject;
        }

        //Throw an error
        throw new Exception\IllegalProxy(
            'Error, the proxy of "'.$this->_statedClassName.'" does not implement "Proxy\ProxyInterface"'
        );
    }
}