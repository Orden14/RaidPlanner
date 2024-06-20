<?php

namespace App\Tests\Acceptance;

use App\Tests\Support\AcceptanceTester;

final readonly class LoginCest
{
    public function testLoginWithValidCredentials(AcceptanceTester $I): void
    {
        $I->loginAs('member');
        $I->seeCurrentUrlEquals('/planning/');
    }

    public function testLoginWithInvalidCredentials(AcceptanceTester $I): void
    {
        $I->loginAs('invalidAccount');
        $I->seeCurrentUrlEquals('/login');
        $I->seeElement('.alert.alert-danger');
    }
}
