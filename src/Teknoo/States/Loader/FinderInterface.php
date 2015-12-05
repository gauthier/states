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
 */

namespace Teknoo\States\Loader;

use Teknoo\States\DI;
use Teknoo\States\States;
use Teknoo\States\Proxy;

/**
 * Interface FinderInterface
 * Interface to define finder to use with this library to find from each stated class all states and the proxy.
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
 * @api
 */
interface FinderInterface
{
    /**
     * Name of Finder (service to find and load elements of stated class).
     */
    const DI_FINDER_SERVICE = 'FinderStates';

    /**
     * Folder where stored states of the stated class.
     */
    const STATES_PATH = 'States';

    /**
     * PHP File of Proxy into each stated class.
     */
    const PROXY_FILE_EXTENSION = '.php';

    /**
     * Initialize finder.
     *
     * @param string $statedClassName
     * @param string $pathString
     */
    public function __construct($statedClassName, $pathString);

    /**
     * To register a DI container for this object.
     *
     * @param DI\ContainerInterface $container
     *
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container);

    /**
     * To return the DI Container used for this object.
     *
     * @return DI\ContainerInterface
     */
    public function getDIContainer();

    /**
     * To get the canonical stated class name associated to this state.
     *
     * @return $this
     */
    public function getStatedClassName();

    /**
     * To list all available states of the stated class.
     *
     * @return string[]
     *
     * @throws Exception\UnavailablePath if the states' folder is not available
     * @throws Exception\UnReadablePath  if the states' folder is not readable
     */
    public function listStates();

    /**
     * To load the required state object of the stated class.
     *
     * @param string $stateName
     *
     * @return string
     *
     * @throws Exception\UnReadablePath   if the stated file is not readable
     * @throws Exception\UnavailableState if the required state is not available
     */
    public function loadState($stateName);

    /**
     * To return the list of parent php classes used by a state.
     *
     * @param string $stateName
     *
     * @return string[]
     *
     * @throws Exception\UnavailableState if the required state is not available
     */
    public function getStateParentsClassesNamesList($stateName);

    /**
     * To load and build the required state object of the stated class.
     *
     * @param string $stateName
     *
     * @return States\StateInterface
     *
     * @throws Exception\UnReadablePath   if the state file is not readable
     * @throws Exception\UnavailableState if the required state is not available
     * @throws Exception\IllegalState     if the state object does not implement the interface
     */
    public function buildState($stateName);

    /**
     * To search and load the proxy class for this stated class.
     * If the class has not proxy, load the default proxy for this stated class.
     *
     * @return string
     *
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function loadProxy();

    /**
     * To return the list of parents stated classes of this stated classes, library classes (Integrated proxy and
     * standard proxy are excluded).
     *
     * @return string[]
     *
     * @throws Exception\IllegalProxy If the proxy class is not valid
     */
    public function listParentsClassesNames();

    /**
     * To load and build a proxy object for the stated class.
     *
     * @param array $arguments argument for proxy
     *
     * @return Proxy\ProxyInterface
     *
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function buildProxy($arguments = null);
}