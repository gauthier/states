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

namespace Teknoo\States\Factory;

use Teknoo\States\DI;

/**
 * Class Integrated
 * Embedded "stated class instance" factory to use with this library to build a new instance of a stated class.
 * It is an alternative of Standard factory to allow developers to use the operator `new` with the stated class.
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
        //Call trait's method to initialize this stated class
        $this->traitInitialize($statedClassName, $path);
        //Build the factory identifier (the proxy class name)
        $parts = explode('\\', $statedClassName);
        $statedClassName .= '\\'.array_pop($parts);
        //Register this factory into the startup factory
        StandardStartupFactory::registerFactory($statedClassName, $this);
    }
}
