<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\Member;
use App\Entity\EntityInterface;
use App\Entity\EventComment;
use App\Entity\User;
use App\Repository\MemberRepository;
use App\Repository\RaceRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RaceManager extends AbstractManager
{
    protected $em;
    protected $raceRepository;
    protected $slugger;
    protected $memberRepository;

    public function __construct(EntityManagerInterface $em, RaceRepository $raceRepository, SluggerInterface $slugger, MemberRepository $memberRepository)
    {
        parent::__construct($em);
        $this->raceRepository = $raceRepository;
        $this->slugger = $slugger;
        $this->memberRepository = $memberRepository;
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

    public function deleteComment(EventComment $comment)
    {
        $this->remove($comment);
    }

    public function add(Race $race)
    {
        $race->setSlug($this->slugger->slug($race->getName()));
        $this->save($race);
    }

    public function getMemberRepository()
    {
        return $this->memberRepository;
    }

    public function getRaceRepository()
    {
        return $this->raceRepository;
    }
}
