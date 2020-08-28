<?php

namespace app\modules\groupManager\controllers;

class DefaultController extends \app\controllers\AccessController
{
    public function getRoleName(){
        return 'groupManager';
    }
}
