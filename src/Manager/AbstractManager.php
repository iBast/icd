<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager implements ManagerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(EntityInterface $entity): void
    {
        $this->initialise($entity);

        $this->em->persist($entity);
        $this->em->flush();
    }

    public function remove(EntityInterface $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function error($type, $message, $redirection, $options = null)
    {
        $error = ['type' => $type, 'message' => $message, 'redirection' => $redirection, 'options' => $options];

        return $error;
    }
}
