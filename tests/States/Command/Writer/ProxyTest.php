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
use Teknoo\States\Command\Writer\Proxy;

/**
 * Class ProxyTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class ProxyTest extends \PHPUnit_Framework_TestCase
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
     * @return Proxy
     */
    public function createWriter()
    {
        return new Proxy(
            $this->buildFileSystemMock(),
            'foo/bar'
        );
    }

    public function testCreateStandardProxyFailure()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals('fooBar.php', $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Proxy;'));
                    $this->assertNotFalse(strpos($code, 'class fooBar extends Proxy\\Standard'));

                    return 0;
                }
            );

        $this->assertFalse($this->createWriter()->createStandardProxy('fooBar', 'Acme\\NameProduct'));
    }

    public function testCreateIntegratedProxyFailure()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals('fooBar.php', $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Proxy;'));
                    $this->assertNotFalse(strpos($code, 'class fooBar extends Proxy\\Standard'));

                    return 10;
                }
            );

        $this->assertTrue($this->createWriter()->createStandardProxy('fooBar', 'Acme\\NameProduct'));
    }

    public function testCreateStandardProxy()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals('fooBar.php', $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Proxy;'));
                    $this->assertNotFalse(strpos($code, 'class fooBar extends Proxy\\Integrated'));

                    return 0;
                }
            );

        $this->assertFalse($this->createWriter()->createIntegratedProxy('fooBar', 'Acme\\NameProduct'));
    }

    public function testCreateIntegratedProxy()
    {
        $this->buildFileSystemMock()
            ->expects($this->once())
            ->method('write')
            ->withAnyParameters()
            ->willReturnCallback(
                function ($file, $code) {
                    $this->assertEquals('fooBar.php', $file);
                    $this->assertNotFalse(strpos($code, 'namespace Acme\\NameProduct\\fooBar;'));
                    $this->assertNotFalse(strpos($code, 'use Teknoo\\States\\Proxy;'));
                    $this->assertNotFalse(strpos($code, 'class fooBar extends Proxy\\Integrated'));

                    return 10;
                }
            );

        $this->assertTrue($this->createWriter()->createIntegratedProxy('fooBar', 'Acme\\NameProduct'));
    }
}
