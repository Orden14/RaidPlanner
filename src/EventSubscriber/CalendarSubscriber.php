<?php

namespace App\EventSubscriber;

use App\Entity\GuildEvent;
use App\Enum\GuildEventStatusEnum;
use App\Repository\GuildEventRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $router,
        private GuildEventRepository  $guildEventRepository
    ) {}

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    final public function onCalendarSetData(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();

        /** @var GuildEvent[] $guildEvents */
        $guildEvents = $this->guildEventRepository
            ->createQueryBuilder('guild_event')
            ->where('guild_event.start BETWEEN :start and :end OR guild_event.end BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($guildEvents as $guildEvent) {
            $title = $guildEvent->getStatus() === GuildEventStatusEnum::OPEN
                ? $guildEvent->getTitle()
                : "{$guildEvent->getTitle()} ({$guildEvent->getStatus()->value})";

            $event = new Event(
                $title,
                $guildEvent->getStart(),
                $guildEvent->getEnd(),
            );

            $backgroundColor = $guildEvent->getStatus() === GuildEventStatusEnum::OPEN
                ? $guildEvent->getColor()
                : '#701010';

            $event->setOptions([
                'backgroundColor' => $backgroundColor,
                'borderColor' => $backgroundColor,
                'description' => 'wiwiwi',
            ]);

            $event->addOption(
                'url',
                $this->router->generate('guild_event_show', [
                    'id' => $guildEvent->getId(),
                ])
            );

            $calendar->addEvent($event);
        }
    }
}
