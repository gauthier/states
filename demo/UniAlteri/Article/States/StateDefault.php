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
 * @subpackage  Demo
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @license     http://agence.net.ua/states/license/new-bsd     New BSD License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     $Id$
 */

namespace demo\UniAlteri\Article\States;

use UniAlteri\States\States;

class StateDefault extends States\AbstractState
{
    /**
     * Return the title of this article
     * @return string
     */
    public function getTitle()
    {
        return $this->_getAttribute('title');
    }

    /**
     * To know if the article is published
     * @return bool
     */
    public function isPublished()
    {
        $isPublished = $this->_getAttribute('is_published');
        return !empty($isPublished);
    }
}