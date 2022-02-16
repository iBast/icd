<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Entity\ShopCategory;
use App\Entity\ShopProduct;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => [['setShopProductSlug'], ['setShopCategorySlug']],
        ];
    }

    public function setShopCategorySlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof ShopCategory)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());
        $entity->setSlug($slug);
    }

    public function setShopProductSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof ShopProduct)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());
        $entity->setSlug($slug);
    }
}
