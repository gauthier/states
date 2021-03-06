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
namespace Teknoo\Tests\States\Command;

/**
 * Class ConsoleTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class ConsoleTest extends \PHPUnit_Framework_TestCase
{
    public function testConsole()
    {
        $application = include __DIR__.'/../../../src/Command/console.php';
        $this->assertInstanceOf('Symfony\Component\Console\Application', $application);

        $this->assertEquals(
            [
                'help',
                'list',
                'class:create',
                'class:info',
                'state:add',
                'state:list',
            ],
            array_keys($application->all())
        );
    }

    public function testFileSystemFactory()
    {
        $application = include __DIR__.'/../../../src/Command/console.php';
        $command = $application->get('class:create');
        $fileSystemFactory = $command->getFileSystemFactory();

        $this->assertEquals('Closure', get_class($fileSystemFactory));
        $this->assertInstanceOf('Gaufrette\Filesystem', $fileSystemFactory('path'));
    }

    public function testFactory()
    {
        $application = include __DIR__.'/../../../src/Command/console.php';
        $command = $application->get('class:create');
        $factory = $command->getFactory();

        $this->assertEquals('Closure', get_class($factory));
        $this->assertInstanceOf('Teknoo\States\Command\Parser\Factory', $factory('Parser\Factory', 'path'));
        $this->assertInstanceOf('Teknoo\States\Command\Parser\Proxy', $factory('Parser\Proxy', 'path'));
        $this->assertInstanceOf('Teknoo\States\Command\Parser\State', $factory('Parser\State', 'path'));
        $this->assertInstanceOf('Teknoo\States\Command\Parser\StatedClass', $factory('Parser\StatedClass', 'path'));
        $this->assertInstanceOf('Teknoo\States\Command\Writer\Factory', $factory('Writer\Factory', 'path'));
        $this->assertInstanceOf('Teknoo\States\Command\Writer\Proxy', $factory('Writer\Proxy', 'path'));
        $this->assertInstanceOf('Teknoo\States\Command\Writer\State', $factory('Writer\State', 'path'));
        try {
            $factory('BadService', 'path');
        } catch (\Exception $e) {
            return;
        }

        $this->fail('Error the parser and writer factory must throw an exception when the service is not valid');
    }
}
