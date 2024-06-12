<?php

namespace App\Tests\Unit\Entity;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\GuildEventRelation\EventBattle;
use App\Enum\GuildEventStatusEnum;
use App\Enum\InstanceTypeEnum;
use DateTime;
use Override;

final class GuildEventTest extends EntityTest
{
    private EventBattle $eventBattle;
    private EventAttendance $eventAttendance;

    public function _before(): void
    {
        $this->eventBattle = $this->tester->grabEntityFromRepository(EventBattle::class, ['id' => 1]);
        $this->eventAttendance = $this->tester->grabEntityFromRepository(EventAttendance::class, ['id' => 1]);
    }

    /**
     * @return GuildEvent
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new GuildEvent())
            ->setTitle('testEvent')
            ->setStart((new DateTime())->setTime(23, 59, 58))
            ->setEnd((new DateTime())->setTime(23, 59, 59))
            ->setType(InstanceTypeEnum::STRIKE)
            ->setStatus(GuildEventStatusEnum::CANCELLED)
            ->setGuildRaid(false)
            ->setColor('#000000')
            ->setOldMembersAllowed(true)
            ->setMembersManageEvent(true)
            ->addEventBattle($this->eventBattle)
            ->addEventAttendance($this->eventAttendance);
    }

    /**
     * @param GuildEvent $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testEvent', $generatedEntity->getTitle());
        $this->tester->assertEquals((new DateTime())->setTime(23, 59, 58), $generatedEntity->getStart());
        $this->tester->assertEquals((new DateTime())->setTime(23, 59, 59), $generatedEntity->getEnd());
        $this->tester->assertSame(InstanceTypeEnum::STRIKE, $generatedEntity->getType());
        $this->tester->assertSame(GuildEventStatusEnum::CANCELLED, $generatedEntity->getStatus());
        $this->tester->assertFalse($generatedEntity->isGuildRaid());
        $this->tester->assertSame('#000000', $generatedEntity->getColor());
        $this->tester->assertTrue($generatedEntity->isOldMembersAllowed());
        $this->tester->assertTrue($generatedEntity->canMembersManageEvent());
    }

    /**
     * @param GuildEvent $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertContains($this->eventBattle, $generatedEntity->getEventBattles());
        $this->tester->assertContains($this->eventAttendance, $generatedEntity->getEventAttendances());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 1;
    }
}
