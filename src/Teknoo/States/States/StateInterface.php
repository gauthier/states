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
 * @license     http://teknoo.software/states/license/mit         MIT License
 * @license     http://teknoo.software/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\States\States;

use Teknoo\States\DI;
use Teknoo\States\Proxy;

/**
 * Interface StateInterface
 * Interface to define a state for a stated class. Each state must implement this interface.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/states/license/mit         MIT License
 * @license     http://teknoo.software/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
interface StateInterface
{
    /**
     * Identifier into DI Container to generate a new Injection Closure Container.
     */
    const INJECTION_CLOSURE_SERVICE_IDENTIFIER = 'injectionClosureService';

    /**
     * Const to get a closure into a public scope.
     */
    const VISIBILITY_PUBLIC = 'public';

    /**
     * Const to get a closure into a protected scope.
     */
    const VISIBILITY_PROTECTED = 'protected';

    /**
     * Const to get a closure into a private scope.
     */
    const VISIBILITY_PRIVATE = 'private';

    /**
     * To register a DI container for this object.
     *
     * @param DI\ContainerInterface $container
     *
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container);

    /**
     * To return the DI Container used for this object.
     *
     * @return DI\ContainerInterface
     */
    public function getDIContainer();

    /**
     * To get the canonical stated class name associated to this state.
     *
     * @return $this
     */
    public function getStatedClassName();

    /**
     * To set the canonical stated class name associated to this state.
     *
     * @param string $statedClassName
     *
     * @return StateInterface
     */
    public function setStatedClassName($statedClassName);

    /**
     * To update the list of aliases of this state in the current stated class.
     *
     * @param string[] $aliases
     *
     * @return StateInterface
     */
    public function setStateAliases(array $aliases);

    /**
     * Return the list of aliases of this state in the current stated class.
     *
     * @return string[]
     */
    public function getStateAliases();

    /**
     * To know if the mode Private is enabled : private method are only accessible from
     * method present in the same stated class and not from methods of children of this class.
     * By default this mode is disable.
     *
     * @return bool
     */
    public function isPrivateMode();

    /**
     * To enable or disable the private mode of this state :
     * If the mode Private is enable, private method are only accessible from
     * method present in the same stated class and not from methods of children of this class.
     * By default this mode is disable.
     *
     * @param bool $enable
     *
     * @return StateInterface
     */
    public function setPrivateMode($enable);

    /**
     * To return an array of string listing all methods available in the state.
     *
     * @return string[]
     */
    public function listMethods();

    /**
     * To test if a method exists for this state in the current visibility scope.
     *
     * @param string      $methodName
     * @param string      $scope                 self::VISIBILITY_PUBLIC|self::VISIBILITY_PROTECTED|self::VISIBILITY_PRIVATE
     * @param string|null $statedClassOriginName
     *
     * @return bool
     *
     * @throws Exception\InvalidArgument when the method name is not a string
     */
    public function testMethod($methodName, $scope = self::VISIBILITY_PUBLIC, $statedClassOriginName = null);

    /**
     * To return the description of a method to configure the behavior of the proxy. Return also description of private
     * methods.
     *
     * @param string $methodName
     *
     * @return \ReflectionMethod
     *
     * @throws Exception\MethodNotImplemented is the method does not exist
     * @throws Exception\InvalidArgument      when the method name is not a string
     */
    public function getMethodDescription($methodName);

    /**
     * To return a closure of the required method to use in the proxy, according with the current visibility scope.
     *
     * @param string               $methodName
     * @param Proxy\ProxyInterface $proxy
     * @param string               $scope                 self::VISIBILITY_PUBLIC|self::VISIBILITY_PROTECTED|self::VISIBILITY_PRIVATE
     * @param string|null          $statedClassOriginName
     *
     * @return DI\InjectionClosureInterface
     *
     * @throws Exception\MethodNotImplemented is the method does not exist or not available in this scope
     * @throws Exception\InvalidArgument      when the method name is not a string
     * @throws Exception\IllegalProxy         when the proxy does not implement the good interface
     * @throws Exception\IllegalService       when there are no DI Container or Injection Closure Container bought
     */
    public function getClosure($methodName, $proxy, $scope = self::VISIBILITY_PUBLIC, $statedClassOriginName = null);
}