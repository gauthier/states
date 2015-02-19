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
 */
if (!function_exists('testGetMethodDescriptionFromFunctionPrivate')) {
    /**
     * Build temp functions to test proxy behavior with different scope visibility
     * from a function to get a description of a private method.
     *
     * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
     *
     * @link        http://teknoo.it/states Project website
     *
     * @license     http://teknoo.it/states/license/mit         MIT License
     * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
     * @author      Richard Déloge <r.deloge@uni-alteri.com>
     */
    function testGetMethodDescriptionFromFunctionPrivate()
    {
        global $proxy;
        $proxy->getMethodDescription('privateTest');
    }
}

if (!function_exists('testGetMethodDescriptionFromFunctionProtected')) {
    /**
     * Build temp functions to test proxy behavior with different scope visibility
     * from a function to get a description of a protected method.
     *
     * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
     *
     * @link        http://teknoo.it/states Project website
     *
     * @license     http://teknoo.it/states/license/mit         MIT License
     * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
     * @author      Richard Déloge <r.deloge@uni-alteri.com>
     */
    function testGetMethodDescriptionFromFunctionProtected()
    {
        global $proxy;
        $proxy->getMethodDescription('protectedTest');
    }
}

if (!function_exists('testGetMethodDescriptionFromFunctionPublic')) {
    /**
     * Build temp functions to test proxy behavior with different scope visibility
     * from a function to get a description of a public method.
     *
     * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
     *
     * @link        http://teknoo.it/states Project website
     *
     * @license     http://teknoo.it/states/license/mit         MIT License
     * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
     * @author      Richard Déloge <r.deloge@uni-alteri.com>
     *
     * @return \ReflectionMethod *
     */
    function testGetMethodDescriptionFromFunctionPublic()
    {
        global $proxy;

        return $proxy->getMethodDescription('publicTest');
    }
}

if (!trait_exists('testGetMethodDescriptionTrait')) {
    /**
     * Build temp functions to test proxy behavior with different scope visibility
     * from object of a the same class of a inherited class.
     *
     * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
     *
     * @link        http://teknoo.it/states Project website
     *
     * @license     http://teknoo.it/states/license/mit         MIT License
     * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
     * @author      Richard Déloge <r.deloge@uni-alteri.com>
     */
    trait testGetMethodDescriptionTrait
    {
        public function privateMethod()
        {
            global $proxy;

            return $proxy->getMethodDescription('privateTest');
        }

        public function protectedMethod()
        {
            global $proxy;

            return $proxy->getMethodDescription('protectedTest');
        }

        public function publicMethod()
        {
            global $proxy;

            return $proxy->getMethodDescription('publicTest');
        }

        public static function privateMethodStatic()
        {
            global $proxy;

            return $proxy->getMethodDescription('privateTest');
        }

        public static function protectedMethodStatic()
        {
            global $proxy;

            return $proxy->getMethodDescription('protectedTest');
        }

        public static function publicMethodStatic()
        {
            global $proxy;

            return $proxy->getMethodDescription('publicTest');
        }
    }
}

if (!class_exists('testGetMethodDescriptionFromOtherObject')) {
    /**
     * Build temp functions to test proxy behavior with different scope visibility
     * from a external object to get a description of methods.
     *
     * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
     *
     * @link        http://teknoo.it/states Project website
     *
     * @license     http://teknoo.it/states/license/mit         MIT License
     * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
     * @author      Richard Déloge <r.deloge@uni-alteri.com>
     */
    class testGetMethodDescriptionFromOtherObject
    {
        use testGetMethodDescriptionTrait;
    }
}
