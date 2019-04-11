<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tblproductdata
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 */
class Tblproductdata
{
    /**
     * @var int
     *
     * @ORM\Column(name="intProductDataId", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
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
     *
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


}
