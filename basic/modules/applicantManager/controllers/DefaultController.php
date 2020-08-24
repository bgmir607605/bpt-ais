<?php

namespace app\modules\applicantManager\controllers;


class DefaultController extends \app\controllers\AccessController
{
    public function getRoleName(){
        return 'applicantManager';
    }
}