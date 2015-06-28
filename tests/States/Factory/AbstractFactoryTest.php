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

use UniAlteri\States\DI;
use UniAlteri\States\Loader;
use UniAlteri\States\Proxy;
use UniAlteri\States\Factory;
use UniAlteri\States\Factory\Exception;
use UniAlteri\Tests\Support;

/**
 * Class AbstractFactoryTest.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
abstract class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Mock container used for test.
     *
     * @var Support\MockDIContainer
     */
    protected $container = null;

    /**
     * Mock finder used for test.
     *
     * @var Support\MockFinder
     */
    protected $virtualFinder = null;

    /**
     * Initialize container used into Factory.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->container = new Support\MockDIContainer();
        $this->registerMockFinderService();
    }

    /**
     * Configure container.
     */
    protected function registerMockFinderService()
    {
        $this->container->unregister(Loader\FinderInterface::DI_FINDER_SERVICE);
        $this->container->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, function ($container) {
            if ($container->testEntry(Factory\FactoryInterface::DI_FACTORY_NAME)) {
                $factory = $container->get(Factory\FactoryInterface::DI_FACTORY_NAME);

                return new Support\MockFinder($factory->getStatedClassName(), $factory->getPath());
            } else {
                return new Support\MockFinder('', '');
            }
        });
    }

    /**
     * Replace finder service to generate virtual finder whose return ArrayObject instead of php array.
     */
    protected function registerMockFinderServiceWithArrayObject()
    {
        $this->container->unregister(Loader\FinderInterface::DI_FINDER_SERVICE);
        $this->container->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, function ($container) {
            if ($container->testEntry(Factory\FactoryInterface::DI_FACTORY_NAME)) {
                $factory = $container->get(Factory\FactoryInterface::DI_FACTORY_NAME);

                return new Support\MockFinderWithArray($factory->getStatedClassName(), $factory->getPath());
            } else {
                return new Support\MockFinderWithArray('', '');
            }
        });
    }

    /**
     * Replace finder service to generate virtual finder mock to use to test inheritance.
     */
    protected function registerMockFinderServiceForInheritance()
    {
        $this->container->unregister(Loader\FinderInterface::DI_FINDER_SERVICE);
        $this->container->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, function ($container) {
            if ($container->testEntry(Factory\FactoryInterface::DI_FACTORY_NAME)) {
                $factory = $container->get(Factory\FactoryInterface::DI_FACTORY_NAME);

                return new Support\MockFinderInheritance($factory->getStatedClassName(), $factory->getPath());
            } else {
                return new Support\MockFinderInheritance('', '');
            }
        });
    }

    /**
     * Return the Factory Object Interface.
     *
     * @param bool $populateContainer to populate di container of this factory
     *
     * @return Factory\FactoryInterface
     */
    abstract public function getFactoryObject($populateContainer = true);

    /**
     * Test behavior for methods Set And GetDiContainer.
     */
    public function testSetAndGetDiContainer()
    {
        $object = $this->getFactoryObject(false);
        $virtualContainer = new Support\MockDIContainer();
        $this->assertSame($object, $object->setDIContainer($virtualContainer));
        $this->assertSame($virtualContainer, $object->getDIContainer());
    }

    /**
     * The method getFinder of the factory requires the di container to get the finder generator, else throw exception.
     */
    public function testGetFinderExceptionNoContainer()
    {
        try {
            $this->getFactoryObject(false)->getFinder();
        } catch (Exception\UnavailableDIContainer $e) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the factory must throw an exception when there are no di container');
    }

    /**
     * The method getFinder of the factory requires the finder generator, else throw exception.
     */
    public function testGetFinderExceptionNoFinderServiceGenerator()
    {
        try {
            $this->container->unregister(Loader\FinderInterface::DI_FINDER_SERVICE);
            $this->getFactoryObject(true)->getFinder();
        } catch (Exception\UnavailableLoader $e) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the factory must throw an exception when there are no finder generator into di container');
    }

    /**
     * The method getFinder of the factory requires the finder generator, else throw exception.
     */
    public function testGetFinderExceptionBadFinderReturnedServiceGenerator()
    {
        try {
            $this->container->unregister(Loader\FinderInterface::DI_FINDER_SERVICE);
            $this->container->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, function () {
                return new \stdClass();
            });
            $this->getFactoryObject(true)->getFinder();
        } catch (Exception\UnavailableLoader $e) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the factory must throw an exception when there are the finder generator into di container return a bad object, not implementing the Finder Interface');
    }

    /**
     * Test the factory behavior to build a new finder object from the service registered into its DI.
     */
    public function testGetFinder()
    {
        $this->assertInstanceOf('UniAlteri\States\Loader\FinderInterface', $this->getFactoryObject(true)->getFinder());
    }

    /**
     * Test the behavior of the method getStatedClassName() with values (stated class name and path) defined
     * by the loading during factory initialization.
     */
    public function testGetStatedClassName()
    {
        $factory = $this->getFactoryObject(true);
        $factory->initialize('foo', 'bar');
        $this->assertEquals('foo', $factory->getStatedClassName());
    }

    /**
     * Test the behavior of the method getPath() with values (stated class name and path) defined
     * by the loading during factory initialization.
     */
    public function testGetPath()
    {
        $factory = $this->getFactoryObject(true);
        $factory->initialize('foo', 'bar');
        $this->assertEquals('bar', $factory->getPath());
    }

    /**
     * Test the behavior of the method initialize() called by the loading during factory initialization
     * - Prerequisite : Finder service (to create new Finder instance dedicated for its stated class)
     * The factory must find and load the proxy class (but not create an instance) :
     * If the proxy class is not defined for the stated class, it must create an alias from the standard or integrated proxy.
     */
    public function testInitialize()
    {
        $virtualFinder = new Support\MockFinder('', '');
        $this->container->unregister(Loader\FinderInterface::DI_FINDER_SERVICE);
        $this->container->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, function () use ($virtualFinder) {
            return $virtualFinder;
        });

        $factory = $this->getFactoryObject(true);

        //Register Di Container
        $repository = new Support\MockDIContainer();
        $this->container->registerInstance(Factory\FactoryInterface::DI_FACTORY_REPOSITORY, $repository);

        $factory->initialize('foo', 'bar');
        $this->assertTrue($virtualFinder->proxyHasBeenLoaded());
        $this->assertSame($factory, $repository->get($factory->getStatedClassName()));
    }

    /**
     * Test exceptions thrown when the stated class has no default state.
     */
    public function testExceptionDefaultStateNotAvailableInStartup()
    {
        try {
            Support\MockFinder::$ignoreDefaultState = true;
            $this->getFactoryObject()->startup(new Support\MockProxy(null));
        } catch (Exception\StateNotFound $exception) {
            return;
        }

        $this->fail('Error, if the stated class has not a default state, the factory must throw an exception StateNotFound');
    }

    /**
     * Test exceptions thrown when the stated class has not the required starting state.
     */
    public function testExceptionRequiredStateNotAvailableInStartup()
    {
        try {
            Support\MockFinder::$ignoreDefaultState = false;
            $this->getFactoryObject()->startup(new Support\MockProxy(null), 'NonExistentState');
        } catch (Exception\StateNotFound $exception) {
            return;
        }

        $this->fail('Error, if the stated class has not the required starting state, the factory must throw an exception StateNotFound');
    }

    /**
     * Test if the factory can retrieve from the finder the list of available states for the stated class.
     */
    public function testListAvailableStateInStartup()
    {
        $proxy = new Support\MockProxy(null);
        $this->registerMockFinderService();
        $this->getFactoryObject(true)
            ->startup($proxy);
        $this->assertEquals(
            array(
                'MockState1',
                Proxy\ProxyInterface::DEFAULT_STATE_NAME,
                'MockState2',
                'MockState3',
            ),
            $proxy->listAvailableStates()
        );
    }

    /**
     * Test if the factory can retrieve from the finder the list of available states for the stated class.
     */
    public function testListAvailableStateInStartupWithInheritanceMotherNotFound()
    {
        $factoryMother = $this->getFactoryObject();
        $factoryMother->getFinder();

        //Register Di Container
        $repository = new Support\MockDIContainer();
        $this->container->registerInstance(Factory\FactoryInterface::DI_FACTORY_REPOSITORY, $repository);

        //Finder
        $factoryDaughter = $this->getFactoryObject();
        $this->registerMockFinderServiceForInheritance();
        $factoryDaughter->getFinder();

        $proxy = new Support\MockProxyChild(null);
        try {
            $factoryDaughter->startup($proxy);
        } catch (Exception\UnavailableFactory $e) {
            return;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());

            return;
        }

        $this->fail('Error, the factory must throw an exception if it can not found parent factory in registry');
    }

    /**
     * Test if the factory can retrieve from the finder the list of available states for the stated class.
     */
    public function testListAvailableStateInStartupWithInheritance()
    {
        $this->registerMockFinderService();
        $factoryMother = $this->getFactoryObject(true);
        $factoryMother->getFinder();

        //Register Di Container
        $repository = new Support\MockDIContainer();
        $repository->registerInstance('UniAlteri\Tests\Support\MockProxy', $factoryMother);
        $this->container->registerInstance(Factory\FactoryInterface::DI_FACTORY_REPOSITORY, $repository);

        //Finder
        $this->registerMockFinderService();
        $factoryDaughter = $this->getFactoryObject(true);
        $this->registerMockFinderServiceForInheritance();
        $factoryDaughter->getFinder();

        $proxy = new Support\MockProxyChild(null);
        $factoryDaughter->startup($proxy);
        $this->assertEquals(
            array(
                'MockState1',
                Proxy\ProxyInterface::DEFAULT_STATE_NAME,
                'MockState4',
                'MockState2',
                'MockState3',
            ),
            $proxy->listAvailableStates()
        );

        $this->assertFalse($proxy->getState('MockState1')->isPrivateMode());
        $this->assertFalse($proxy->getState(Proxy\ProxyInterface::DEFAULT_STATE_NAME)->isPrivateMode());
        $this->assertFalse($proxy->getState('MockState4')->isPrivateMode());
        $this->assertTrue($proxy->getState('MockState2')->isPrivateMode());
        $this->assertTrue($proxy->getState('MockState3')->isPrivateMode());
    }

    /**
     * Check if the factory, when it initialize a new proxy, enable the default state if there is no defined startup state.
     */
    public function testDefaultStateAutomaticallySelectedInStartup()
    {
        $proxy = new Support\MockProxy(null);
        $this->getFactoryObject()->startup($proxy);
        $this->assertEquals($proxy->listEnabledStates(), array('StateDefault'));
    }

    /**
     * Check if the factory, when it initialize a new proxy, enable the required state if there is defined startup state.
     */
    public function testRequiredStateSelectedInStartup()
    {
        $proxy = new Support\MockProxy(null);
        $this->getFactoryObject()->startup($proxy, 'MockState1');
        $this->assertEquals($proxy->listEnabledStates(), array('MockState1'));
    }

    /**
     * Check if the factory register all available states of the stated class in the new proxy
     * (Finder use ArrayObject instead of array to return the list of states).
     */
    public function testListAvailableStateInStartupWithArrayObject()
    {
        $proxy = new Support\MockProxy(null);
        $this->registerMockFinderServiceWithArrayObject();
        $this->getFactoryObject()->startup($proxy);
        $this->assertEquals(
            array(
                'MockState1',
                'MockState2',
                Proxy\ProxyInterface::DEFAULT_STATE_NAME,
                'MockState3',
            ),
            $proxy->listAvailableStates()
        );
    }

    /**
     * Check if the factory, when it initialize a new proxy, enable the default state if there is no defined startup state
     * (Finder use ArrayObject instead of array to return the list of states).
     */
    public function testDefaultStateAutomaticallySelectedInStartupWithArrayObject()
    {
        $proxy = new Support\MockProxy(null);
        $this->registerMockFinderServiceWithArrayObject();
        $this->getFactoryObject()->startup($proxy);
        $this->assertEquals($proxy->listEnabledStates(), array('StateDefault'));
    }

    /**
     * Check if the factory, when it initialize a new proxy, enable the required state if there is defined startup state
     * (Finder use ArrayObject instead of array to return the list of states).
     */
    public function testRequiredStateSelectedInStartupWithArrayObject()
    {
        $proxy = new Support\MockProxy(null);
        $this->registerMockFinderServiceWithArrayObject();
        $this->getFactoryObject()->startup($proxy, 'MockState1');
        $this->assertEquals($proxy->listEnabledStates(), array('MockState1'));
    }

    /**
     * Test exceptions thrown when the stated class has no default state.
     */
    public function testExceptionDefaultStateNotAvailable()
    {
        try {
            Support\MockFinder::$ignoreDefaultState = true;
            $this->getFactoryObject()->build();
        } catch (Exception\StateNotFound $exception) {
            return;
        }

        $this->fail('Error, if the stated class has not a default state, the factory must throw an exception StateNotFound');
    }

    /**
     * Test exceptions thrown when the stated class has not the required starting state.
     */
    public function testExceptionRequiredStateNotAvailable()
    {
        try {
            Support\MockFinder::$ignoreDefaultState = false;
            $this->getFactoryObject()->build(false, 'NonExistentState');
        } catch (Exception\StateNotFound $exception) {
            return;
        }

        $this->fail('Error, if the stated class has not the required starting state, the factory must throw an exception StateNotFound');
    }

    /**
     * Check if the factory register all available states of the stated class in the new proxy.
     */
    public function testListAvailableState()
    {
        $proxy = $this->getFactoryObject()->build();
        $this->assertEquals(
            array(
                'MockState1',
                Proxy\ProxyInterface::DEFAULT_STATE_NAME,
                'MockState2',
                'MockState3',
            ),
            $proxy->listAvailableStates()
        );
    }

    /**
     * Check if the factory, when it initialize a new proxy, enable the default state if there is no defined startup state.
     */
    public function testDefaultStateAutomaticallySelected()
    {
        $proxy = $this->getFactoryObject()->build();
        $this->assertEquals($proxy->listEnabledStates(), array('StateDefault'));
    }

    /**
     * Check if the factory, when it initialize a new proxy, enable the required state if there is defined startup state.
     */
    public function testRequiredStateSelected()
    {
        $proxy = $this->getFactoryObject()->build(null, 'MockState1');
        $this->assertEquals($proxy->listEnabledStates(), array('MockState1'));
    }

    /**
     * Check if the factory pass arguments to the.
     */
    public function testPassedArguments()
    {
        $args = array('foo' => 'bar');
        $proxy = $this->getFactoryObject()->build($args);
        $this->assertSame($args, $proxy->args);
    }
}