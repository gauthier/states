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
 * Trait ArrayAccessTrait
 * Trait to use the interface \ArrayAccess (http://php.net/manual/en/class.arrayaccess.php) with stated classes.
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
trait ArrayAccessTrait
{
    /****************
     * Array Access *
     ****************/

    /**
     * This method is executed when using the count() function on an object implementing Countable.
     *
     * @api
     *
     * @return int
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function count(): int
    {
        $args = [];

        return (int) $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Whether or not an offset exists.
     * This method is executed when using isset() or empty() on states implementing ArrayAccess.
     *
     * @api
     *
     * @param string|int $offset
     *
     * @return bool
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function offsetExists($offset)
    {
        $args = [$offset];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Returns the value at specified offset.
     * This method is executed when checking if offset is empty().
     *
     * @api
     *
     * @param string|int $offset
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function offsetGet($offset)
    {
        $args = [$offset];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Assigns a value to the specified offset.
     *
     * @api
     *
     * @param string|int $offset
     * @param mixed      $value
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function offsetSet($offset, $value)
    {
        $args = [$offset, $value];

        return $this->findMethodToCall(__FUNCTION__, $args);
    }

    /**
     * Unset an offset.
     *
     * @api
     *
     * @param string|int $offset
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     */
    public function offsetUnset($offset)
    {
        $args = [$offset];
        $this->findMethodToCall(__FUNCTION__, $args);
    }
}
