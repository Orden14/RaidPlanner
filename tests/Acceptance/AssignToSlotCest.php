<?php

namespace App\Tests\Acceptance;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Tests\Support\AcceptanceTester;
use LogicException;

final class AssignToSlotCest
{
    private const string USER_NAME = "member";

    private User $user;
    private GuildEvent $guildEvent;

    public function _before(AcceptanceTester $I): void
    {
        $this->user = $I->grabEntityFromRepository(User::class, ['username' => self::USER_NAME]);
        $guildEvents = $I->grabEntitiesFromRepository(GuildEvent::class);

        foreach ($guildEvents as $guildEvent) {
            if (count($guildEvent->getEventBattles()) >= 1
                && count($guildEvent->getEventAttendances()) < InstanceTypeEnum::getMaxPlayersByType(InstanceTypeEnum::RAID)
            ) {
                $this->guildEvent = $guildEvent;
                break;
            }
        }

        $this->resetParticipationForUser($I);

        $I->loginAs($this->user->getUsername());
    }

    public function _after(AcceptanceTester $I): void
    {
        $I->logout();
    }

    public function testSlotAssignment(AcceptanceTester $I): void
    {
        $I->amOnPage("/event/show/{$this->guildEvent->getId()}");
        $I->see($this->guildEvent->getTitle());

        $firstBattleId = (int)$I->grabAttributeFrom('button.accordion-button[aria-expanded="true"] span', 'data-battle-id');

        foreach ($this->guildEvent->getEventBattles() as $eventBattle) {
            $this->assignToSlotOnBattle($I, $eventBattle, $eventBattle->getId() === $firstBattleId);
        }
    }

    private function assignToSlotOnBattle(AcceptanceTester$I, EventBattle $eventBattle, bool $firstBattle = false): void
    {
        if (!$firstBattle) {
            $I->timedScrollTo("span[data-battle-id=\"{$eventBattle->getId()}\"]");
            $I->click("span[data-battle-id=\"{$eventBattle->getId()}\"]");
        }

        $playerSlot = $this->findFreeSlotForBattle($eventBattle);
        $assignmentButtonSelector = $this->getSelectorForPlayerSlotAssignmentButton($playerSlot);
        $I->timedScrollTo($assignmentButtonSelector);
        $I->click($assignmentButtonSelector);
        $I->wait(0.5);
        $I->waitAjaxLoad();
        $I->see($this->user->getUsername(), "div[data-slot-id=\"{$playerSlot->getId()}\"]");
    }

    /**
     * @return PlayerSlot[]
     */
    private function findEmptySlots(): array
    {
        $freeSlots = [];

        foreach ($this->guildEvent->getEventBattles() as $eventBattle) {
            foreach ($eventBattle->getPlayerSlots() as $playerSlot) {
                if ($playerSlot->getPlayer() === null && $playerSlot->getBuild() !== null) {
                    $freeSlots[] = $playerSlot;
                }
            }
        }

        return $freeSlots;
    }

    private function findFreeSlotForBattle(EventBattle $eventBattle): PlayerSlot
    {
        $freeSlots = $this->findEmptySlots();

        foreach ($freeSlots as $slot) {
            if ($slot->getEventBattle()?->getId() === $eventBattle->getId()) {
                return $slot;
            }
        }

        sleep(5);
        throw new LogicException('There should be at least one free slot for this battle.');
    }

    private function getSelectorForPlayerSlotAssignmentButton(PlayerSlot $playerSlot): string
    {
        return "span[data-guild-event--assign-battle-slot-url-value='/guild-event/player-slot/battle/slot/assign/{$playerSlot->getId()}']";
    }

    private function resetParticipationForUser(AcceptanceTester $I): void
    {
        $potentialEventAttendances = $I->grabEntitiesFromRepository(EventAttendance::class, [
            'guildEvent' => $this->guildEvent,
            'user' => $this->user
        ]);

        if (reset($potentialEventAttendances)) {
            $eventAttendance = reset($potentialEventAttendances);
            $eventAttendance->setType(AttendanceTypeEnum::PLAYER);

            foreach ($this->guildEvent->getEventBattles() as $eventBattle) {
                $assignedPlayerSlots = $I->grabEntitiesFromRepository(PlayerSlot::class, [
                    'player' => $this->user,
                    'eventBattle' => $eventBattle
                ]);

                foreach ($assignedPlayerSlots as $assignedPlayerSlot) {
                    $assignedPlayerSlot->setPlayer(null);
                }
            }
        }

        $I->flushToDatabase();
    }
}
