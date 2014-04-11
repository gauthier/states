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
 * @subpackage  Tests
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     0.9.2
 */

namespace UniAlteri\Tests\Support;

use UniAlteri\States\DI;
use UniAlteri\States\Loader\Exception;
use UniAlteri\States\Proxy;
use UniAlteri\States\Loader;
use UniAlteri\States\States;

/**
 * Class MockFinder
 * Mock finder to test behavior of proxies and factories
 *
 * @package     States
 * @subpackage  Tests
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class MockFinder implements Loader\FinderInterface
{
    /**
     * Mock container used for tests
     * @var DI\ContainerInterface
     */
    protected $_container = null;

    /**
     * To not return the default state
     * @var bool
     */
    public static $ignoreDefaultState = false;

    /**
     * To test if the proxy has been loaded by the factory
     * @var bool
     */
    protected $_proxyLoaded = false;

    /**
     * Initialize finder
     * @param string $statedClassName
     * @param string $pathString
     */
    public function __construct($statedClassName, $pathString)
    {

    }

    /**
     * Register a DI container for this object
     * @param \UniAlteri\States\DI\ContainerInterface $container
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container)
    {
        $this->_container = $container;
        return $this;
    }

    /**
     * Return the DI Container used for this object
     * @return \UniAlteri\States\DI\ContainerInterface
     */
    public function getDIContainer(){
        return $this->_container;
    }

    /**
     * List all available state object of the stated class
     * @return string[]
     */
    public function listStates()
    {
        //Return mock states
        if (empty(static::$ignoreDefaultState)) {
            return array(
                'MockState1',
                Proxy\ProxyInterface::DEFAULT_STATE_NAME,
                'MockState2',
                'MockState3'
            );
        } else {
            return array(
                'MockState1',
                'MockState2',
                'MockState3'
            );
        }
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
        //Return a new mock state object for tests
        return new MockState();
    }

    /**
     * Load the required state object of the stated class
     * @param string $stateName
     * @return \UniAlteri\States\States\StateInterface
     */
    public function loadState($stateName)
    {
        return true;
    }

    /**
     * Load a proxy object for the stated class
     * @param array $arguments argument for proxy
     * @return Proxy\ProxyInterface
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function loadProxy($arguments = null)
    {
        $this->_proxyLoaded = true;
        return true;
    }

    /**
     * To test if the proxy has been loaded by the proxy
     * Method added for tests to check factory behavior
     * @return boolean
     */
    public function proxyHasBeenLoaded()
    {
        return $this->_proxyLoaded;
    }

    /**
     * Load and build a proxy object of the stated class
     * @param array $arguments argument for proxy
     * @return \UniAlteri\States\Proxy\ProxyInterface
     */
    public function buildProxy($arguments=null)
    {
        return new MockProxy($arguments);
    }
}