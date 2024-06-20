<?php

namespace App\DataFixtures;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Repository\BuildRepository;
use App\Repository\EncounterRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

final class GuildEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly BuildRepository $buildRepository,
        private readonly EncounterRepository $encounterRepository,
    ) {
    }

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $this->createGuildEvents($manager);
    }

    /**
     * @throws RandomException
     */
    private function createGuildEvents(ObjectManager $manager): void
    {
        $lastMonday = (new DateTime())->modify('monday last week');

        $types = InstanceTypeEnum::cases();

        for ($j = 0; $j < 20; $j++) {
            $date = $lastMonday->modify('+ 1 day')->setTime(random_int(9, 21), random_int(0, 59));

            $isGuildRaid = (bool) random_int(0, 1);

            $guildEvent = (new GuildEvent())
                ->setTitle('Test event ' . $j)
                ->setStart(clone $date)
                ->setEnd((clone $date)->modify('+2 hours'))
                ->setType($types[array_rand($types)])
                ->setGuildRaid($isGuildRaid)
                ->setOldMembersAllowed(!$isGuildRaid && random_int(0, 1))
                ->setMembersManageEvent(!$isGuildRaid && random_int(0, 1))
            ;
            $manager->persist($guildEvent);

            $participants = $this->setParticipants($guildEvent, $manager);

            $this->setBattles($guildEvent, $participants, $manager);
        }

        $manager->flush();
    }

    /**
     * @return User[]
     *
     * @throws RandomException
     */
    private function setParticipants(GuildEvent $guildEvent, ObjectManager $manager): array
    {
        $users = [];
        $totalParticipants = random_int(3, InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType()));
        for ($i = 0; $i < $totalParticipants; $i++) {
            /** @var User $user */
            $user = $this->userRepository->find($i + 1);
            $eventAttendance = (new EventAttendance())
                ->setGuildEvent($guildEvent)
                ->setUser($user)
            ;

            if ($i < $totalParticipants - 2) {
                $eventAttendance->setType(AttendanceTypeEnum::PLAYER);
            } elseif ($i < $totalParticipants - 1) {
                $eventAttendance->setType(AttendanceTypeEnum::BACKUP);
            } else {
                $eventAttendance->setType(AttendanceTypeEnum::ABSENT);
            }

            $manager->persist($eventAttendance);

            $users[] = $user;
        }

        return $users;
    }

    /**
     * @param User[] $participants
     *
     * @throws RandomException
     */
    private function setBattles(GuildEvent $guildEvent, array $participants, ObjectManager $manager): void
    {
        $encounters = $this->encounterRepository->createQueryBuilder('e')
            ->innerJoin('e.instance', 'i')
            ->where('i.type = :type')
            ->setParameter('type', $guildEvent->getType()->value)
            ->getQuery()
            ->getResult()
        ;

        $totalBattles = random_int(1, 6);
        for ($y = 0; $y < $totalBattles; $y++) {
            $eventBattle = (new EventBattle())
                ->setGuildEvent($guildEvent)
                ->setEncounter($encounters[array_rand($encounters)])
            ;

            $manager->persist($eventBattle);

            for ($i = 0; $i < InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType()); $i++) {
                $eventSlot = (new PlayerSlot())->setEventBattle($eventBattle);

                $eventSlot->setBuild($this->buildRepository->find($i + 1));

                if ($i === 0) {
                    $eventSlot->setTank(true);
                }

                if ($i < count($participants) - 1) {
                    $eventSlot->setPlayer($participants[$i]);
                }

                $manager->persist($eventSlot);
            }
        }
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BuildFixtures::class,
            EncounterFixtures::class,
        ];
    }
}
