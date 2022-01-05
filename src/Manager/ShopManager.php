<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Manager\AbstractManager;
use App\Repository\ShopCategoryRepository;
use App\Repository\ShopProductRepository;
use App\Repository\ShopProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;

class ShopManager extends AbstractManager
{
    protected $em;
    private $categoryRepository;
    private $productRepository;
    private $productVariantRepository;

    public function __construct(EntityManagerInterface $em, ShopCategoryRepository $categoryRepository, ShopProductRepository $productRepository, ShopProductVariantRepository $productVariantRepository)
    {
        parent::__construct($em);
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    public function initialise(EntityInterface $entity)
    {
        //interface
    }

    public function getCategoryRepository()
    {
        return $this->categoryRepository;
    }

    public function getProductRepository()
    {
        return $this->productRepository;
    }

    public function getProductVariantRepository()
    {
        return $this->productVariantRepository;
    }
}
