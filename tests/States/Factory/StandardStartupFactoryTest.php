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

namespace UniAlteri\Tests\States\Factory;

use UniAlteri\States\Proxy;
use UniAlteri\States\Factory;
use UniAlteri\States\Factory\Exception;
use UniAlteri\Tests\Support;

/**
 * Class StandardStartupFactoryTest
 * Test the exception behavior of the start up standard factory.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class StandardStartupFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepare test, reinitialize the StandardStartupFactory.
     */
    protected function setUp()
    {
        Factory\StandardStartupFactory::reset();
        parent::setUp();
    }

    /**
     * The startup factory must throw an exception when the proxy cannot be initialized.
     */
    public function testForwardStartupProxyNotInitialized()
    {
        try {
            Factory\StandardStartupFactory::forwardStartup(new Support\MockProxy(null));
        } catch (Exception\UnavailableFactory $e) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the startup factory must throw an exception when the proxy cannot be initialized');
    }

    /**
     * Test normal behavior of forward startup.
     */
    public function testForwardStartup()
    {
        $factory = new Support\MockFactory();
        Factory\StandardStartupFactory::registerFactory('UniAlteri\Tests\Support\MockProxy', $factory);
        $proxy = new Support\MockProxy(null);
        Factory\StandardStartupFactory::forwardStartup($proxy);
        $this->assertSame($factory->getStartupProxy(), $proxy);
    }

    /**
     * Test Factory\StandardStartupFactory::listRegisteredFactory if its return all initialized factory.
     */
    public function testListRegisteredFactory()
    {
        $factory = new Support\MockFactory();
        Factory\StandardStartupFactory::registerFactory('UniAlteri\Tests\Support\MockProxy1', $factory);
        Factory\StandardStartupFactory::reset();
        Factory\StandardStartupFactory::registerFactory('UniAlteri\Tests\Support\MockProxy2', $factory);
        Factory\StandardStartupFactory::registerFactory('UniAlteri\Tests\Support\MockProxy3', $factory);
        $this->assertEquals(
            array(
                'UniAlteri\Tests\Support\MockProxy2',
                'UniAlteri\Tests\Support\MockProxy3',
            ),
            Factory\StandardStartupFactory::listRegisteredFactory()
        );
    }

    /**
     * Test Factory\StandardStartupFactory::listRegisteredFactory if its return all initialized factory.
     */
    public function testListRegisteredFactoryEmpty()
    {
        Factory\StandardStartupFactory::reset();
        $this->assertEquals(
            array(),
            Factory\StandardStartupFactory::listRegisteredFactory()
        );
    }
}