<?php

namespace App\Tests\Functional\GuildEvent;

use App\Entity\GuildEvent;
use App\Enum\InstanceTypeEnum;
use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\Depends;
use DateTime;

final class GuildEventCrudCest
{
    private GuildEvent $guildEvent;

    public function _before(FunctionalTester $I): void
    {
        $I->loginAs('admin');
    }

    public function _after(FunctionalTester $I): void
    {
        $I->logout();
    }

    public function testGuildEventCrud(FunctionalTester $I): void
    {
        $this->_testCreation($I);
        $this->_testEdition($I);
        $this->_testDeletion($I);
    }

    private function _testCreation(FunctionalTester $I): void
    {
        $mondayLastWeek = (new DateTime('last monday - 1 week'))->setTime(21, 0);

        $I->amOnRoute('calendar_index');
        $I->seeResponseCodeIsSuccessful();

        $csrfToken = $I->grabValueFrom('input[name="guild_event[_token]"]');
        $I->submitSymfonyForm('guild_event', [
            '[title]' => 'GuildEventCestCreate',
            '[type]' => InstanceTypeEnum::STRIKE->value,
            '[guildRaid]' => false,
            '[start]' => $mondayLastWeek->format('Y-m-d\TH:i'),
            '[end]' => (clone $mondayLastWeek)->modify('+1 hour')->format('Y-m-d\TH:i'),
            '[oldMembersAllowed]' => true,
            '[membersManageEvent]' => true,
            '[_token]' => $csrfToken,
        ]);

        $I->seeResponseCodeIsSuccessful();

        $createdEvent = $I->grabEntityFromRepository(GuildEvent::class, ['title' => 'GuildEventCestCreate']);
        $I->assertEquals('GuildEventCestCreate', $createdEvent->getTitle());
        $I->assertEquals(InstanceTypeEnum::STRIKE, $createdEvent->getType());
        $I->assertFalse($createdEvent->isGuildRaid());
        $I->assertEquals($mondayLastWeek, $createdEvent->getStart());
        $I->assertEquals((clone $mondayLastWeek)->modify('+1 hour'), $createdEvent->getEnd());
        $I->assertTrue($createdEvent->isOldMembersAllowed());
        $I->assertTrue($createdEvent->canMembersManageEvent());

        $this->guildEvent = $createdEvent;
    }

    private function _testEdition(FunctionalTester $I): void
    {
        $I->amOnRoute('guild_event_show', ['id' => $this->guildEvent->getId()]);
        $I->seeResponseCodeIsSuccessful();

        $csrfToken = $I->grabValueFrom('input[name="guild_event[_token]"]');
        $I->submitSymfonyForm('guild_event', [
            '[title]' => 'GuildEventCestCreateEdited',
            '[guildRaid]' => true,
            '[oldMembersAllowed]' => false,
            '[membersManageEvent]' => false,
            '[_token]' => $csrfToken,
        ]);

        $I->seeResponseCodeIsSuccessful();

        $editedEvent = $I->grabEntityFromRepository(GuildEvent::class, ['title' => 'GuildEventCestCreateEdited']);
        $I->assertEquals('GuildEventCestCreateEdited', $editedEvent->getTitle());
        $I->assertTrue($editedEvent->isGuildRaid());
        $I->assertFalse($editedEvent->isOldMembersAllowed());
        $I->assertFalse($editedEvent->canMembersManageEvent());
    }

    private function _testDeletion(FunctionalTester $I): void
    {
        $I->amOnRoute('guild_event_show', ['id' => $this->guildEvent->getId()]);
        $I->seeResponseCodeIsSuccessful();

        $csrfToken = $I->grabValueFrom('input[name="_token"]');
        $I->submitSymfonyForm("delete", ['_token' => $csrfToken]);
        $I->seeResponseCodeIsSuccessful();

        $I->dontSeeInRepository(GuildEvent::class, ['id' => $this->guildEvent->getId()]);
    }
}
