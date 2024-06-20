<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Encounter;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\Instance;
use Override;

final class EncounterTest extends EntityTest
{
    private Instance $instance;
    private EventBattle $eventBattle;

    public function _before(): void
    {
        $this->instance = $this->tester->grabEntityFromRepository(Instance::class, ['id' => 1]);
        $this->eventBattle = $this->tester->grabEntityFromRepository(EventBattle::class, ['id' => 1]);
    }

    /**
     * @return Encounter
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new Encounter())
            ->setName('testEncounter')
            ->setInstance($this->instance)
            ->addEventBattle($this->eventBattle)
        ;
    }

    /**
     * @param Encounter $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testEncounter', $generatedEntity->getName());
    }

    /**
     * @param Encounter $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame($this->instance, $generatedEntity->getInstance());
        $this->tester->assertContains($this->eventBattle, $generatedEntity->getEventBattles());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 1;
    }
}
