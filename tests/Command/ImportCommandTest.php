<?php
namespace App\tests\Command;

use app\Command\ImportCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Doctrine\ORM\EntityManagerInterface;

class ImportCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $application->add(new ImportCommand($entityManager));

        $command = $application->find('Import');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
            // pass arguments to the helper
            'mode' => 'test',
        ));
        $output = $commandTester->getDisplay();
        $this->assertContains('Test mode', $output);
    }
}