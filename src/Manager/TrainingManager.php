<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Entity\Sport;
use App\Manager\AbstractManager;
use App\Repository\ShopCategoryRepository;
use App\Repository\ShopProductRepository;
use App\Repository\ShopProductVariantRepository;
use App\Repository\SportRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;

class TrainingManager extends AbstractManager
{
    protected $em;
    private $sportRepository;
    private $trainingRepository;

    public function __construct(EntityManagerInterface $em, TrainingRepository $trainingRepository, SportRepository $sportRepository)
    {
        parent::__construct($em);
        $this->sportRepository = $sportRepository;
        $this->trainingRepository = $trainingRepository;
    }

    public function initialise(EntityInterface $entity)
    {
        //interface
    }

    public function getSportRepository()
    {
        return $this->sportRepository;
    }

    public function getTrainingRepository()
    {
        return $this->trainingRepository;
    }
}
