<?php

namespace app\modules\student\controllers;

class DefaultController extends \app\controllers\AccessController
{
    public function getRoleName(){
        return 'student';
    }
}