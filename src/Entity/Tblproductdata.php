<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tblproductdata
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity(repositoryClass="App\Repository\TblproductdataRepository")
 *
 */
class Tblproductdata
{

    /**
     * @var int
     *
     * @ORM\Column(name="intProductDataId", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $intproductdataid;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     */
    private $strproductname;

    /**
     * @var string
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private $strproductdesc;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     */
    private $strproductcode;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer", options={"unsigned"=true})
     */
    private $stock;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmadded;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmdiscontinued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $stmtimestamp = 'CURRENT_TIMESTAMP';

    /**
     * @return int
     */
    public function getIntproductdataid(): int
    {
        return $this->intproductdataid;
    }

    /**
     * @param int $intproductdataid
     */
    public function setIntproductdataid(int $intproductdataid): void
    {
        $this->intproductdataid = $intproductdataid;
    }

    /**
     * @return string
     */
    public function getStrproductname(): string
    {
        return $this->strproductname;
    }

    /**
     * @param string $strproductname
     */
    public function setStrproductname(string $strproductname): void
    {
        $this->strproductname = $strproductname;
    }

    /**
     * @return string
     */
    public function getStrproductdesc(): string
    {
        return $this->strproductdesc;
    }

    /**
     * @param string $strproductdesc
     */
    public function setStrproductdesc(string $strproductdesc): void
    {
        $this->strproductdesc = $strproductdesc;
    }

    /**
     * @return string
     */
    public function getStrproductcode(): string
    {
        return $this->strproductcode;
    }

    /**
     * @param string $strproductcode
     */
    public function setStrproductcode(string $strproductcode): void
    {

        $this->strproductcode = $strproductcode;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(string $stock): void    {


        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param decimal $price
     */
    public function setPrice(string $price): void
    {

        $this->price = $price;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtmadded(): ?\DateTime
    {
        return $this->dtmadded;
    }

    /**
     * @param \DateTime|null $dtmadded
     */
    public function setDtmadded(?\DateTime $dtmadded): void
    {
        $this->dtmadded = $dtmadded;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtmdiscontinued(): ?\DateTime
    {
        return $this->dtmdiscontinued;
    }

    /**
     * @param \DateTime|null $dtmdiscontinued
     */
    public function setDtmdiscontinued(?\DateTime $dtmdiscontinued): void
    {
        $this->dtmdiscontinued = $dtmdiscontinued;
    }

    /**
     * @return \DateTime
     */
    public function getStmtimestamp(): \DateTime
    {
        return $this->stmtimestamp;
    }

    /**
     * @param \DateTime $stmtimestamp
     */
    public function setStmtimestamp(\DateTime $stmtimestamp): void
    {
        $this->stmtimestamp = $stmtimestamp;
    }


}
