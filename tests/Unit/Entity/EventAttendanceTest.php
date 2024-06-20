<?php

namespace App\Tests\Unit\Entity;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use Override;

final class EventAttendanceTest extends EntityTest
{
    private User $user;
    private GuildEvent $guildEvent;

    public function _before(): void
    {
        $this->user = $this->tester->grabEntityFromRepository(User::class, ['id' => 1]);
        $this->guildEvent = $this->tester->grabEntityFromRepository(GuildEvent::class, ['id' => 1]);
    }

    /**
     * @return EventAttendance
     */
    #[Override]
    public function _generateEntity(): object
    {
        $eventAttendance = new EventAttendance();

        $eventAttendance->setUser($this->user)
            ->setGuildEvent($this->guildEvent)
            ->setType(AttendanceTypeEnum::ABSENT)
            ->setEventOwner(false)
            ->setComment('testComment')
        ;

        return $eventAttendance;
    }

    /**
     * @param EventAttendance $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame(AttendanceTypeEnum::ABSENT, $generatedEntity->getType());
        $this->tester->assertFalse($generatedEntity->isEventOwner());
        $this->tester->assertSame('testComment', $generatedEntity->getComment());
    }

    /**
     * @param EventAttendance $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame($this->user, $generatedEntity->getUser());
        $this->tester->assertSame($this->guildEvent, $generatedEntity->getGuildEvent());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 0;
    }
}
