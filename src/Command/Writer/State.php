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
namespace Teknoo\States\Command\Writer;

use Teknoo\States\Loader\FinderInterface;

/**
  * Class State
  * Writer to create or update a state.
  *
  *
  *
  * @link        http://teknoo.software/states Project website
  *
  * @license     http://teknoo.software/license/mit         MIT License
  * @author      Richard Déloge <richarddeloge@gmail.com>
  */
 class State extends AbstractWriter
 {
     /**
      * Generator to build the php code for the new state of the stated class.
      *
      * @param string $className
      * @param string $namespace
      * @param string $stateName
      *
      * @return string
      */
     protected function generateState($className, $namespace, $stateName)
     {
         return <<<EOF
<?php

namespace $namespace\\$className\\States;

use Teknoo\\States\\State\\AbstractState;

/**
 * State $stateName
 * State for the stated class $className
 */
class $stateName extends AbstractState
{
}
EOF;
     }

     /**
      * Method to create a new state for the defined stated class.
      *
      * @param string $className
      * @param string $namespace
      * @param string $stateName
      *
      * @return bool
      */
     public function createState($className, $namespace, $stateName)
     {
         $stateCode = $this->generateState($className, $namespace, $stateName);
         $stateFileName = FinderInterface::STATES_PATH.DIRECTORY_SEPARATOR.$stateName.'.php';

         if (0 < $this->write($stateFileName, $stateCode)) {
             return true;
         } else {
             return false;
         }
     }
 }
