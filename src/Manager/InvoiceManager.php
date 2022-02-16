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
use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Entity\Member;
use App\Repository\InvoiceRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceManager extends AbstractManager
{
    protected $invoiceRepository;
    protected $em;

    public function __construct(InvoiceRepository $invoiceRepository, EntityManagerInterface $em)
    {
        $this->invoiceRepository = $invoiceRepository;
        parent::__construct($em);
    }

    public function initialise(EntityInterface $entity)
    {
        //interface
    }

    public function create($date, $name, $adress, $postCode, $city)
    {
        $invoie = new Invoice();
        $invoie->setDate($date)->setToName($name)->setToAdress($adress)->setToPostCode($postCode)->setToCity($city);
    }

    public function invoiceLineNewInvoice(Member $member, $wording, $qty, $unitPrice)
    {
        $invoice = new Invoice();
        $invoice->setDate(new DateTimeImmutable())->setToName($member->getFirstName().' '.$member->getLastName())->setToAdress($member->getAdress())->setToPostCode($member->getPostCode())->setToCity($member->getCity());
        $line = new InvoiceLine();
        $line->setInvoice($invoice)->setDate(new DateTime())->setWording($wording)->setQty($qty)->setUnitPrice($unitPrice);
        $invoice->setTotalAmount($line->getQty() * $line->getUnitPrice());
        $this->save($invoice);
        $this->save($line);
    }
}
