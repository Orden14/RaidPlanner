<?php

namespace App\EventSubscriber;

use App\Entity\GuildEvent;
use App\Repository\GuildEventRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $router,
        private GuildEventRepository $guildEventRepository
    )
    {}

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        /** @var GuildEvent[] $guildEvents */
        $guildEvents = $this->guildEventRepository
            ->createQueryBuilder('guild_event')
            ->where('guild_event.start BETWEEN :start and :end OR guild_event.end BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($guildEvents as $guildEvent) {
            // this create the events with your data (here booking data) to fill calendar
            $event = new Event(
                $guildEvent->getTitle(),
                $guildEvent->getStart(),
                $guildEvent->getEnd()
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $event->setOptions([
                'backgroundColor' => $guildEvent->getColor(),
                'borderColor' => $guildEvent->getColor(),
            ]);
            $event->addOption(
                'url',
                $this->router->generate('guild_event_show', [
                    'id' => $guildEvent->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($event);
        }
    }
}
