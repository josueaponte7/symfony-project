<?php

namespace App\EventSubscriber;

use App\Repository\ConferenceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private Environment $twig, private ConferenceRepository $conferenceRepository)
    {
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $conferences = $this->conferenceRepository->findBy([], ['year' => 'DESC', 'city' => 'ASC']);
        $this->twig->addGlobal('conferences', $conferences);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
