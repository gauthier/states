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
namespace Teknoo\States\Loader;

use Composer\Autoload\ClassLoader;
use Teknoo\States\State\StateInterface;
use Teknoo\States\Proxy\ProxyInterface;

/**
 * Class FinderComposer
 * Default implementation of the finder. It is used with this library to find from each stated class
 * all states and the proxy. It needs an instance of the Composer Loader to find php classes and load them.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class FinderComposer implements FinderInterface
{
    /**
     * Current stated class's name.
     *
     * @var string
     */
    private $statedClassName;

    /**
     * Folder/Phar of the stated class.
     *
     * @var string
     */
    private $pathString;

    /**
     * Default proxy class to use when there are no proxy class.
     *
     * @var string
     */
    protected $defaultProxyClassName = '\Teknoo\States\Proxy\Standard';

    /**
     * List of states already fetched by this finder.
     *
     * @var \ArrayObject
     */
    private $statesNamesList;

    /**
     * @var ClassLoader
     */
    private $composerInstance;

    /**
     * Initialize finder.
     *
     * @param string      $statedClassName
     * @param string      $pathString
     * @param ClassLoader $composerInstance
     */
    public function __construct(string $statedClassName, string $pathString, ClassLoader $composerInstance)
    {
        $this->statedClassName = $statedClassName;
        $this->pathString = $pathString;
        $this->composerInstance = $composerInstance;
    }

    /**
     * Check if a required class exists, and if not, try to load it via composer and recheck.
     * Can not use directly autoloader with class_exists. Sometimes it's behavior is non consistent
     * with spl_autoload_register.
     *
     * @param string $className
     *
     * @return bool
     */
    private function testClassExists($className)
    {
        if (\class_exists($className, false)) {
            return true;
        }

        return $this->composerInstance->loadClass($className) && \class_exists($className, false);
    }

    /**
     * To get the canonical stated class name associated to this state.
     *
     * @return string
     */
    public function getStatedClassName(): string
    {
        return $this->statedClassName;
    }

    /**
     * To list all available states of the stated class.
     *
     * @return string[]
     *
     * @throws Exception\UnavailablePath if the states' folder is not available
     * @throws Exception\UnReadablePath  if the states' folder is not readable
     */
    public function listStates()
    {
        if (!$this->statesNamesList instanceof \ArrayObject) {
            //Checks if states are stored into the standardized path
            $statesPath = $this->pathString.DIRECTORY_SEPARATOR.FinderInterface::STATES_PATH;
            if (!\is_dir($statesPath)) {
                throw new Exception\UnavailablePath(
                    \sprintf('Error, the path "%s" was not found', $statesPath)
                );
            }

            //Checks if the path is available, use error_reporting to not use @
            $oldErrorReporting = \error_reporting(E_ALL & ~E_WARNING);
            $hD = \opendir($statesPath);
            \error_reporting($oldErrorReporting);
            if (false === $hD) {
                throw new Exception\UnReadablePath(
                    \sprintf('Error, the path "%s" is not available', $statesPath)
                );
            }

            //Extracts all states (No check class exists)
            $statesNameArray = new \ArrayObject();
            while (false !== ($file = \readdir($hD))) {
                switch ($file) {
                    case '.';
                    case '..';
                        break;
                    default:
                        if (\strlen($file) - 4 == \strrpos($file, '.php')) {
                            $stateName = \substr($file, 0, -4);
                            $statesNameArray[] = $stateName;
                        }
                        break;
                }
            }

            closedir($hD);

            $this->statesNamesList = $statesNameArray;
        }

        return $this->statesNamesList;
    }

    /**
     * To load the required state object of the stated class.
     *
     * @param string $stateName
     *
     * @return string
     *
     * @throws Exception\UnavailableState if the required state is not available
     */
    public function loadState(string $stateName): string
    {
        $stateClassName = $this->statedClassName.'\\'.FinderInterface::STATES_PATH.'\\'.$stateName;
        if (!$this->testClassExists($stateClassName)) {
            throw new Exception\UnavailableState(
                \sprintf('Error, the state "%s" is not available', $stateName)
            );
        }

        return $stateClassName;
    }

    /**
     * To return the list of parent php classes used by a state.
     *
     * @param string $stateName
     *
     * @return string[]
     *
     * @throws Exception\UnavailableState if the required state is not available
     */
    public function getStateParentsClassesNamesList(string $stateName): array
    {
        $classNameList = [];

        //Get name of the parent class
        $parentClassName = \get_parent_class($this->loadState($stateName));
        while (false !== $parentClassName) {
            $classNameList[] = $parentClassName;
            $parentClassName = \get_parent_class($parentClassName);
        }

        return $classNameList;
    }

    /**
     * To load and build the required state object of the stated class.
     *
     * @param string $stateName
     * @param bool   $privateMode     : If it's enable, private methods are not available
     * @param string $statedClassName
     * @param array  $aliases
     *
     * @return StateInterface
     *
     * @throws Exception\UnavailableState if the required state is not available
     * @throws Exception\IllegalState     if the state object does not implement the interface
     */
    public function buildState(string $stateName, bool $privateMode, string $statedClassName, array $aliases = []): StateInterface
    {
        //Load the state class if it is not already done
        $stateClassName = $this->loadState($stateName);

        $stateObject = new $stateClassName($privateMode, $statedClassName, $aliases);
        if (!$stateObject instanceof StateInterface) {
            throw new Exception\IllegalState(
                \sprintf(
                    'Error, the state "%s" does not implement the interface "States\StateInterface"',
                    $stateName
                )
            );
        }

        return $stateObject;
    }

    /**
     * To extract the class name from the stated class name with namespace.
     *
     * @param string $statedClassName
     *
     * @return string
     */
    private function getClassedName(string $statedClassName): string
    {
        $parts = \explode('\\', $statedClassName);

        return \array_pop($parts);
    }

    /**
     * To search and load the proxy class for this stated class.
     * If the class has not proxy, load the default proxy for this stated class.
     *
     * @return string
     */
    public function loadProxy(): string
    {
        //Build the class name
        $classPartName = $this->getClassedName($this->statedClassName);
        $proxyClassName = $this->statedClassName.'\\'.$classPartName;

        if (!$this->testClassExists($proxyClassName)) {
            //The stated class has not its own proxy, reuse the standard proxy, as an alias
            \class_alias($this->defaultProxyClassName, $proxyClassName, true);
            \class_alias($this->defaultProxyClassName, $this->statedClassName, false);
        } else {
            //To access this class directly without repeat the stated class name
            if (!\class_exists($this->statedClassName, false)) {
                \class_alias($proxyClassName, $this->statedClassName, false);
            }
        }

        return $proxyClassName;
    }

    /**
     * To return the list of parents stated classes of the stated classes, library classes (Integrated proxy and
     * standard proxy are excluded).
     *
     * @return string[]
     *
     * @throws Exception\IllegalProxy If the proxy class is not valid
     */
    public function listParentsClassesNames()
    {
        //Build the class name
        $classPartName = $this->getClassedName($this->statedClassName);
        $proxyClassName = $this->statedClassName.'\\'.$classPartName;

        //Fetch parents classes and extract library classes
        if (\class_exists($proxyClassName, false)) {
            $finalParentsClassesList = new \ArrayObject();

            //Get name of the parent class
            $parentClassName = \get_parent_class($proxyClassName);
            while (false !== $parentClassName && false === \strpos($parentClassName, 'Teknoo\\States')) {
                if (\class_exists($parentClassName, false)) {
                    //Use reflection class on the reflection class, ignore bad proxy and abstract class
                    $reflectionClassInstance = new \ReflectionClass($parentClassName);
                    if ($reflectionClassInstance->implementsInterface(ProxyInterface::class)
                        && false === $reflectionClassInstance->isAbstract()) {
                        $parentClassName = \substr($parentClassName, 0, \strrpos($parentClassName, '\\'));
                        $finalParentsClassesList[] = $parentClassName;
                    }
                }
                $parentClassName = \get_parent_class($parentClassName);
            }

            return $finalParentsClassesList;
        }

        throw new Exception\IllegalProxy('Proxy class was not found');
    }

    /**
     * To load and build a proxy object for the stated class.
     *
     * @param array $arguments argument for proxy
     *
     * @return ProxyInterface
     *
     * @throws Exception\IllegalProxy If the proxy object does not implement Proxy/ProxyInterface
     */
    public function buildProxy($arguments = null): ProxyInterface
    {
        //Load the proxy if it is not already done
        $proxyClassName = $this->loadProxy();

        //Load an instance of this proxy and test if it implements the interface ProxyInterface
        $proxyObject = new $proxyClassName($arguments);
        if ($proxyObject instanceof ProxyInterface) {
            return $proxyObject;
        }

        //Throw an error
        throw new Exception\IllegalProxy(
            \sprintf('Error, the proxy of "%s" does not implement "Proxy\ProxyInterface"', $this->statedClassName)
        );
    }
}
