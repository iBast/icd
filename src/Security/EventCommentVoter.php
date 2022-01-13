<?php

namespace App\Security;

use App\Entity\EventComment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EventCommentVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const CREATE = 'create';

    protected function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE, self::CREATE])) {
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
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        if (in_array('ROLE_USER', $user->getRoles()) && $comment->getUser() == $user) {

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
        if (in_array('ROLE_USER', $user->getRoles())) {
            return true;
        }
        return false;
    }
}
