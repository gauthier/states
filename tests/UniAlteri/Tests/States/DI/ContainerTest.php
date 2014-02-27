<?php
/**
 * Created by JetBrains PhpStorm.
 * Author : Richard Déloge, richard@uni-alteri.fr, agence.net.ua
 * Date: 27/05/13
 * Time: 16:25
 */

namespace UniAlteri\Tests\States\DI;

use UniAlteri\States\DI;
use UniAlteri\Tests\Support;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Return a valid container for tests
     * @return DI\ContainerInterface
     */
    protected function _buildContainer()
    {
        return new DI\Container();
    }

    /**
     * @param DI\ContainerInterface $container
     */
    protected function _populateContainer($container)
    {
    }

    /**
     * The container must accepts only identifier as [a-zA-Z_][a-zA-Z0-9_/]*
     */
    public function testRegisterInstanceBadIdentifier()
    {
        try {
            $this->_buildContainer()->registerInstance('##', 'DateTime');
        } catch (DI\Exception\IllegalName $exception) {
            return;
        } catch(\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\IllegalName exception');
    }

    /**
     * The container must throws an exception if the class of the instance does not exist
     */
    public function testRegisterInstanceBadClass()
    {
        try {
            $this->_buildContainer()->registerInstance('class', 'NonExistentClass');
        } catch (DI\Exception\ClassNotFound $exception) {
            return;
        } catch (\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\ClassNotFound exception');
    }

    /**
     * Test return of registerInstance
     */
    public function testRegisterInstanceClass()
    {
        $container = $this->_buildContainer();
        $result = $container->registerInstance('dateObject', '\DateTime');
        $this->assertSame($container, $result, 'Error, the container must return $this after `registerInstance`');
    }

    /**
     * Non invokable object are allowed for instance, but not for service
     */
    public function testRegisterInstanceNonInvokableObject()
    {
        $container = $this->_buildContainer();
        $result = $container->registerInstance('dateObject', new \DateTime());
        $this->assertSame($container, $result, 'Error, the container must return $this after `registerInstance`');
    }

    /**
     * Test return of registerInstance
     */
    public function testRegisterInstanceInvokableObject()
    {
        $container = $this->_buildContainer();
        $result = $container->registerInstance('dateObject', new \DateTime());
        $this->assertSame($container, $result, 'Error, the container must return $this after `registerInstance`');
    }

    /**
     * Test return of registerInstance
     */
    public function testRegisterInstanceFunction()
    {
        $container = $this->_buildContainer();
        $result = $container->registerInstance('dateObject', function(){return new \DateTime();});
        $this->assertSame($container, $result, 'Error, the container must return $this after `registerInstance`');
    }

    /**
     * Test return of registerInstance
     */
    public function testRegisterInstanceArray()
    {
        try {
            $container = $this->_buildContainer();
            $container->registerInstance('dateObject', array());
        } catch (DI\Exception\IllegalService $exception) {
            return;
        } catch (\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\IllegalService exception if the instance is invalid');
    }

    /**
     * The container must accepts only identifier as [a-zA-Z_][a-zA-Z0-9_/]*
     */
    public function testRegisterServiceBadIdentifier()
    {
        try {
            $this->_buildContainer()->registerService('##', 'DateTime');
        } catch (DI\Exception\IllegalName $exception) {
            return;
        } catch (\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\IllegalName exception');
    }

    /**
     * The container must throws an exception if the class of the service does not exist
     */
    public function testRegisterServiceBadClass()
    {
        try {
            $this->_buildContainer()->registerService('class', 'NonExistentClass');
        } catch (DI\Exception\ClassNotFound $exception) {
            return;
        } catch (\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\ClassNotFound exception');
    }

    /**
     * Test return of registerService
     */
    public function testRegisterServiceClass()
    {
        $container = $this->_buildContainer();
        $result = $container->registerService('dateObject', '\DateTime');
        $this->assertSame($container, $result, 'Error, the container must return $this after `registerService`');
    }

    /**
     * Test return of registerService
     */
    public function testRegisterServiceArray()
    {
        try {
            $container = $this->_buildContainer();
            $container->registerService('dateObject', array());
        } catch (DI\Exception\IllegalService $exception) {
            return;
        } catch(\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\IllegalService exception if the service is invalid');
    }

    /**
     * Test return of registerService with non invokable object
     */
    public function testRegisterServiceNonInvokableObject()
    {
        try {
            $container = $this->_buildContainer();
            $container->registerService('dateObject', new \DateTime());
        } catch (DI\Exception\IllegalService $exception) {
            return;
        } catch (\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\IllegalService exception if the object is not invokable');
    }

    /**
     * Test return of registerService
     */
    public function testRegisterServiceInvokableObject()
    {
        $container = $this->_buildContainer();
        $result = $container->registerService('dateObject', new Support\InvokableClass());
        $this->assertSame($container, $result, 'Error, the container must return $this after `registerService`');
    }

    /**
     * Test return of registerService
     */
    public function testRegisterServiceFunction()
    {
        $container = $this->_buildContainer();
        $result = $container->registerService('dateObject', function(){return new \DateTime();});
        $this->assertSame($container, $result, 'Error, the container must return $this after `registerService`');
    }

    /**
     * The container must accepts only identifier as [a-zA-Z_][a-zA-Z0-9_/]*
     */
    public function testTestInstanceBadIdentifier()
    {
        try {
            $this->_buildContainer()->testEntry('##');
        } catch (DI\Exception\IllegalName $exception) {
            return;
        } catch (\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\IllegalName exception');
    }

    /**
     * test behavior of testInstance(), return true if an instance of service exist
     */
    public function testTestInstance()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);
        $this->assertTrue($container->testEntry('instanceClass'));
        $this->assertTrue($container->testEntry('instanceObject'));
        $this->assertTrue($container->testEntry('instanceFunction'));
        $this->assertTrue($container->testEntry('serviceClass'));
        $this->assertTrue($container->testEntry('serviceObject'));
        $this->assertTrue($container->testEntry('serviceFunction'));
        $this->assertFalse($container->testEntry('foo'));
    }

    /**
     * The container must accepts only identifier as [a-zA-Z_][a-zA-Z0-9_/]*
     */
    public function testGetBadIdentifier()
    {
        try {
            $this->_buildContainer()->get('##');
        } catch (DI\Exception\IllegalName $exception) {
            return;
        } catch(\Exception $e) {}

        $this->fail('Error, the container object must throws an Exception\IllegalName exception');
    }

    /**
     * Test to get an instance
     */
    public function testGetInstanceClass()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);

        $obj1 = $container->get('instanceClass');
        $obj2 = $container->get('instanceClass');
        $this->assertEquals(get_class($obj1), get_class($obj2), 'Error, container, must return the same object for a registered instance');
        $this->assertSame($obj1, $obj2, 'Error, container, must return the same object for a registered instance');
    }

    /**
     * Test to get an instance
     */
    public function testGetInstanceObject()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);

        $obj1 = $container->get('instanceObject');
        $obj2 = $container->get('instanceObject');
        $this->assertEquals(get_class($obj1), get_class($obj2), 'Error, container, must return the same object for a registered instance');
        $this->assertSame($obj1, $obj2, 'Error, container, must return the same object for a registered instance');
    }

    /**
     * Test to get an instance
     */
    public function testGetInstanceFunction()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);

        $obj1 = $container->get('instanceFunction');
        $obj2 = $container->get('instanceFunction');
        $this->assertEquals(get_class($obj1), get_class($obj2), 'Error, container, must return the same object for a registered instance');
        $this->assertSame($obj1, $obj2, 'Error, container, must return the same object for a registered instance');
        $this->assertInstanceOf('Closure', $obj1);
    }

    /**
     * Test to get a service behavior
     */
    public function testGetServiceClass()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);

        $obj1 = $container->get('serviceClass');
        $obj2 = $container->get('serviceClass');
        $this->assertEquals(get_class($obj1), get_class($obj2), 'Error, container, must return the same object for a registered instance');
        $this->assertNotSame($obj1, $obj2, 'Error, container, must return two different objects for a same service');
    }

    /**
     * Test to get a service behavior for invokable
     */
    public function testGetServiceObject()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);

        $obj1 = $container->get('instanceObject');
        $this->assertEquals('\stdClass', $obj1, 'Error, for a service, the invokable object must be called and not returned');
    }

    /**
     * Test to get a service behavior for anonymous function
     */
    public function testGetServiceFunction()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);

        $obj1 = $container->get('serviceFunction');
        $this->assertEquals('\DateTime', $obj1, 'Error, for a service, the invokable object must be called and not returned');
    }

    /**
     * Test if the params passed to configure is not an array, the container throw an exception
     */
    public function testConfigureBadArray()
    {
        $container = $this->_buildContainer();
        try {
            $container->configure(new \DateTime());
        } catch (\Exception $e) {
            return;
        }

        $this->fail('Error, method configure of the container must accept only array an ArrayObject');
    }

    /**
     * Test configuration of the container with an array
     */
    public function testConfigure()
    {
        $container = $this->_buildContainer();
        $container->configure(
            array(
                'instances' => array(
                    'instanceClass'  => '\DateTime',
                    'date1'         => function(){ return new \DateTime(); }
                ),
                'services'  => array(
                    'stdClass'         => function(){ return new \stdClass(); }
                )
            )
        );

        $this->assertInstanceOf('\DateTime', $container->get('instanceClass'));
        $this->assertInstanceOf('\Closure', $container->get('date1'));
        $this->assertInstanceOf('\stdClass', $container->get('stdClass'));
    }

    /**
     * Test if the id to unregister is not valid, the container throw an exception
     */
    public function testUnregisterBadId()
    {
        $container = $this->_buildContainer();
        try {
            $container->unregister('##');
        } catch (DI\Exception\IllegalName $exception) {
            return;
        } catch (\Exception $e) {}

        $this->fail('Error, the identifier must be a valid php var name http://www.php.net/manual/en/language.variables.basics.php');
    }

    /**
     * Test unregister an instance from the DI
     */
    public function testUnregister()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);
        $this->assertTrue($container->testEntry('instanceObject'));

        $container->unregister('instanceObject');
        $this->assertFalse($container->testEntry('instanceObject'));
    }

    /**
     * Test if the container return all instances with the ids
     */
    public function testListDefinitions()
    {
        $container = $this->_buildContainer();
        $this->_populateContainer($container);

        $list = $container->listDefinitions();
        $this->assertEquals(
            array_values($list),
            array('instanceClass', 'instanceObject', 'instanceFunction', 'serviceClass', 'serviceObject', 'serviceFunction'),
            'Error, the container method "listContainer" must return all instance name with there unique id'
        );
    }
}