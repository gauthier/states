<?php
/**
 * States
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @package     States
 * @subpackage  Command
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     0.9.2
 */

namespace UniAlteri\States\Command\Writer;

use Gaufrette\Filesystem;
use spec\Gaufrette\Adapter;

/**
 * Class Writer
 * Abstract class Writer to create, update a file
 *
 * @package     States
 * @subpackage  Command
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
abstract class AbstractWriter
{
    /**
     * Adapter to operate with file system
     * @var callable
     */
    protected $adapterFactory;

    /**
     * Filesystem object to manipulate file
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * Path of the current stated class
     * @var string
     */
    protected $statedClassPath;

    /**
     * Return the file system object from Gaufrette to
     * @return Filesystem
     */
    protected function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * Path of the current stated class to operate
     * @param FileSystem $fileSystem
     * @param string     $path
     */
    public function __construct($fileSystem, $path)
    {
        $this->fileSystem = $fileSystem;
        $this->statedClassPath = $path;
    }

    /**
     * Create or replace a file with a content
     * @param  string $file
     * @param  string $content
     * @return int
     */
    protected function write($file, $content)
    {
        return $this->getFileSystem()->write($file, $content, true);
    }
}