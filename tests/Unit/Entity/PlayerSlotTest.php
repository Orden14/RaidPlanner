<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Build;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use Override;

final class PlayerSlotTest extends EntityTest
{
    private User $player;
    private Build $build;
    private EventBattle $eventBattle;

    public function _before(): void
    {
        $this->player = $this->tester->grabEntityFromRepository(User::class, ['id' => 1]);
        $this->build = $this->tester->grabEntityFromRepository(Build::class, ['id' => 1]);
        $this->eventBattle = $this->tester->grabEntityFromRepository(EventBattle::class, ['id' => 1]);
    }

    /**
     * @return PlayerSlot
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new PlayerSlot())
            ->setPlayer($this->player)
            ->setBuild($this->build)
            ->setEventBattle($this->eventBattle)
            ->setTank(true);
    }

    /**
     * @param PlayerSlot $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertTrue($generatedEntity->isTank());
    }

    /**
     * @param PlayerSlot $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame($this->player, $generatedEntity->getPlayer());
        $this->tester->assertSame($this->build, $generatedEntity->getBuild());
        $this->tester->assertSame($this->eventBattle, $generatedEntity->getEventBattle());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 0;
    }
}
