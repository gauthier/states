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
 * @version     1.0.3
 */

namespace UniAlteri\States\DI;

/**
 * Interface InjectionClosureInterface
 * Interface to define Injection Closure container to use in this library.
 * An Injection Closure container is used to extract and manipulate all methods of a stated class
 * in the proxy. These containers implement also a "static" mechanism to allow developers to use
 * clean static var in these functions.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @api
 */
interface InjectionClosureInterface
{
    /**
     * To To register a DI container for this object.
     *
     * @param ContainerInterface $container
     */
    public function setDIContainer(ContainerInterface $container);

    /**
     * To return the DI Container used for this object.
     *
     * @return ContainerInterface
     * @return $this
     */
    public function getDIContainer();

    /**
     * Execute the closure.
     *
     * @return mixed
     */
    public function __invoke();

    /**
     * To define the closure contained into $this.
     *
     * @param \Closure $closure
     *
     * @return $this
     *
     * @throws Exception\InvalidArgument when $closure is not an instance of \Closure
     */
    public function setClosure($closure);

    /**
     * To return the closure contained into $this.
     *
     * @return \Closure
     */
    public function getClosure();

    /**
     * To allow the closure to save a static property,
     * to allow developer to not use "static" key word into the closure.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     * @throw Exception\IllegalName if the name does not respect the pattern [a-zA-Z_][a-zA-Z0-9_]*
     */
    public function saveProperty($name, $value);

    /**
     * To remove a static property.
     *
     * @param string $name
     *
     * @return $this
     * @throw Exception\IllegalName if the name does not respect the pattern [a-zA-Z_][a-zA-Z0-9_]*
     */
    public function deleteProperty($name);

    /**
     * To return to the closure a static property.
     *
     * @param string $name
     *
     * @return mixed
     * @throw Exception\IllegalName if the name does not respect the pattern [a-zA-Z_][a-zA-Z0-9_]*
     */
    public function getProperty($name);

    /**
     * To check if a static property is stored.
     *
     * @param string $name
     *
     * @return boolean
     * @throw Exception\IllegalName if the name does not respect the pattern [a-zA-Z_][a-zA-Z0-9_]*
     */
    public function testProperty($name);
}
