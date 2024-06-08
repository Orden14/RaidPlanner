<?php

declare(strict_types=1);

namespace App\Tests\Support;

use Codeception\Actor;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
final class AcceptanceTester extends Actor
{
    use _generated\AcceptanceTesterActions;

    public function loginAs(string $username): void
    {
        $this->amOnPage('/login');
        $this->fillField('username', $username);
        $this->fillField('password', $username);
        $this->click('#login-button');
    }

    public function logout(): void
    {
        $this->amOnPage('/logout');
    }

    public function timedScrollTo(string $selector, bool $longScroll = false): void
    {
        $this->scrollTo($selector);
        $this->wait($longScroll ? 2 : 0.5);
    }
}
