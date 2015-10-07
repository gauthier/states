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
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\States\Loader;

use UniAlteri\States\DI;
use UniAlteri\States\Factory;

/**
 * Class LoaderStandard
 * Default implementation of the "stated class autoloader".
 * It is used to allow php to load automatically stated class.
 *
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @deprecated  Removed since 2.0 : Use LoaderComposer instead of LoaderStandard
 *
 * @api
 */
class LoaderStandard implements LoaderInterface
{
    /**
     * DI Container to use with this loader.
     *
     * @var DI\ContainerInterface
     */
    protected $diContainer = null;

    /**
     * List of paths to include for this loader.
     *
     * @var \ArrayObject
     */
    protected $includedPathsArray = null;

    /**
     * List of paths where namespace are available.
     *
     * @var \SplQueue[]
     */
    protected $namespacesArray = null;

    /**
     * Backup of previous included path configuration.
     *
     * @var \SplStack
     */
    protected $previousIncludedPathStack = null;

    /**
     * Manager to use by this loader to manipulate include path.
     *
     * @var IncludePathManagerInterface
     */
    protected $includePathManager = null;

    /**
     * to enable PSR 0 loader to search required stated class in included paths
     * disabled by default for performance reasons.
     *
     * @var bool
     */
    protected $psr0LoaderEnabled = false;

    /**
     * Initialize the loader object.
     *
     * @param IncludePathManagerInterface $includePathManager
     * @param bool                        $enablePSR0Loader   to enable PSR 0 loader to search required stated class in included paths
     *                                                        disabled by default for performance reasons
     *
     * @throws Exception\IllegalArgument $includePathManager does not implement the interface IncludePathManagerInterface
     */
    public function __construct($includePathManager, $enablePSR0Loader = false)
    {
        if (!$includePathManager instanceof IncludePathManagerInterface) {
            throw new Exception\IllegalArgument(
                'Error, the include path manager does not implement the interface IncludePathManagerInterface'
            );
        }

        //Initialize the object
        $this->includedPathsArray = new \ArrayObject();
        $this->namespacesArray = new \ArrayObject();
        $this->previousIncludedPathStack = new \SplStack();
        $this->includePathManager = $includePathManager;
        $this->psr0LoaderEnabled = $enablePSR0Loader;

        if (class_exists('\Phar', false)) {
            //instructs phar to intercept fopen, file_get_contents, opendir, and all of the stat-related functions
            //Needed to support Phar with the loader
            \Phar::interceptFileFuncs();
        }
    }

    /**
     * To enable or disable  PSR 0 loader to search required stated class in included paths
     * disabled by default for performance reasons.
     *
     * @param bool $state
     *
     * @return $this
     *
     * @deprecated  Removed since 2.0
     */
    public function setPSR0LoaderState($state)
    {
        $this->psr0LoaderEnabled = $state;

        return $this;
    }

    /**
     * To know the state of the PSR 0 loader to search required stated class in included paths.
     *
     * @return bool
     *
     * @deprecated  Removed since 2.0
     */
    public function getPSR0LoaderState()
    {
        return $this->psr0LoaderEnabled;
    }

    /**
     * Return the current include path manager.
     *
     * @return IncludePathManagerInterface
     *
     * @deprecated  Removed since 2.0
     */
    protected function getIncludePathManager()
    {
        return $this->includePathManager;
    }

    /**
     * To register a DI container for this object.
     *
     * @param DI\ContainerInterface $container
     *
     * @return $this
     */
    public function setDIContainer(DI\ContainerInterface $container)
    {
        $this->diContainer = $container;

        return $this;
    }

    /**
     * To return the DI Container used for this object.
     *
     * @return DI\ContainerInterface
     */
    public function getDIContainer()
    {
        return $this->diContainer;
    }

    /**
     * Method to add a path on the list of location where find class.
     *
     * @param string $path
     *
     * @return $this
     *
     * @throws Exception\UnavailablePath if the path is not readable
     *
     * @deprecated  Removed since 2.0
     */
    public function addIncludePath($path)
    {
        if (false === is_dir($path)) {
            throw new Exception\UnavailablePath(
                sprintf('Error, the path "%s" is not available', $path)
            );
        }

        $this->includedPathsArray[$path] = $path;

        return $this;
    }

