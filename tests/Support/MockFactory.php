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
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Tests\Support;

use UniAlteri\States\DI;
use UniAlteri\States\Factory;
use UniAlteri\States\Factory\Exception;
use UniAlteri\States\Loader;
use UniAlteri\States\Proxy;

/**
 * Class MockFactory
 * Mock factory to tests proxies and loaders. Logs only all actions.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class MockFactory implements Factory\FactoryInterface
{
    /**
     * To list initialized factory by loader.
     *
     * @var string[]
     */
    protected static $initializedFactoryNameArray = array();

    /**
     * @var Proxy\ProxyInterface
     */
    protected $startupProxy;

    /**
     * @var string
     */
    protected $statedClassName = null;

    /**
     * @var string
     */
    protected $path = null;

    /**
     * @var DI\ContainerInterface
     */
    protected $diConainter = null;

    /**
     * To return the DI Container used for this object.
     *
     * @return DI\ContainerInterface
     */
    public function getDIContainer(): DI\ContainerInterface
    {
        return $this->diConainter;
    }

    /**
     * To register a DI container for this object.
     *
     * @param DI\ContainerInterface $container
     *
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container): Factory\FactoryInterface
    {
        $this->diConainter = $container;

        return $this;
    }

    /**
     * Return the loader of this stated class from its DI Container.
     *
     * @return Loader\FinderInterface
     *
     * @throws Exception\UnavailableLoader if any finder are available for this stated class
     */
    public function getFinder(): Loader\FinderInterface
    {
        //Build a new mock finder
        return new MockFinder($this->statedClassName, $this->path);
    }

    /**
     * Return the path of the stated class.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Return the stated class name used with this factory.
     *
     * @return string
     */
    public function getStatedClassName(): string
    {
        return $this->statedClassName;
    }

    /**
     * Method called by the Loader to initialize the stated class :
     *  Extends the proxy used by this stated class a child called like the stated class.
     *  => To allow developer to build new object with the operator new
     *  => To allow developer to use the operator "instanceof".
     *
     * @param string $statedClassName the name of the stated class
     * @param string $path            of the stated class
     *
     * @return $this
     */
    public function initialize(string $statedClassName, string $path): Factory\FactoryInterface
    {
        $this->statedClassName = $statedClassName;
        $this->path = $path;
        self::$initializedFactoryNameArray[] = $statedClassName.':'.$path;
        return $this;
    }

    /**
     * Method added for tests to get action logs
     * Return the list of initialized factories by the loader.
     *
     * @return string[]
     */
    public static function resetInitializedFactories()
    {
        self::$initializedFactoryNameArray = array();
    }

    /**
     * Method added for tests to get action logs
     * Return the list of initialized factories by the loader.
     *
     * @return string[]
     */
    public static function listInitializedFactories()
    {
        return array_values(self::$initializedFactoryNameArray);
    }

    /**
     * Build a new instance of an object.
     *
     * @param mixed  $arguments
     * @param string $stateName to build an object with a specific class
     *
     * @return Proxy\ProxyInterface
     *
     * @throws Exception\StateNotFound     if the $stateName was not found for this stated class
     * @throws Exception\UnavailableLoader if any loader are available for this stated class
     */
    public function build($arguments = null, string $stateName = null): Proxy\ProxyInterface
    {
        return new MockProxy(array());
    }

    /**
     * Initialize a proxy object with its container and states.
     *
     * @param Proxy\ProxyInterface $proxyObject
     * @param string               $stateName
     *
     * @return $this
     *
     * @throws Exception\StateNotFound     if the $stateName was not found for this stated class
     * @throws Exception\UnavailableLoader if any loader are available for this stated class
     */
    public function startup(Proxy\ProxyInterface $proxyObject, string $stateName = null): Factory\FactoryInterface
    {
        $this->startupProxy = $proxyObject;

        return $this;
    }

    /**
     * Get the proxy called to startup it
     * Method added for tests to check startup behavior.
     *
     * @return Proxy\ProxyInterface
     */
    public function getStartupProxy(): Proxy\ProxyInterface
    {
        return $this->startupProxy;
    }
}