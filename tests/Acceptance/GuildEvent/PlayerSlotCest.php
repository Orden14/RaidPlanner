<?php

namespace App\Tests\Acceptance\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Tests\Support\AcceptanceTester;
use Codeception\Attribute\Depends;
use LogicException;

final class PlayerSlotCest
{
    private const string USER_NAME = "member";

    private User $user;
    private GuildEvent $guildEvent;

    public function _before(AcceptanceTester $I): void
    {
        $this->user = $I->grabEntityFromRepository(User::class, ['username' => self::USER_NAME]);
        $this->guildEvent = $I->grabEntityFromRepository(GuildEvent::class, ['id' => 1]);
    }

    public function _after(AcceptanceTester $I): void
    {
        $I->logout();
    }

    public function testSlotAssignment(AcceptanceTester $I): void
    {
        $this->resetParticipationForUser($I);

        $I->loginAs($this->user->getUsername());
        $I->amOnPage("/event/show/{$this->guildEvent->getId()}");
        $I->see($this->guildEvent->getTitle());

        $firstBattleId = (int)$I->grabAttributeFrom('button.accordion-button[aria-expanded="true"] span', 'data-battle-id');

        foreach ($this->guildEvent->getEventBattles() as $eventBattle) {
            $this->assignToSlotOnBattle($I, $eventBattle, $eventBattle->getId() === $firstBattleId);
        }
    }

    public function testUnassignSlot(AcceptanceTester $I): void
    {
        $I->loginAs('admin');
        $I->amOnPage("/event/show/{$this->guildEvent->getId()}");
        $I->see($this->guildEvent->getTitle());

        $firstBattleId = (int)$I->grabAttributeFrom('button.accordion-button[aria-expanded="true"] span', 'data-battle-id');
        $eventBattle = $I->grabEntityFromRepository(EventBattle::class, ['id' => $firstBattleId]);
        $this->unassignAllSlotsForBattle($I, $eventBattle);
    }

    private function assignToSlotOnBattle(AcceptanceTester $I, EventBattle $eventBattle, bool $firstBattle = false): void
    {
        if (!$firstBattle) {
            $I->timedScrollTo("span[data-battle-id=\"{$eventBattle->getId()}\"]");
            $I->click("span[data-battle-id=\"{$eventBattle->getId()}\"]");
        }

        $playerSlot = $this->findAvailableSlotForBattle($eventBattle);
        $assignmentButtonSelector = $this->getSelectorForPlayerSlotButton($playerSlot);

        $I->timedScrollTo($assignmentButtonSelector);
        $I->click($assignmentButtonSelector);
        $I->waitAjaxLoad();
        $I->see($this->user->getUsername(), "div[data-slot-id=\"{$playerSlot->getId()}\"]");
    }

    private function unassignAllSlotsForBattle(AcceptanceTester $I, EventBattle $eventBattle): void
    {
        foreach ($eventBattle->getPlayerSlots() as $playerSlot) {
            if ($playerSlot->getPlayer() !== null) {
                $assignedUser = $playerSlot->getPlayer();
                $unassignmentButtonSelector = $this->getSelectorForPlayerSlotButton($playerSlot, true);


                $I->timedScrollTo($unassignmentButtonSelector);
                $I->see($assignedUser->getUsername(), "div[data-slot-id=\"{$playerSlot->getId()}\"]");
                $I->click($unassignmentButtonSelector);
                $I->wait(0.2);
                $I->click('Confirmer');
                $I->waitAjaxLoad();
                $I->dontSee($assignedUser->getUsername(), "div[data-slot-id=\"{$playerSlot->getId()}\"]");
            }
        }
    }

    /**
     * @return PlayerSlot[]
     */
    private function findEmptySlots(): array
    {
        $emptySlots = [];

        foreach ($this->guildEvent->getEventBattles() as $eventBattle) {
            foreach ($eventBattle->getPlayerSlots() as $playerSlot) {
                if ($playerSlot->getPlayer() === null && $playerSlot->getBuild() !== null) {
                    $emptySlots[] = $playerSlot;
                }
            }
        }

        return $emptySlots;
    }

    private function findAvailableSlotForBattle(EventBattle $eventBattle): PlayerSlot
    {
        $emptySlots = $this->findEmptySlots();

        foreach ($emptySlots as $slot) {
            if ($slot->getEventBattle()?->getId() === $eventBattle->getId()) {
                return $slot;
            }
        }

        sleep(5);
        throw new LogicException('There should be at least one free slot for this battle.');
    }

    private function getSelectorForPlayerSlotButton(PlayerSlot $playerSlot, bool $unassignSlotAction = false): string
    {
        if ($unassignSlotAction) {
            return "span[data-guild-event--confirm-unassign-slot-url-value='/player-slot/unassign/{$playerSlot->getId()}']";
        }

        return "span[data-guild-event--assign-battle-slot-url-value='/player-slot/assign/{$playerSlot->getId()}']";
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