    /**
     * To list all active included path for this loaded.
     *
     * @return string[]
     *
     * @deprecated  Removed since 2.0
     */
    public function getIncludedPaths()
    {
        return $this->includedPathsArray;
    }

    /**
     * To register a location to find some classes of a namespace.
     * A namespace can has several locations.
     *
     * @param string $namespace
     * @param string $path
     *
     * @return $this
     *
     * @throws Exception\IllegalArgument if the path is not a valid string
     *
     * @deprecated  Removed since 2.0
     */
    public function registerNamespace($namespace, $path)
    {
        if (!is_string($path)) {
            throw new Exception\IllegalArgument('Error, the path is not a valid string');
        }

        //Prepend the namespace with "\" to avoid mismatch error
        if ('\\' != $namespace[0]) {
            $namespace = '\\'.$namespace;
        }

        //Initialize the stack of paths for this namespace
        if (!isset($this->namespacesArray[$namespace])) {
            $this->namespacesArray[$namespace] = new \SplQueue();
        }

        if ('/' == $path[strlen($path) - 1]) {
            $path = substr($path, 0, strlen($path) - 1);
        }

        $this->namespacesArray[$namespace]->enqueue($path);
    }

    /**
     * To list all registered namespace.
     *
     * @return \ArrayObject
     *
     * @deprecated  Removed since 2.0
     */
    public function listNamespaces()
    {
        return $this->namespacesArray;
    }

    /**
     * To update included path before loading class.
     */
    protected function updateIncludedPaths()
    {
        //Convert paths to string
        //Update path into PHP
        $oldIncludedPaths = $this->getIncludePathManager()->setIncludePath(
            array_merge(
                $this->getIncludePathManager()->getIncludePath(),
                array_values($this->includedPathsArray->getArrayCopy())
            )
        );
        //Store previous path to restore them
        $this->previousIncludedPathStack->push($oldIncludedPaths);
    }

    /**
     * To restore previous loaded class.
     */
    protected function restoreIncludedPaths()
    {
        if (!$this->previousIncludedPathStack->isEmpty()) {
            $oldIncludedPaths = $this->previousIncludedPathStack->pop();
            $this->getIncludePathManager()->setIncludePath($oldIncludedPaths);
        }
    }

