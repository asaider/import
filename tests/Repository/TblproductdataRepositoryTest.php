<?php

namespace App\tests\Repository;

use App\Entity\Tblproductdata;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TblproductdataRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindOneByProductCode()
    {
        $products = $this->entityManager
            ->getRepository(Tblproductdata::class)
            ->findOneByProductCode('P0009');
        $this->assertNotEquals(null,$products);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}