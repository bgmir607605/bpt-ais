<?php

namespace app\modules\inspector\controllers;

class DefaultController extends \app\controllers\AccessController
{
    public function getRoleName(){
        return 'inspector';
    }
}
