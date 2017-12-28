<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - ViewController.php
 * Author:    WuHui
 * Date:      2017-12-27 17:42
 * Desc:      视图控制器
 *******************************************************************************/

use Engine\Base\BaseController;

class ViewController extends  BaseController
{
    public function processEChartsView()
    {
        if (empty($this->postParams->getItem('step'))) {
            $this->dispatcher->loadTemplateView('View\processEChartsView');
            return;
        }

        \Engine\Common\RequestFunction::ajaxJsonReturn(\Engine\Common\EChartsFunction::packEChartsReturnData());
    }
}