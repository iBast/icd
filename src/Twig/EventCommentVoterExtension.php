<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use App\Entity\EventComment;
use App\Security\EventCommentVoter;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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

    public function getFilters(): array
    {
        return [
            new TwigFilter('commentCanRead', [$this, 'commentCanRead']),
            new TwigFilter('commentCanUpdate', [$this, 'commentCanUpdate']),
            new TwigFilter('commentCanDelete', [$this, 'commentCanDelete']),
        ];
    }

    public function commentCanRead(EventComment $item): bool
    {
        return $this->eventCommentVoter->canRead($item, $this->security->getUser());
    }

    public function commentCanUpdate(EventComment $item): bool
    {
        return $this->eventCommentVoter->canUpdate($item, $this->security->getUser());
    }

    public function commentCanDelete(EventComment $item): bool
    {
        return $this->eventCommentVoter->canDelete($item, $this->security->getUser());
    }
}
