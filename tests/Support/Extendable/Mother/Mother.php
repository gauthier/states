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
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Support\Extendable\Mother;

use Teknoo\States\Proxy\Integrated;

/**
 * Proxy Mother
 * Proxy class of the stated class Mother
 * Copy from Demo for functional tests.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Mother extends Integrated
{
    /**
     * Return the list of available state in this class.
     *
     * @return array
     */
    public function listMethodsByStates()
    {
        $methodsList = array();
        foreach ($this->getStatesList() as $stateName => $stateContainer) {
            $methodsList[$stateName] = $stateContainer->listMethods()->getArrayCopy();
        }

        ksort($methodsList);

        return $methodsList;
    }
}