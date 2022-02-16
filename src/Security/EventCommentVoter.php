<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use App\Entity\EventComment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EventCommentVoter extends Voter
{
    public const READ = 'read';
    public const UPDATE = 'update';
    public const DELETE = 'delete';
    public const CREATE = 'create';

    protected function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!\in_array($attribute, [self::READ, self::UPDATE, self::DELETE, self::CREATE], true)) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof EventComment) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var EventComment $comment */
        $comment = $subject;
        switch ($attribute) {
            case self::READ:
                return $this->canRead($comment, $user);
            case self::UPDATE:
                return $this->canUpdate($comment, $user);
            case self::DELETE:
                return $this->canDelete($comment, $user);
            case self::CREATE:
                return $this->canCreate($user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(EventComment $comment, User $user): bool
    {
        return true;
    }

    public function canUpdate(EventComment $comment, User $user): bool
    {
        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        if (\in_array('ROLE_USER', $user->getRoles(), true) && $comment->getUser() === $user) {
            return true;
        }

        return false;
    }

    public function canDelete(EventComment $comment, User $user): bool
    {
        return $this->canUpdate($comment, $user);
    }

    public function canCreate(User $user): bool
    {
        if (\in_array('ROLE_USER', $user->getRoles(), true)) {
            return true;
        }

        return false;
    }
}
