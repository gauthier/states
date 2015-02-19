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
 *
 * @version     1.0.2
 *
 * Mock factory file to test loader behavior when the factory class is valid but its method initialize throws an
 * exception and stated class loaded by loader with PSR-0 recommendations (file computed from namespace)
 * and not from namespace definitions)
 */

namespace Support\FileLoader\Class3;

use UniAlteri\Tests\Support;

/**
 * Class FactoryClass
 * Mock factory file to test loader behavior when the factory class is valid but its method initialize throws an
 * exception and stated class loaded by loader with PSR-0 recommendations (file computed from namespace)
 * and not from namespace definitions).
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class Factory extends Support\MockFactory
{
    /**
     * Throw an exception to test if the loader return false in loading class.
     *
     * @param string $statedClassName
     * @param string $path
     *
     * @return bool|void
     *
     * @throws \Exception
     */
    public function initialize($statedClassName, $path)
    {
        throw new \Exception('test');
    }
}
