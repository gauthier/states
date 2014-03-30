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
 * @subpackage  Factory
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     $Id$
 */

namespace UniAlteri\States\Factory;
use UniAlteri\States\DI;

/**
 * Class Integrated
 * @package UniAlteri\States\Factory
 * Default "stated object" factory to use with this library to build a new instance
 * of a stated class. This class is used when a stated class does not provide its own factory.
 *
 * The library create an alias with the class's factory name and this default factory
 * to simulate a dedicated factory to this class
 */
class Integrated implements FactoryInterface
{
    use TraitFactory {
        TraitFactory::initialize as traitInitialize;
    }

    public function initialize($statedClassName, $path)
    {
        $this->traitInitialize($statedClassName, $path);
        $parts = explode('\\', $statedClassName);
        $statedClassName .= '\\'.array_pop($parts);
        StandardStartupFactory::registerFactory($statedClassName, $this);
    }
}