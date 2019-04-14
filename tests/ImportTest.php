<?php

namespace App\tests;

use App\Command\ImportCommand;
use PHPUnit\Framework\TestCase;
use League\Csv\Reader;
use Doctrine\ORM\EntityManagerInterface;

class ImportTest extends TestCase
{
    public function testFile()
    {
        $this->assertFileExists('src/Data/stock.csv');
    }

    public function testsCsvColumns()
    {
        $reader = Reader::createFromPath('src/Data/stock.csv');
        $reader->setHeaderOffset(0);
        $header = $reader->getHeader();
        $name_column=['Product Code','Product Name','Product Description','Stock','Cost in GBP','Discontinued'];
        ksort($header);
        ksort($name_column);
        $this->assertEquals($header,$name_column);
    }


    public function testValidation()
    {
        $result['Product Code']="P123";
        $result['Product Name']="Iphone  xs";
        $result['Product Description']="mobile";
        $result['Stock']="1";
        $result['Cost in GBP']="900";
        $result['Discontinued']="";
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $object=new ImportCommand($entityManager);
        $test_error=$object->validate($result);
        $this->assertEquals(0,count($test_error));
    }
}