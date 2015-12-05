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
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/states/license/mit         MIT License
 * @license     http://teknoo.software/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * Mock factory file to test command for cli helper
 */

namespace Acme\GoodFactory;

use Teknoo\States\DI;
use Teknoo\States\Factory\Exception;
use Teknoo\States\Factory\FactoryInterface;
use Teknoo\States\Loader;
use Teknoo\States\Proxy;

class Factory implements FactoryInterface
{
    /**
     * To register a DI container for this object.
     *
     * @param DI\ContainerInterface $container
     *
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container)
    {
    }

    /**
     * To return the DI Container used for this object.
     *
     * @return DI\ContainerInterface
     */
    public function getDIContainer()
    {
    }

    /**
     * To return the loader of this stated class from its DI Container.
     *
     * @return Loader\FinderInterface
     *
     * @throws Exception\UnavailableLoader      if any finder are available for this stated class
     * @throws Exception\UnavailableDIContainer if there are no di container
     */
    public function getFinder()
    {
    }

    /**
     * To return the stated class name used with this factory.
     *
     * @return string
     */
    public function getStatedClassName()
    {
    }

    /**
     * To return the path of the stated class.
     *
     * @return string
     */
    public function getPath()
    {
    }

    /**
     * Method called by the Loader to initialize the stated class :
     * It registers the class name and its path, retrieves the DI Container,
     * register the factory in the DI Container, it retrieves the finder object and load the proxy
     * from the finder.
     *
     * @param string $statedClassName the name of the stated class
     * @param string $path            of the stated class
     *
     * @return bool
     *
     * @throws Exception\UnavailableLoader      if any finder are available for this stated class
     * @throws Exception\UnavailableDIContainer if there are no di container
     */
    public function initialize($statedClassName, $path)
    {
    }

    /**
     * To initialize a proxy object with its container and states. States are fetched by the finder of this stated class.
     *
     * @param Proxy\ProxyInterface $proxyObject
     * @param string               $stateName
     *
     * @return bool
     *
     * @throws Exception\StateNotFound          if the $stateName was not found for this stated class
     * @throws Exception\UnavailableLoader      if any finder are available for this stated class
     * @throws Exception\IllegalProxy           if the proxy object does not implement the interface
     * @throws Exception\UnavailableDIContainer if there are no di container
     */
    public function startup($proxyObject, $stateName = null)
    {
    }

    /**
     * Build a new instance of an object.
     *
     * @param mixed  $arguments
     * @param string $stateName to build an object with a specific class
     *
     * @return Proxy\ProxyInterface
     *
     * @throws Exception\StateNotFound          if the $stateName was not found for this stated class
     * @throws Exception\UnavailableLoader      if any finder are available for this stated class
     * @throws Exception\UnavailableDIContainer if there are no di container
     */
    public function build($arguments = null, $stateName = null)
    {
    }
}