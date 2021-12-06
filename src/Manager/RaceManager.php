<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\Member;
use App\Entity\EntityInterface;
use App\Entity\EventComment;
use App\Entity\User;
use App\Repository\RaceRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class RaceManager extends AbstractManager
{
    protected $em;
    protected $raceRepository;

    public function __construct(EntityManagerInterface $em, RaceRepository $raceRepository)
    {
        parent::__construct($em);
        $this->raceRepository = $raceRepository;
    }
    public function initialise(EntityInterface $entity)
    {
        //interface
    }

    public function participate(Member $member, Race $race)
    {
        if ($member->getRaces()->contains($race)) {
            $member->removeRace($race);
            $this->save($member);
        } else {
            $member->addRace($race);
            $this->save($member);
        }
    }

    public function comment(EventComment $comment, Race $event, User $user)
    {
        $comment->setCreatedAt(new DateTimeImmutable())
            ->setEvent($event)
            ->setUser($user)
            ->setIsPinned(false);
        $this->save($comment);
    }

    public function changeState(EventComment $comment)
    {
        $comment->setIsPinned(!$comment->getIsPinned());
        $this->save($comment);
    }
}
