<?php
/**
 * States
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @package     States
 * @subpackage  Loader
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     $Id$
 */

namespace UniAlteri\States\Loader;

/**
 * Class IncludePathManagement
 * @package UniAlteri\States\Loader
 * Object to manage set_included_path and unit test this section
 */
class IncludePathManagement implements IncludePathManagementInterface
{
    /**
     * Sets the include_path configuration option
     * @param string[] $paths (paths must be split into an array)
     * @return string[] old paths
     * @throws Exception\IllegalArgument if the argument $paths is not an array of string
     */
    public function setIncludePath($paths)
    {
        if (!is_array($paths) && !$paths instanceof \ArrayObject) {
            throw new Exception\IllegalArgument('Error, $paths is not an array of string');
        }

        if ($paths instanceof \ArrayObject) {
            //Convert to array
            $paths = $paths->getArrayCopy();
        }

        return explode(PATH_SEPARATOR, set_include_path(implode(PATH_SEPARATOR, $paths)));
    }

    /**
     * Gets the current include_path configuration option
     * @return string[] (paths must be split into an array)
     */
    public function getIncludePath()
    {
        $paths = explode(PATH_SEPARATOR, get_include_path());
        return new \ArrayObject($paths);
    }
}