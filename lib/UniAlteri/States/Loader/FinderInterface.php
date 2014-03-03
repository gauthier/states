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
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     $Id$
 */

namespace UniAlteri\States\Loader;

use \UniAlteri\States\DI;
use \UniAlteri\States\States;
use \UniAlteri\States\Proxy;

/**
 * Interface FinderInterface
 * @package UniAlteri\States\Loader
 * Interface to define finder to use with this library to find and load from each stated class
 * all states and the proxy
 */
interface FinderInterface
{
    /**
     * Name of Finder (service to find and load elements of stated class)
     */
    const DI_FINDER_NAME = 'FinderLoader';

    /**
     * Folder where stored states of the stated class
     */
    const STATES_PATH = 'States';

    /**
     * PHP File of Proxy into each stated class
     */
    const PROXY_FILE_NAME = 'Proxy.php';

    /**
     * PHP File of Factory into each stated class
     */
    const FACTORY_FILE_NAME = 'Factory.php';

    /**
     * Suffix name of the Proxy PHP Class of each Stated Class (The pattern is <statedClassName>[Suffix]
     */
    const PROXY_SUFFIX_CLASS_NAME = 'Proxy';

    /**
     * Suffix name of the Factory PHP Class of each Stated Class (The pattern is <statedClassName>[Suffix]
     */
    const FACTORY_SUFFIX_CLASS_NAME = 'Factory';

    /**
     * Initialize finder
     * @param string $statedClassName
     * @param string $pathString
     */
    public function __construct($statedClassName, $pathString);

    /**
     * Register a DI container for this object
     * @param DI\ContainerInterface $container
     */
    public function setDIContainer(DI\ContainerInterface $container);

    /**
     * Return the DI Container used for this object
     * @return DI\ContainerInterface
     */
    public function getDIContainer();

    /**
     * List all available state object of the stated class
     * @return string[]
     * @throws Exception\UnavailablePath if the states's folder is not available
     * @throws Exception\UnReadablePath if the states's folder is not readable
     */
    public function listStates();

    /**
     * Load and build the required state object of the stated class
     * @param string $stateName
     * @return States\StateInterface
     * @throws Exception\UnReadablePath if the state file is not readable
     * @throws Exception\UnavailableState if the required state is not available
     * @throws Exception\IllegalState if the state object does not implement the interface
     */
    public function loadState($stateName);

    /**
     * Load and build a proxy object for the stated class
     * @return Proxy\ProxyInterface
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function loadProxy();
}