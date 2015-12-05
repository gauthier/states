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

namespace Teknoo\States\Loader;

/**
 * Class FinderIntegrated
 * Implementation of the finder. It is used with this library to find from each stated class all states
 * and the proxy. It extends FinderStandard to use '\Teknoo\States\Proxy\Integrated' instead of
 * '\Teknoo\States\Proxy\Standard'.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/states/license/mit         MIT License
 * @license     http://teknoo.software/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @deprecated  Removed since 2.0 : Use FinderComposerIntegrated instead of FinderIntegrated
 *
 * @api
 */
class FinderIntegrated extends FinderStandard
{
    /**
     * Default proxy class to use when there are no proxy class.
     *
     * @var string
     */
    protected $defaultProxyClassName = '\Teknoo\States\Proxy\Integrated';
}