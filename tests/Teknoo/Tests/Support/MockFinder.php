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
 * @license     http://teknoo.software/states/license/mit         MIT License
 * @license     http://teknoo.software/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Support;

use Teknoo\States\DI;
use Teknoo\States\Loader\Exception;
use Teknoo\States\Proxy;
use Teknoo\States\Loader;
use Teknoo\States\States;

/**
 * Class MockFinder
 * Mock finder to test behavior of proxies and factories.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/states/license/mit         MIT License
 * @license     http://teknoo.software/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class MockFinder implements Loader\FinderInterface
{
    /**
     * Mock container used for tests.
     *
     * @var DI\ContainerInterface
     */
    protected $container = null;

    /**
     * To not return the default state.
     *
     * @var bool
     */
    public static $ignoreDefaultState = false;

    /**
     * To test if the proxy has been loaded by the factory.
     *
     * @var bool
     */
    protected $proxyLoaded = false;

    /**
     * @var States\StateInterface
     */
    protected $lastMockState;

    /**
     * @var array
     */
    protected $parentsClassesNamesList = array();

    /**
     * @var string
     */
    protected $statedClassName;

    /**
     * Initialize finder.
     *
     * @param string $statedClassName
     * @param string $pathString
     */
    public function __construct($statedClassName, $pathString)
    {
        $this->statedClassName = $statedClassName;
    }

    /**
     * To register a DI container for this object.
     *
     * @param \Teknoo\States\DI\ContainerInterface $container
     *
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * To return the DI Container used for this object.
     *
     * @return \Teknoo\States\DI\ContainerInterface
     */
    public function getDIContainer()
    {
        return $this->container;
    }

    /**
     * List all available state object of the stated class.
     *
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
                'MockState3',
            );
        } else {
            return array(
                'MockState1',
                'MockState2',
                'MockState3',
            );
        }
    }

    /**
     * Load and build the required state object of the stated class.
     *
     * @param string $stateName
     *
     * @return States\StateInterface
     *
     * @throws Exception\UnReadablePath   if the state file is not readable
     * @throws Exception\UnavailableState if the required state is not available
     * @throws Exception\IllegalState     if the state object does not implement the interface
     */
    public function buildState($stateName)
    {
        //Return a new mock state object for tests
        $this->lastMockState = new MockState();

        return $this->lastMockState;
    }

    /**
     * @return States\StateInterface
     */
    public function getLastMockStateBuilt()
    {
        return $this->lastMockState;
    }

    /**
     * Load the required state object of the stated class.
     *
     * @param string $stateName
     *
     * @return \Teknoo\States\States\StateInterface
     */
    public function loadState($stateName)
    {
        return true;
    }

    /**
     * Load a proxy object for the stated class.
     *
     * @param array $arguments argument for proxy
     *
     * @return Proxy\ProxyInterface
     *
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function loadProxy($arguments = null)
    {
        $this->proxyLoaded = true;

        return true;
    }

    /**
     * To test if the proxy has been loaded by the proxy
     * Method added for tests to check factory behavior.
     *
     * @return bool
     */
    public function proxyHasBeenLoaded()
    {
        return $this->proxyLoaded;
    }

    /**
     * Load and build a proxy object of the stated class.
     *
     * @param array $arguments argument for proxy
     *
     * @return \Teknoo\States\Proxy\ProxyInterface
     */
    public function buildProxy($arguments = null)
    {
        return new MockProxy($arguments);
    }

    /**
     * To return the list of parents stated classes of this stated classes, library classes (Integrated proxy and
     * standard proxy are excluded).
     *
     * @return string[]
     *
     * @throws Exception\IllegalProxy If the proxy class is not valid
     */
    public function listParentsClassesNames()
    {
        return new \ArrayObject();
    }

    /**
     * To get the canonical stated class name associated to this state.
     *
     * @return $this
     */
    public function getStatedClassName()
    {
        return $this->statedClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function getStateParentsClassesNamesList($stateName)
    {
        return $this->parentsClassesNamesList;
    }

    /**
     * @param array $parentsClassesNamesList
     *
     * @return self
     */
    public function setParentsClassesNamesList($parentsClassesNamesList)
    {
        $this->parentsClassesNamesList = $parentsClassesNamesList;

        return $this;
    }
}