    /**
     * To load a class into a namespace.
     *
     * @param string $class
     *
     * @return bool
     *
     * @throws Exception\UnavailableFactory if the required factory is not available
     * @throws Exception\IllegalFactory     if the factory does not implement the good interface
     */
    protected function loadNamespaceClass($class)
    {
        $namespacePartsArray = explode('\\', $class);

        if (1 == count($namespacePartsArray)) {
            //No namespace, default to basic behavior
            return false;
        }

        $className = array_pop($namespacePartsArray);
        if ('' == $namespacePartsArray[0]) {
            //Prevent '\' at start
            array_shift($namespacePartsArray);
        }

        if (end($namespacePartsArray) === $className) {
            //Prevent when developer call directly the proxy class name
            array_pop($namespacePartsArray);
            $class = implode('\\', $namespacePartsArray).'\\'.$className;
        }

        //Rebuild namespace
        $namespaceString = '\\'.implode('\\', $namespacePartsArray);
        if (!isset($this->namespacesArray[$namespaceString])) {
            return false;
        }

        //Browse each
        $result = false;
        foreach ($this->namespacesArray[$namespaceString] as $path) {
            //Compute the factory file of the stated class
            $factoryFile = $path.DIRECTORY_SEPARATOR.$className.DIRECTORY_SEPARATOR.LoaderInterface::FACTORY_FILE_NAME;
            if (is_readable($factoryFile)) {
                //Factory found, load it
                include_once $factoryFile;

                //Compute factory class name with its namespace
                $factoryClassName = $namespaceString.'\\'.$className.'\\'.LoaderInterface::FACTORY_CLASS_NAME;
                if (class_exists($factoryClassName, false)) {
                    //Initialize the factory
                    try {
                        $this->buildFactory($factoryClassName, $class, $path.DIRECTORY_SEPARATOR.$className);
                        $result = true;
                    } catch (\Exception $e) {
                        $result = false;
                    }
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * To test if a file exists, check also in all included path.
     *
     * @param string $pathFile
     *
     * @return bool
     */
    protected function testFileExist($pathFile)
    {
        foreach ($this->getIncludePathManager()->getIncludePath() as $includedPath) {
            if (is_readable($includedPath.DIRECTORY_SEPARATOR.$pathFile)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Method called to load a class by __autoload of PHP Engine.
     *
     * @param string $className class name, support namespace prefixes
     *
     * @return bool
     *
     * @throws Exception\UnavailableFactory if the required factory is not available
     * @throws Exception\IllegalFactory     if the factory does not implement the good interface
     * @throws \Exception
     */
    public function loadClass($className)
    {
        $factoryClassName = $className.'\\'.LoaderInterface::FACTORY_CLASS_NAME;
        if (class_exists($factoryClassName, false)) {
            //Prevent class already loaded
            return true;
        }

        //Update included path
        $this->updateIncludedPaths();
        $classLoaded = false;

        try {
            //If the namespace is configured, check its paths
            if (false === $this->loadNamespaceClass($className)) {
                //Stated class was not found in defined namespace, check included path
                if (false === $this->psr0LoaderEnabled) {
                    //PSR 0 is disable for this loaded, skip next operations
                    $this->restoreIncludedPaths();

                    return $classLoaded;
                }

                //Class not found, switch to basic mode, replace \ and _ by a directory separator
                $path = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $className);
                if (DIRECTORY_SEPARATOR == $path[0]) {
                    $path = substr($path, 1);
                }
                //Compute the factory file of the stated class
                $factoryClassFile = $path.DIRECTORY_SEPARATOR.LoaderInterface::FACTORY_FILE_NAME;
                if ($this->testFileExist($factoryClassFile)) {
                    //Factory found, load it
                    include_once $factoryClassFile;

                    if (class_exists($factoryClassName, false)) {
                        //Class found and loaded
                        try {
                            //Initialize the factory
                            $this->buildFactory($factoryClassName, $className, $path);
                            $classLoaded = true;
                        } catch (\Exception $e) {
                            $classLoaded = false;
                        }
                    }
                }
            } else {
                $classLoaded = true;
            }
        } catch (\Exception $e) {
            $this->restoreIncludedPaths();
            throw $e;
        }

        $this->restoreIncludedPaths();

        return $classLoaded;
    }

    /**
     * Build the factory and initialize the loading stated class.
     *
     * @param string $factoryClassName
     * @param string $statedClassName
     * @param string $path
     *
     * @return Factory\FactoryInterface
     *
     * @throws Exception\UnavailableFactory if the required factory is not available
     * @throws Exception\IllegalFactory     if the factory does not implement the good interface
     */
    public function buildFactory($factoryClassName, $statedClassName, $path)
    {
        //Check if the factory class is loaded
        if (!class_exists($factoryClassName, false)) {
            throw new Exception\UnavailableFactory(
                sprintf('The factory of %s is not available', $statedClassName)
            );
        }

        //Create a new instance of the factory
        $factoryObject = new $factoryClassName();
        if (!$factoryObject instanceof Factory\FactoryInterface) {
            throw new Exception\IllegalFactory(
                sprintf('The factory of %s does not implement the interface', $statedClassName)
            );
        }

        //clone the di container for this stated class, it will has its own di container
        if ($this->diContainer instanceof DI\ContainerInterface) {
            $diContainer = clone $this->diContainer;
            $factoryObject->setDIContainer($diContainer);
        }

        //Call its initialize methods to load the stated class
        $factoryObject->initialize($statedClassName, $path);

        return $factoryObject;
    }
}
