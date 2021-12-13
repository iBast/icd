<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use App\Entity\EventComment;
use App\Security\EventCommentVoter;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Security\Core\Security;

class EventCommentVoterExtension extends AbstractExtension
{
    /**
     * @var EventCommentVoter
     */
    private $eventCommentVoter;
    protected $security;

    public function __construct(
        Security $security,
        EventCommentVoter $eventCommentVoter

    ) {
        $this->security = $security;
        $this->eventCommentVoter = $eventCommentVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('commentCanRead', [$this, 'commentCanRead']),
            new TwigFilter('commentCanUpdate', [$this, 'commentCanUpdate']),
            new TwigFilter('commentCanDelete', [$this, 'commentCanDelete']),
        ];
    }



    public function commentCanRead(EventComment $item)
    {
        return $this->eventCommentVoter->canRead($item, $this->security->getUser());
    }

    public function commentCanUpdate(EventComment $item)
    {
        return $this->eventCommentVoter->canUpdate($item, $this->security->getUser());
    }

    public function commentCanDelete(EventComment $item)
    {
        return $this->eventCommentVoter->canDelete($item, $this->security->getUser());
    }
}
