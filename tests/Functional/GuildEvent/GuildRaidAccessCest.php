<?php

namespace App\Tests\Functional\GuildEvent;

use App\Entity\GuildEvent;
use App\Tests\Support\FunctionalTester;

final class GuildRaidAccessCest
{
    private GuildEvent $guildEvent;

    public function _before(FunctionalTester $I): void
    {
        $guildRaids = $I->grabEntitiesFromRepository(GuildEvent::class, ['guildRaid' => true]);
        $this->guildEvent = reset($guildRaids);
    }

    public function testAccessAsGuest(FunctionalTester $I): void
    {
        $I->loginAs('guest');

        $I->amOnRoute('guild_event_show', ['id' => $this->guildEvent->getId()]);
        $I->seeResponseCodeIs(403);
        $I->dontSee($this->guildEvent->getTitle());
    }

    public function testAccessAsOldMember(FunctionalTester $I): void
    {
        $I->loginAs('old_member');

        $I->amOnRoute('guild_event_show', ['id' => $this->guildEvent->getId()]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentUrlEquals('/planning');
        $I->dontSee($this->guildEvent->getTitle());
    }

    public function testAccessAsTrial(FunctionalTester $I): void
    {
        $I->loginAs('trial');

        $I->amOnRoute('guild_event_show', ['id' => $this->guildEvent->getId()]);
        $I->seeResponseCodeIsSuccessful();
        $I->see($this->guildEvent->getTitle());

        $I->dontSeeElement('#event-toolbar');
    }

    public function testAccessAsMember(FunctionalTester $I): void
    {
        $I->loginAs('member');

        $I->amOnRoute('guild_event_show', ['id' => $this->guildEvent->getId()]);
        $I->seeResponseCodeIsSuccessful();
        $I->see($this->guildEvent->getTitle());

        $I->dontSeeElement('#event-toolbar');
    }

    public function testAccessAsOfficer(FunctionalTester $I): void
    {
        $I->loginAs('admin');

        $I->amOnRoute('guild_event_show', ['id' => $this->guildEvent->getId()]);
        $I->seeResponseCodeIsSuccessful();
        $I->see($this->guildEvent->getTitle());

        $I->seeElement('#event-toolbar');
    }
}
