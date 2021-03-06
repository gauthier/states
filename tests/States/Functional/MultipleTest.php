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
namespace Teknoo\Tests\States\Functional;

use Teknoo\States\Loader;

/**
 * Class MultipleTest
 * Functional test number 1, from demo article.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class MultipleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Loader of the states library.
     *
     * @var \Teknoo\States\Loader\LoaderInterface
     */
    protected $loader = null;

    /**
     * Load the library State and retrieve its default loader from its bootstrap.
     *
     * @return \Teknoo\States\Loader\LoaderInterface
     */
    protected function getLoader()
    {
        if (null === $this->loader) {
            $this->loader = include __DIR__.'/../../../src/bootstrap.php';
        }

        return $this->loader;
    }

    /**
     * Create the PHAR multiple.phar for the test if this file does not exist.
     */
    protected function setUp()
    {
        $multiplePharPath = TK_STATES_TEST_PATH.DIRECTORY_SEPARATOR.'Support'
                                               .DIRECTORY_SEPARATOR.'multiple.phar';

        if (!file_exists($multiplePharPath)) {
            //Compute Path for this Phar
            $multiplePath = TK_STATES_TEST_PATH.DIRECTORY_SEPARATOR.'Support'
                                               .DIRECTORY_SEPARATOR.'src'
                                               .DIRECTORY_SEPARATOR.'Multiple';

            //Build phat
            $phar = new \Phar($multiplePharPath, 0, 'multiple.phar');
            $phar->buildFromDirectory($multiplePath);
        }

        parent::setUp();
    }

    public function testMultiple()
    {
        defined('DS')
            || define('DS', DIRECTORY_SEPARATOR);

        //Loading lib States
        $loader = $this->getLoader();

        //Register demo namespace
        $loader->registerNamespace('\\Teknoo\\Tests\\Support', TK_STATES_TEST_PATH.DS.'Support');
        $loader->registerNamespace('\\Teknoo\\Tests\\Support\\Multiple', 'phar://'.TK_STATES_TEST_PATH.DS.'Support'.DS.'multiple.phar');

        //Initialize user
        $simpleUser = new \Teknoo\Tests\Support\Multiple\User('simple 1');
        $this->assertEquals('simple 1', $simpleUser->getName());
        //Initialize moderator
        $moderator = new \Teknoo\Tests\Support\Multiple\User('modo', false, true);
        $this->assertEquals('modo', $moderator->getName());
        //Initialize admin
        $administrator = new \Teknoo\Tests\Support\Multiple\User('admin', true, true);
        $this->assertEquals('admin', $administrator->getName());

        //Method not available, because state Moderator is not enabled
        $fail = false;
        try {
            $simpleUser->isModerator();
        } catch (\Exception $e) {
            $fail = true;
        }

        if (!$fail) {
            $this->fail('Error, the lib must throw an exception because the method is not available in enabled states');
        }

        $this->assertTrue($moderator->isModerator());
        $this->assertTrue($administrator->isModerator());

        //admin transforms the user as modo
        $administrator->setModerator($simpleUser);
        $this->assertTrue($simpleUser->isModerator());

        //Initialize another stated class of this phar
        $newPost = new \Teknoo\Tests\Support\Multiple\Post();
        $this->assertInstanceOf('\Teknoo\Tests\Support\Multiple\Post', $newPost);
    }
}
