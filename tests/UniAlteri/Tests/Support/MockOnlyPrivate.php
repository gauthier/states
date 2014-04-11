<?php
/**
 * States
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @package     States
 * @subpackage  Tests
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     0.9.2
 */

namespace UniAlteri\Tests\Support;

use \UniAlteri\States\States;

/**
 * Class MockOnlyPrivate
 * Mock class to test the default trait State behavior with private methods.
 * All methods have not a description to check the state's behavior with these methods.
 *
 * @package     States
 * @subpackage  Tests
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     0.9.2
 */
class MockOnlyPrivate extends States\AbstractState
{
    /**
     * To simulate a real state behavior
     * @param boolean $initializeContainer initialize virtual di container for state
     */
    public function __construct($initializeContainer=true)
    {
        if (true === $initializeContainer) {
            //Mock DI Container
            $this->setDIContainer(new MockDIContainer());
            //Register the service to generate a mock injection closure object
            $this->getDIContainer()->registerService(
                States\StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER,
                function() {
                    return new MockInjectionClosure();
                }
            );
        }
    }

    /**
     * Final Method 9
     */
    final private function _finalMethod9()
    {
    }

    /**
     * Standard Method 10
     */
    private function _standardMethod10()
    {
    }

    final private function _finalMethod11()
    {
    }

    private static function _staticMethod12()
    {
    }
}