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
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     $Id$
 */

namespace UniAlteri\States;

use \UniAlteri\States\DI;
use \UniAlteri\States\Loader;
use \UniAlteri\States\Factory;

//Initial DI Container
$diContainer = new DI\Container();

//Service to generate a finder for Stated class factory
/**
 * @param DI\ContainerInterface $container
 * @return Loader\FinderIntegrated
 */
$finderService = function ($container) {
    $factory = $container->get(Factory\FactoryInterface::DI_FACTORY_NAME);
    return new Loader\FinderIntegrated($factory->getStatedClassName(), $factory->getPath());
};

//Register finder generator
$diContainer->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, $finderService);

//Stated class loader, initialize
$includePathManager = new Loader\IncludePathManager();
$loader = new Loader\LoaderStandard($includePathManager);
$loader->setDIContainer($diContainer);

//Register loader into container
$diContainer->registerInstance(Loader\LoaderInterface::DI_LOADER_INSTANCE, $loader);

//Use default spl autoloader, UA States lib use PSR-0 standards
spl_autoload_register(
    function ($className) {
        $path = str_replace(array('\\', '_'), '/', $className).'.php';
        include_once($path);
        $included = class_exists($className, false);
        return $included;
    },
    true
);

//Register autoload function in the spl autoloader stack
spl_autoload_register(
    array($loader, 'loadClass'),
    true
);

//Return the loader for the caller file
return $loader;