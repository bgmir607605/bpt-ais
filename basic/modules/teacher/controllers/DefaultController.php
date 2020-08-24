<?php

namespace app\modules\teacher\controllers;

class DefaultController extends \app\controllers\AccessController
{
    public function getRoleName(){
        return 'teacher';
    }
}