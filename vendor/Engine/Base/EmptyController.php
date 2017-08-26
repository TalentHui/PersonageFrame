<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - EmptyController.php
 * Author:    WuHui
 * Date:      2017-08-26 00:08
 * Desc:      空控制器
 *******************************************************************************/

namespace Engine\Base;


use Engine\Common\RequestFunction;

final class EmptyController extends BaseController
{
    public function run()
    {
        if (RequestFunction::isCommandLine()) {

            echo '************************** Error * Message ***************************' . PHP_EOL;
            echo '* Sorry ! The controller you load does not exist' . PHP_EOL;
            echo '**********************************************************************' . PHP_EOL;
            echo '* Controller : ' . $this->argvParams->getController() . PHP_EOL;
            echo '* Action     : ' . $this->argvParams->getAction() . PHP_EOL;
            echo '**********************************************************************' . PHP_EOL;
        } else {

            $this->dispatcher->loadTemplateView();
        }
    }
}