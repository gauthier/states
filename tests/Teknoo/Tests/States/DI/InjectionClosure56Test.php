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

namespace Teknoo\Tests\States\DI;

use Teknoo\States\DI;
use Teknoo\States\DI\Exception;
use Teknoo\Tests\Support;

/**
 * Class InjectionClosureTest
 * Check if the Injection Closure class has the excepted behavior defined by the DI\InjectionClosureInterface.
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
class InjectionClosure56Test extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if ('5.6' > PHP_VERSION) {
            $this->markTestSkipped('Version of PHP is not supported for this injection closure');
        }
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Return a valid InjectionClosureInterface object.
     *
     * @param callable $closure
     *
     * @return DI\InjectionClosure
     */
    protected function buildClosure(\Closure $closure = null)
    {
        if (null === $closure) {
            $closure = function () {
                return array_reverse(func_get_args());
            };
        }

        $injectionClosureObject = new DI\InjectionClosurePHP56();
        $injectionClosureObject->setClosure($closure);

        return $injectionClosureObject;
    }

    /**
     * Test behavior for methods Set And GetDiContainer.
     */
    public function testSetAndGetDiContainer()
    {
        $object = new DI\InjectionClosurePHP56();
        $this->assertNull($object->getDIContainer());
        $virtualContainer = new Support\MockDIContainer();
        $this->assertSame($object, $object->setDIContainer($virtualContainer));
        $this->assertSame($virtualContainer, $object->getDIContainer());
    }

    /**
     * The Injection Closure object must not accept object who not implement \Closure.
     *
     * @return bool
     */
    public function testBadClosureConstruct()
    {
        try {
            $a = new DI\InjectionClosurePHP56();
            $a->setClosure(new \stdClass());
        } catch (Exception\InvalidArgument $e) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the Injection closure object must throw an exception if the object is not a closure');
    }

    /**
     * Test Injection closure creation.
     */
    public function testCreateClosure()
    {
        $closure = $this->buildClosure();
        $this->assertInstanceOf('\Teknoo\States\DI\InjectionClosureInterface', $closure);
    }

    /**
     * Test invoking from injection with the closure, execute the closure (the closure test returns arguments order.
     */
    public function testInvokeWithArgsInvokeMagic()
    {
        $closure = $this->buildClosure();
        $return = $closure('foo', 'boo', 'hello', 'world');
        $this->assertSame(
            array(
                'world',
                'hello',
                'boo',
                'foo',
            ),
            $return,
            'Error, the closure is not called by the injector '
        );
    }

    /**
     * Test invoking from injection with the closure, execute the closure (the closure test returns arguments order.
     */
    public function testInvokeWithArgs()
    {
        $closure = $this->buildClosure();
        $args = ['foo', 'boo', 'hello', 'world'];
        $return = $closure->invoke($args);
        $this->assertEquals(
            array(
                'world',
                'hello',
                'boo',
                'foo',
            ),
            $return,
            'Error, the closure is not called by the injector '
        );
    }

    /**
     * Test invoke behavior with different number of arguments.
     */
    public function testInvokeCallSeveralArgs()
    {
        $closure = $this->buildClosure();
        $args = [];
        $result = $closure->invoke($args);
        $this->assertEquals(array(), $result);

        $args = [1];
        $result = $closure->invoke($args);
        $this->assertEquals(array(1), $result);

        $args = [1, 2];
        $result = $closure->invoke($args);
        $this->assertEquals(array(2, 1), $result);

        $args = [1, 2, 3];
        $result = $closure->invoke($args);
        $this->assertEquals(array(3, 2, 1), $result);

        $args = [1, 2, 3, 4];
        $result = $closure->invoke($args);
        $this->assertEquals(array(4, 3, 2, 1), $result);

        $args = [1, 2, 3, 4, 5];
        $result = $closure->invoke($args);
        $this->assertEquals(array(5, 4, 3, 2, 1), $result);

        $args = [1, 2, 3, 4, 5, 6];
        $result = $closure->invoke($args);
        $this->assertEquals(array(6, 5, 4, 3, 2, 1), $result);

        $args = [1, 2, 3, 4, 5, 6, 7];
        $result = $closure->invoke($args);
        $this->assertEquals(array(7, 6, 5, 4, 3, 2, 1), $result);
    }

    /**
     * Test if the injector car return the original closure.
     */
    public function testGetClosure()
    {
        $myClosure = function ($i) {
            return $i + 1;
        };

        $injectionClosure = new DI\InjectionClosurePHP56($myClosure);
        $this->assertSame($myClosure, $injectionClosure->getClosure());
    }

    /**
     * Test if the injector car return the linked proxy.
     */
    public function testSetProxy()
    {
        $myClosure = function ($i) {
            return $i + 1;
        };

        $proxy = $this->getMock('Teknoo\States\Proxy\ProxyInterface');

        $injectionClosure = new DI\InjectionClosurePHP56($myClosure);
        $this->assertSame($injectionClosure, $injectionClosure->setProxy($proxy));
        $this->assertSame($myClosure, $injectionClosure->getClosure());
        $this->assertSame($proxy, $injectionClosure->getProxy());
    }

    /**
     * Storage must throw an exception if the attribute name is not valid.
     */
    public function testSaveBadStaticProperty()
    {
        try {
            $this->buildClosure()->saveProperty('##', 'foo');
        } catch (Exception\IllegalName $exception) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the storage adapter must throw an exception if the attribute name is not valid : http://www.php.net/manual/en/language.variables.basics.php');
    }

    /**
     * Test behavior of injector with static properties.
     */
    public function testGetSaveStaticProperty()
    {
        $closure = $this->buildClosure();
        $closure->saveProperty('static1', 'foo');
        $closure->saveProperty('static2', new \stdClass());

        $this->assertEquals('foo', $closure->getProperty('static1'));
        $obj = $closure->getProperty('static2');
        $this->assertInstanceOf('stdClass', $obj);
        $obj->attr1 = 'boo';
        $this->assertEquals('boo', $closure->getProperty('static2')->attr1);
    }

    /**
     * Storage must throw an exception if the attribute name is not valid.
     */
    public function testGetBadStaticProperty()
    {
        try {
            $this->buildClosure()->saveProperty('##', 'foo');
        } catch (Exception\IllegalName $exception) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the storage adapter must throw an exception if the attribute name is not valid : http://www.php.net/manual/en/language.variables.basics.php');
    }

    /**
     * Test if statics properties are persistent on all call of the closure.
     */
    public function testPersistenceOfStaticPropertyInvokeMagik()
    {
        $closure = $this->buildClosure();
        $closure->saveProperty('static1', 'foo');
        $result = $closure(1, 2, 3);
        $this->assertEquals(array(3, 2, 1), $result);
        $this->assertEquals('foo', $closure->getProperty('static1'));

        $result = $closure(4, 5, 6);
        $this->assertEquals(array(6, 5, 4), $result);
        $this->assertEquals('foo', $closure->getProperty('static1'));

        $closure->saveProperty('static1', 'boo');
        $result = $closure(7, 8, 9);
        $this->assertEquals(array(9, 8, 7), $result);
        $this->assertEquals('boo', $closure->getProperty('static1'));
    }

    /**
     * Test if statics properties are persistent on all call of the closure.
     */
    public function testPersistenceOfStaticProperty()
    {
        $closure = $this->buildClosure();
        $closure->saveProperty('static1', 'foo');
        $args = [1, 2, 3];
        $result = $closure->invoke($args);
        $this->assertEquals(array(3, 2, 1), $result);
        $this->assertEquals('foo', $closure->getProperty('static1'));

        $args = [4, 5, 6];
        $result = $closure->invoke($args);
        $this->assertEquals(array(6, 5, 4), $result);
        $this->assertEquals('foo', $closure->getProperty('static1'));

        $closure->saveProperty('static1', 'boo');
        $args = [7, 8, 9];
        $result = $closure->invoke($args);
        $this->assertEquals(array(9, 8, 7), $result);
        $this->assertEquals('boo', $closure->getProperty('static1'));
    }

    /**
     * Storage must throw an exception if the attribute name is not valid.
     */
    public function testDeleteBadStaticProperty()
    {
        try {
            $this->buildClosure()->deleteProperty('##');
        } catch (Exception\IllegalName $exception) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the storage adapter must throw an exception if the attribute name is not valid : http://www.php.net/manual/en/language.variables.basics.php');
    }

    /**
     * Test deletion.
     */
    public function testDeleteStaticProperty()
    {
        $closure = $this->buildClosure();
        $closure->saveProperty('static1', 'foo');
        $this->assertEquals('foo', $closure->getProperty('static1'));
        $closure->deleteProperty('static1');
        $this->assertNull($closure->getProperty('static1'));
    }

    /**
     * Storage must throw an exception if the attribute name is not valid.
     */
    public function testTestBadStaticProperty()
    {
        try {
            $this->buildClosure()->testProperty('##');
        } catch (Exception\IllegalName $exception) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the storage adapter must throw an exception if the attribute name is not valid : http://www.php.net/manual/en/language.variables.basics.php');
    }

    /**
     * Test "test" to check if a static property exists.
     */
    public function testTestStaticProperty()
    {
        $closure = $this->buildClosure();
        $this->assertFalse($closure->testProperty('static1'));
        $closure->saveProperty('static1', 'foo');
        $this->assertTrue($closure->testProperty('static1'));
        $closure->deleteProperty('static1');
        $this->assertFalse($closure->testProperty('static1'));
    }
}
