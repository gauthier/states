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
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
namespace Teknoo\Tests\States\Command\Writer;

use Gaufrette\Filesystem;
use Teknoo\States\Command\Writer\Factory;
use Teknoo\States\Loader\LoaderInterface;

/**
 * Class FactoryTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Filesystem
     */
    protected function buildFileSystemMock()
    {
        if (!$this->fileSystem instanceof \PHPUnit_Framework_MockObject_MockObject) {
            $this->fileSystem = $this->createMock(
                '\Gaufrette\Filesystem');
        }

        return $this->fileSystem;
    }

    /**
     * @return Factory
     */
    public function createWriter()
    {
        return new Factory(
            $this->buildFileSystemMock(),
            'foo/bar'
        );
    }

    public function testCreateStandardFactoryFailure()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals(LoaderInterface::FACTORY_FILE_NAME, $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Factory\\Standard;'));
                    $this->assertNotFalse(strpos($code, 'class '.LoaderInterface::FACTORY_CLASS_NAME.' extends Standard'));

                    return 0;
                }
            );

        $this->assertFalse($this->createWriter()->createStandardFactory('fooBar', 'Acme\\NameProduct'));
    }

    public function testCreateIntegratedFactoryFailure()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals(LoaderInterface::FACTORY_FILE_NAME, $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Factory\\Standard;'));
                    $this->assertNotFalse(strpos($code, 'class '.LoaderInterface::FACTORY_CLASS_NAME.' extends Standard'));

                    return 10;
                }
            );

        $this->assertTrue($this->createWriter()->createStandardFactory('fooBar', 'Acme\\NameProduct'));
    }

    public function testCreateStandardFactory()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals(LoaderInterface::FACTORY_FILE_NAME, $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Factory\\Integrated;'));
                    $this->assertNotFalse(strpos($code, 'class '.LoaderInterface::FACTORY_CLASS_NAME.' extends Integrated'));

                    return 0;
                }
            );

        $this->assertFalse($this->createWriter()->createIntegratedFactory('fooBar', 'Acme\\NameProduct'));
    }

    public function testCreateIntegratedFactory()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals(LoaderInterface::FACTORY_FILE_NAME, $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Factory\\Integrated;'));
                    $this->assertNotFalse(strpos($code, 'class '.LoaderInterface::FACTORY_CLASS_NAME.' extends Integrated'));

                    return 10;
                }
            );

        $this->assertTrue($this->createWriter()->createIntegratedFactory('fooBar', 'Acme\\NameProduct'));
    }
}
