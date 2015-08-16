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
 */

namespace UniAlteri\Tests\Support;

use UniAlteri\States\Proxy\Exception;
use UniAlteri\States\Proxy\ProxyInterface;
use UniAlteri\States\State\StateInterface;

/**
 * Class MockProxy
 * Mock proxy to tests factories behavior and trait state behavior.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class MockProxy implements ProxyInterface
{
    /**
     * To test args passed by factory.
     *
     * @var null|array
     */
    public $args = null;

    /**
     * Local registry of loaded states, to simulate a real proxy.
     *
     * @var array
     */
    protected $states = array();

    /**
     * Local registry of active states, to simulate a real proxy.
     *
     * @var array
     */
    protected $actives = array();

    /**
     * @param mixed $arguments
     */
    public function __construct($arguments)
    {
        $this->args = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
        //Not used in tests
    }

    /***********************
     *** States Management *
     ***********************/

    /**
     * {@inheritdoc}
     */
    public function registerState(\string $stateName, StateInterface $stateObject): ProxyInterface
    {
        //Simulate real behavior
        $this->states[$stateName] = $stateObject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unregisterState(\string $stateName): ProxyInterface
    {
        //Simulate real behavior
        if (isset($this->states[$stateName])) {
            unset($this->states[$stateName]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function switchState(\string $stateName): ProxyInterface
    {
        //Simulate real behavior
        $this->actives = array($stateName => $stateName);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function enableState(\string $stateName): ProxyInterface
    {
        //Simulate real behavior
        $this->actives[$stateName] = $stateName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disableState(\string $stateName): ProxyInterface
    {
        //Simulate real behavior
        if (isset($this->actives[$stateName])) {
            unset($this->actives[$stateName]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disableAllStates(): ProxyInterface
    {
        //Simulate real behavior
        $this->actives = array();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listAvailableStates()
    {
        //Simulate real behavior
        return array_keys($this->states);
    }

    /**
     * {@inheritdoc}
     */
    public function listEnabledStates()
    {
        //Simulate real behavior
        return array_keys($this->actives);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatesList()
    {
        return $this->states;
    }

    /**
     * {@inheritdoc}
     */
    public function inState(\string $stateName): bool
    {
        return in_array(strtolower(str_replace('_', '', $stateName)), $this->actives);
    }

    /*******************
     * Methods Calling *
     *******************/

    /**
     * {@inheritdoc}
     */
    public function __call(\string $name, array $arguments)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodDescription(\string $methodName, \string $stateName = null): \ReflectionMethod
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(...$args)
    {
        //Not used in tests
    }

    /*******************
     * Data Management *
     *******************/

    /**
     * {@inheritdoc}
     */
    public function __get(\string $name)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function __isset(\string $name)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function __set(\string $name, $value)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function __unset(\string $name)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): \string
    {
        //Not used in tests
    }

    /****************
     * Array Access *
     ****************/

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        //Not used in tests
    }

    /************
     * Iterator *
     ************/

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function seek($position)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        //Not used in tests
    }

    /*****************
     * Serialization *
     *****************/

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        //Not used in tests
    }

    /**
     * {@inheritdoc}
     */
    public function getState($stateName)
    {
        return $this->states[$stateName];
    }
}
