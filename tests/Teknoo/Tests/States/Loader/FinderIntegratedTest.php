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

namespace Teknoo\Tests\States\Loader;

use Teknoo\States\Loader;
use Teknoo\States\Factory;
use Teknoo\States\States;
use Teknoo\Tests\Support;

/**
 * Class FinderIntegratedTest
 * Tests the excepted behavior of integrated finder implementing the interface \Teknoo\States\Loader\FinderInterface.
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
class FinderIntegratedTest extends FinderStandardTest
{
    /**
     * Initialize the integrated finder for test with mock objects.
     *
     * @param string $statedClassName
     * @param string $pathString
     *
     * @return Loader\FinderStandard
     */
    protected function initializeFinder($statedClassName, $pathString)
    {
        $virtualDIContainer = new Support\MockDIContainer();
        $this->finder = new Loader\FinderIntegrated($statedClassName, $pathString);
        $this->finder->setDIContainer($virtualDIContainer);

        return $this->finder;
    }

    /**
     * Test behavior for methods Set And GetDiContainer.
     */
    public function testSetAndGetDiContainer()
    {
        $object = new Loader\FinderIntegrated('', '');
        $this->assertNull($object->getDIContainer());
        $virtualContainer = new Support\MockDIContainer();
        $this->assertSame($object, $object->setDIContainer($virtualContainer));
        $this->assertSame($virtualContainer, $object->getDIContainer());
    }

    /**
     * Initialize the startup factory to run this tests with the integrated finder (use the integrated proxy).
     */
    public function testBuildProxyDefault()
    {
        Factory\StandardStartupFactory::registerFactory('Teknoo\States\Proxy\Integrated', new Support\MockFactory());
        parent::testBuildProxyDefault();
    }
}
