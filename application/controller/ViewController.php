<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - ViewController.php
 * Author:    WuHui
 * Date:      2017-12-27 17:42
 * Desc:      视图控制器
 *******************************************************************************/

use Engine\Base\BaseController;
use Engine\Common\EChartsFunction;
use Engine\Common\RequestFunction;

class ViewController extends  BaseController
{
    public function processEChartsView()
    {
        if (empty($this->postParams->getItem('step'))) {
            $this->dispatcher->loadTemplateView('View\processEChartsView');
            return;
        }

        RequestFunction::ajaxJsonReturn(EChartsFunction::packEChartsReturnData());
    }
}