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

namespace UniAlteri\States\Proxy;

/**
 * Class Standard
 * Variant of default Proxy class to use in stated class when no proxy are defined in these classes.
 *
 * A stated object is a proxy, configured for its stated class, with its different stated objects.
 * It is a proxy because, by default, all calls are redirected to enabled states.
 * $this in all methods of the stated object (also of states' methods) points the proxy object.
 *
 * The library creates an alias with the proxy class name and this default proxy to simulate a dedicated proxy
 * to this class.
 *
 * This proxy is a variant of the default proxy to allow developer to create an instance a stated class
 * like another class with the operator new.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class Integrated extends Standard implements IntegratedInterface
{
    use IntegratedTrait;

    /**
     * Class name of the factory to use in set up to initialize this object in this construction.
     *
     * @var string
     */
    protected static $startupFactoryClassName = '\UniAlteri\States\Factory\StandardStartupFactory';

    /**
     * Default constructor used to initialize the stated object with its factory.
     *
     * @throws Exception\IllegalFactory
     * @throws Exception\UnavailableFactory
     */
    public function __construct()
    {
        //Call the method of the trait to initialize local attributes of the proxy
        $this->initializeProxy();
        //Call the startup factory to initialize this proxy
        $this->initializeObjectWithFactory();
    }
}