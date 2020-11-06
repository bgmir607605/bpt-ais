<?php

namespace tests\unit\models;

use app\models\Direct;

class DirectTest extends \Codeception\Test\Unit
{
    public function testFindOne()
    {
        $direct = Direct::findOne(2);
        expect_that($direct);
        expect($direct->name)->equals('Информационные системы и программирование');

        expect_not(Direct::findOne(13));
    }
    
//
//    public function testFindUserByAccessToken()
//    {
//        expect_that($user = User::findIdentityByAccessToken('100-token'));
//        expect($user->username)->equals('admin');
//
//        expect_not(User::findIdentityByAccessToken('non-existing'));        
//    }
//
//    public function testFindUserByUsername()
//    {
//        expect_that($user = User::findByUsername('admin'));
//        expect_not(User::findByUsername('not-admin'));
//    }
//
//    /**
//     * @depends testFindUserByUsername
//     */
//
//    public function testValidateUser($user)
//    {
//        $user = User::findByUsername('admin');
//        expect_that($user->validateAuthKey('test100key'));
//        expect_not($user->validateAuthKey('test102key'));
//
//        expect_that($user->validatePassword('admin'));
//        expect_not($user->validatePassword('123456'));        
//    }

}
