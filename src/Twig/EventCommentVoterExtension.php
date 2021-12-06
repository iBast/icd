<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use App\Entity\EventComment;
use App\Security\EventCommentVoter;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class EventCommentVoterExtension extends AbstractExtension
{
    /**
     * @var EventCommentVoter
     */
    private $voter;

    private $security;


    public function __construct(
        Security $security,
        EventCommentVoter $voter

    ) {
        $this->security = $security;
        $this->voter = $voter;
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
        return $this->voter->canRead($item, $this->user);
    }

    public function commentCanUpdate(EventComment $item)
    {
        return $this->voter->canUpdate($item, $this->user);
    }

    public function commentCanDelete(EventComment $item)
    {
        return $this->voter->canDelete($item, $this->security->getUser());
    }
}
