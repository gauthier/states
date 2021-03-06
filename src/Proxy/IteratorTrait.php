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
namespace Teknoo\States\Proxy;

/**
 * Trait IteratorTrait
 * Trait to use the interface \Iterator (http://php.net/manual/en/class.iterator.php) with stated classes.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @method mixed findMethodToCall($name, $args)
 */
trait IteratorTrait
{
    /************
     * Iterator *
     ************/

    /**
     * Returns the current element.
     *
     * @api
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function current()
    {
        $args = [];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Returns the key of the current element.
     *
     * @api
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function key()
    {
        $args = [];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Moves the current position to the next element.
     *
     * @api
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function next()
    {
        $args = [];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Rewinds back to the first element of the Iterator.
     *
     * @api
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function rewind()
    {
        $args = [];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Seeks to a given position in the iterator.
     *
     * @api
     *
     * @param int $position
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function seek($position)
    {
        $args = [$position];
        $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * This method is called after Iterator::rewind() and Iterator::next() to check if the current position is valid.
     *
     * @api
     *
     * @return bool
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function valid()
    {
        $args = [];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Returns an external iterator.
     *
     * @api
     *
     * @return \Traversable
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function getIterator(): \Traversable
    {
        $args = [];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }
}
