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
namespace Acme\Extendable\Mother\States;

use Teknoo\States\State\AbstractState;

/**
 * State StateTwo.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class StateTwo extends AbstractState
{
    /**
     * @return int
     */
    public function methodPublic()
    {
        return 123;
    }

    /**
     * @return int
     */
    protected function methodProtected()
    {
        return 456;
    }

    /**
     * @return int
     */
    private function methodPrivate()
    {
        return 789;
    }

    /**
     * @return int
     */
    public function methodRecallPrivate()
    {
        return $this->methodPrivate() * 2;
    }
}
