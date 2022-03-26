<?php

namespace App\EventListener;

use App\Entity\Conference;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ConferenceEntityListener
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function prePersist(Conference $conference, LifecycleEventArgs $event)
    {
        $conference->computerSlug($this->slugger);
    }

    public function preUpdate(Conference $conference, LifecycleEventArgs $event)
    {
        $conference->computerSlug($this->slugger);
    }
}
