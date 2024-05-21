<?php

namespace App\Tests\Unit\Entity;

use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\RolesEnum;
use DateTime;
use Override;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserTest extends EntityTest
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private EventAttendance $eventAttendance;
    private PlayerSlot $playerSlot;


    public function _before(): void
    {
        $this->userPasswordHasher = $this->tester->grabService(UserPasswordHasherInterface::class);
        $this->eventAttendance = $this->tester->grabEntityFromRepository(EventAttendance::class, ['id' => 1]);
        $this->playerSlot = $this->tester->grabEntityFromRepository(PlayerSlot::class, ['id' => 1]);
    }

    /**
     * @return User
     */
    #[Override]
    public function _generateEntity(): object
    {
        $user = new User();

        $user->setUsername('testUsername')
            ->setProfilePicture('profilepicture.jpg')
            ->setRole(RolesEnum::MEMBER)
            ->setPassword($this->userPasswordHasher->hashPassword($user, 'testPassword123'))
            ->setJoinedAt((new DateTime())->setTime(23, 59, 59))
            ->addEventAttendance($this->eventAttendance)
            ->addPlayerSlot($this->playerSlot);

        return $user;
    }

    /**
     * @param User $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testUsername', $generatedEntity->getUsername());
        $this->tester->assertSame('profilepicture.jpg', $generatedEntity->getProfilePicture());
        $this->tester->assertSame(RolesEnum::MEMBER, $generatedEntity->getRole());
        $this->tester->assertEquals((new DateTime())->setTime(23, 59, 59), $generatedEntity->getJoinedAt());
        $this->tester->assertTrue($this->userPasswordHasher->isPasswordValid($generatedEntity, 'testPassword123'));
    }

    /**
     * @param User $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertContains($this->eventAttendance, $generatedEntity->getEventAttendances());
        $this->tester->assertContains($this->playerSlot, $generatedEntity->getPlayerSlots());
    }

    #[Override]
    public function _expectedCountAssertionErrors(): int
    {
        return 1;
    }
}
