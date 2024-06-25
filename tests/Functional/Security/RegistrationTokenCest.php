<?php

namespace App\Tests\Functional\Security;

use App\Entity\RegistrationToken;
use App\Entity\User;
use App\Tests\Support\FunctionalTester;

final class RegistrationTokenCest
{
    private RegistrationToken $registrationToken;

    public function testRegistrationToken(FunctionalTester $I): void
    {
        $this->_testCreation($I);
        $this->_testUsage($I);
    }

    private function _testCreation(FunctionalTester $I): void
    {
        $I->loginAs('admin');
        $I->amOnPage('/registration-token/');
        $I->seeResponseCodeIsSuccessful();

        $csrfToken = $I->grabValueFrom('input[name="registration_token[_token]"]');
        $I->submitSymfonyForm('registration_token', [
            '[expiryTime]' => 900,
            '[uses]' => 1,
            '[_token]' => $csrfToken,
        ]);
        $I->seeResponseCodeIsSuccessful();

        $this->registrationToken = $I->grabEntityFromRepository(RegistrationToken::class, [
            'uses' => 1,
        ]);

        $I->logout();
    }

    private function _testUsage(FunctionalTester $I): void
    {
        $I->amOnPage('/register');

        $csrfToken = $I->grabValueFrom('input[name="registration[_token]"]');
        $I->submitSymfonyForm('registration', [
            '[username]' => 'testTokenCreation',
            '[plainPassword][first]' => 'Abc123456789Abc',
            '[plainPassword][second]' => 'Abc123456789Abc',
            '[registrationToken]' => $this->registrationToken->getToken(),
            '[_token]' => $csrfToken,
        ]);
        $I->seeResponseCodeIsSuccessful();

        $I->seeInRepository(User::class, [
            'username' => 'testTokenCreation',
        ]);

        $I->dontSeeInRepository(RegistrationToken::class, [
            'token' => $this->registrationToken->getToken(),
        ]);
    }
}
