<?php

namespace app\modules\admin\controllers;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends \app\controllers\AccessController
{
    public function getRoleName(){
        return 'admin';
    }
}
