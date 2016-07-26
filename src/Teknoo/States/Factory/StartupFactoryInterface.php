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

use Teknoo\States\Proxy;

/**
 * Interface StartupFactoryInterface
 * Interface to define a factory used to initialize a stated class instance during in constructor.
 * This factory will only find the object's factory to forward to it the call.
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
interface StartupFactoryInterface
{
    /**
     * To find the factory to use for the new proxy object to initialize it with its container and states.
     * This method is called by the constructor of the stated class instance.
     *
     * @param Proxy\ProxyInterface $proxyObject
     * @param string               $stateName
     *
     * @return bool
     *
     * @throws Exception\InvalidArgument    when $factoryIdentifier is not an object
     * @throws Exception\UnavailableFactory when the required factory was not found
     */
    public static function forwardStartup($proxyObject, $stateName = null);
}
