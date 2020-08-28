<?php

class UserAccesCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('/admin');
    }

    public function openAdminPageAsGuest(\FunctionalTester $I)
    {
        $I->see('Доступ запрещён');
        $I->seeResponseCodeIs(403);
        
    }
    
    public function openAdminPageAsAdmin(\FunctionalTester $I)
    {
        $I->see('Доступ запрещён');
        $I->seeResponseCodeIs(403);
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => '0000',
            ]);
        $I->amOnRoute('/admin');
        $I->seeResponseCodeIs(200);

    }



    // public function loginWithEmptyCredentials(\FunctionalTester $I)
    // {
    //     $I->submitForm('#login-form', []);
    //     $I->expectTo('see validations errors');
    //     $I->see('Username cannot be blank.');
    //     $I->see('Password cannot be blank.');
    // }

    // public function loginWithWrongCredentials(\FunctionalTester $I)
    // {
    //     $I->submitForm('#login-form', [
    //         'LoginForm[username]' => 'admin',
    //         'LoginForm[password]' => 'wrong',
    //     ]);
    //     $I->expectTo('see validations errors');
    //     $I->see('Incorrect username or password.');
    // }

    // public function loginSuccessfully(\FunctionalTester $I)
    // {
    //     $I->submitForm('#login-form', [
    //         'LoginForm[username]' => 'admin',
    //         'LoginForm[password]' => '0000',
    //     ]);
    //     $I->see('Logout (admin)');
    //     $I->dontSeeElement('form#login-form');              
    // }
}