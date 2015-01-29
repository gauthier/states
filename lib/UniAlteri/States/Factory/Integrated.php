<?php
/**
 * States
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @subpackage  Factory
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     1.0.1
 */
namespace UniAlteri\States\Factory;

use UniAlteri\States\DI;

/**
 * Class Integrated
 * Embedded "stated object" factory to use with this library to build a new instance of a stated class.
 * It is an alternative of Standard factory to allow developers to use the operator `new` with the stated class.
 *
 * @package     States
 * @subpackage  Factory
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class Integrated implements FactoryInterface
{
    use FactoryTrait {
        //Rename the initialize method of the trait to override it into this class.
        FactoryTrait::initialize as traitInitialize;
    }

    /**
     * Method called by the Loader to initialize the stated class :
     * It registers the class name and its path, retrieves the DI Container,
     * register the factory in the DI Container, it retrieves the finder object and load the proxy
     * from the finder.
     * @param  string                           $statedClassName the name of the stated class
     * @param  string                           $path            of the stated class
     * @return boolean
     * @throws Exception\UnavailableLoader      if any finder are available for this stated class
     * @throws Exception\UnavailableDIContainer if there are no di container
     */
    public function initialize($statedClassName, $path)
    {
        //Call trait's method to initialize this stated class
        $this->traitInitialize($statedClassName, $path);
        //Build the factory identifier (the proxy class name)
        $parts = explode('\\', $statedClassName);
        $statedClassName .= '\\'.array_pop($parts);
        //Register this factory into the startup factory
        StandardStartupFactory::registerFactory($statedClassName, $this);
    }
}
