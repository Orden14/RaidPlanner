<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Encounter;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use Override;

final class EventBattleTest extends EntityTest
{
    private GuildEvent $guildEvent;
    private Encounter $encounter;
    private PlayerSlot $playerSlot;

    public function _before(): void
    {
        $this->guildEvent = $this->tester->grabEntityFromRepository(GuildEvent::class, ['id' => 1]);
        $this->encounter = $this->tester->grabEntityFromRepository(Encounter::class, ['id' => 1]);
        $this->playerSlot = $this->tester->grabEntityFromRepository(PlayerSlot::class, ['id' => 1]);
    }

    /**
     * @return EventBattle
     */
    #[Override]
    public function _generateEntity(): object
    {
        $eventBattle = new EventBattle();

        $eventBattle->setGuildEvent($this->guildEvent)
            ->setEncounter($this->encounter)
            ->addPlayerSlot($this->playerSlot);

        return $eventBattle;
    }

    /**
     * @param EventBattle $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertNotNull($generatedEntity->getCreatedAt());
    }

    /**
     * @param EventBattle $generatedEntity
     */
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame($this->guildEvent, $generatedEntity->getGuildEvent());
        $this->tester->assertSame($this->encounter, $generatedEntity->getEncounter());
        $this->tester->assertContains($this->playerSlot, $generatedEntity->getPlayerSlots());
    }

    #[Override]
    public function _expectedCountAssertionErrors(): int
    {
        return 0;
    }
}