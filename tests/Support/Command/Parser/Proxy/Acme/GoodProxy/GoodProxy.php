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
 * Mock factory file to test command for cli helper
 */

namespace Acme\GoodProxy;

use UniAlteri\States;
use UniAlteri\States\DI;
use UniAlteri\States\Proxy\Exception;
use UniAlteri\States\Proxy\ProxyInterface;

class GoodProxy implements ProxyInterface
{
    /**
     * Called to clone an Object.
     *
     * @return $this
     */
    public function __clone()
    {
    }

    /**
     * To call a method of the Object.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     *
     * @throws \Exception
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     * @throws Exception\IllegalArgument      if the method's name is not a string
     */
    public function __call(string $name, array $arguments)
    {
    }

    /**
     * To invoke an object as a function.
     *
     * @param mixed ...$args
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function __invoke(...$args)
    {
    }

    /**
     * To get a property of the object.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function __get(string $name)
    {
    }

    /**
     * To test if a property is set for the object.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function __isset(string $name)
    {
    }

    /**
     * To update a property of the object.
     *
     * @param string $name
     * @param string $value
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function __set(string $name, $value)
    {
    }

    /**
     * To remove a property of the object.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function __unset(string $name)
    {
    }

    /**
     * To transform the object to a string
     * You cannot throw an exception from within a __toString() method. Doing so will result in a fatal error.
     *
     * @return mixed
     */
    public function __toString(): string
    {
    }

    /**
     * To register a DI container for this object.
     *
     * @param DI\ContainerInterface $container
     *
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container): ProxyInterface
    {
    }

    /**
     * To return the DI Container used for this object.
     *
     * @return DI\ContainerInterface
     */
    public function getDIContainer(): DI\ContainerInterface
    {
    }

    /**
     * To register dynamically a new state for this object.
     *
     * @param string                       $stateName
     * @param States\States\StateInterface $stateObject
     *
     * @return $this
     *
     * @throws Exception\IllegalArgument when the identifier is not a string
     * @throws Exception\IllegalName     when the identifier does not respect the pattern [a-zA-Z_][a-zA-Z0-9_\-]*
     */
    public function registerState(string $stateName, States\States\StateInterface $stateObject): ProxyInterface
    {
    }

    /**
     * To remove dynamically a state from this object.
     *
     * @param string $stateName
     *
     * @return $this
     *
     * @throws Exception\IllegalArgument when the identifier is not a string
     * @throws Exception\StateNotFound   when the state was not found
     * @throws Exception\IllegalName     when the identifier does not respect the pattern [a-zA-Z_][a-zA-Z0-9_\-]*
     */
    public function unregisterState(string $stateName): ProxyInterface
    {
    }

    /**
     * To disable all actives states and enable the required states.
     *
     * @param string $stateName
     *
     * @return $this
     *
     * @throws Exception\IllegalArgument when the identifier is not a string
     * @throws Exception\IllegalName     when the identifier does not respect the pattern [a-zA-Z_][a-zA-Z0-9_\-]*
     */
    public function switchState(string $stateName): ProxyInterface
    {
    }

    /**
     * To enable a loaded states.
     *
     * @param $stateName
     *
     * @return $this
     *
     * @throws Exception\StateNotFound   if $stateName does not exist
     * @throws Exception\IllegalArgument when the identifier is not a string
     * @throws Exception\IllegalName     when the identifier does not respect the pattern [a-zA-Z_][a-zA-Z0-9_\-]*
     */
    public function enableState(string $stateName): ProxyInterface
    {
    }

    /**
     * To disable an active state (not available for calling, but always loaded).
     *
     * @param string $stateName
     *
     * @return $this
     *
     * @throws Exception\IllegalArgument when the identifier is not a string
     * @throws Exception\StateNotFound   when the state was not found
     * @throws Exception\IllegalName     when the identifier does not respect the pattern [a-zA-Z_][a-zA-Z0-9_\-]*
     */
    public function disableState(string $stateName): ProxyInterface
    {
    }

    /**
     * To disable all actives states.
     *
     * @return $this
     */
    public function disableAllStates(): ProxyInterface
    {
    }

    /**
     * To list all currently available states for this object.
     *
     * @return string[]
     */
    public function listAvailableStates()
    {
    }

    /**
     * To list all enable states for this object.
     *
     * @return string[]
     */
    public function listEnabledStates()
    {
    }

    /**
     * Check if the current entity is in the required state defined by $stateName.
     *
     * @param string $stateName
     *
     * @return bool
     *
     * @throws Exception\InvalidArgument when $stateName is not a valid string
     */
    public function inState(string $stateName): bool
    {
    }

    /**
     * To return the description of the method.
     *
     * @param string $methodName
     * @param string $stateName  : Return the description for a specific state of the object,
     *                           if null, use the current state
     *
     * @return \ReflectionMethod
     *
     * @throws Exception\StateNotFound        is the state required is not available
     * @throws Exception\InvalidArgument      where $methodName or $stateName are not string
     * @throws Exception\MethodNotImplemented when the method is not currently available
     * @throws \Exception                     to rethrows unknown exceptions
     */
    public function getMethodDescription(string $methodName, string $stateName = null): \ReflectionMethod
    {
    }

    /**
     * This method is executed when using the count() function on an object implementing Countable.
     *
     * @return int
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function count(): int
    {
    }

    /**
     * Whether or not an offset exists.
     * This method is executed when using isset() or empty() on states implementing ArrayAccess.
     *
     * @param string|int $offset
     *
     * @return bool
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function offsetExists($offset)
    {
    }

    /**
     * Returns the value at specified offset.
     * This method is executed when checking if offset is empty().
     *
     * @param string|int $offset
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function offsetGet($offset)
    {
    }

    /**
     * Assigns a value to the specified offset.
     *
     * @param string|int $offset
     * @param mixed      $value
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Unset an offset.
     *
     * @param string|int $offset
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * Returns the current element.
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function current()
    {
    }

    /**
     * Returns the key of the current element.
     *
     * @return mixed
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function key()
    {
    }

    /**
     * Moves the current position to the next element.
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function next()
    {
    }

    /**
     * Rewinds back to the first element of the Iterator.
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function rewind()
    {
    }

    /**
     * Seeks to a given position in the iterator.
     *
     * @param int $position
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function seek($position)
    {
    }

    /**
     * This method is called after Iterator::rewind() and Iterator::next() to check if the current position is valid.
     *
     * @return bool
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function valid()
    {
    }

    /**
     * Returns an external iterator.
     *
     * @return \Traversable
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function getIterator(): \Traversable
    {
    }

    /**
     * To serialize the object.
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     *
     * @return string
     */
    public function serialize(): string
    {
    }

    /**
     * To wake up the object.
     *
     * @param string $serialized
     *
     * @throws Exception\MethodNotImplemented if any enabled state implement the required method
     * @throws Exception\UnavailableState     if the required state is not available
     */
    public function unserialize($serialized)
    {
    }
}