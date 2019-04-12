<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;


use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
class ImportCommand extends Command
{
    protected static $defaultName = 'Import';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $reader=Reader::createFromPath('%kernel.root_dir%/../src/Data/stock.csv');
        $results=$reader->setHeaderOffset(0);
        /*$i=0;
        foreach ($results as $result) {
            $i++;
            echo $result['Product Code'].' '.$result['Product Name'].' '.$result['Product Description'].' '
                .$result['Stock'].' '.$result['Cost in GBP'].' '.$result['Discontinued'].''."\n";
        }
        echo $i;*/

        $arg1 = $input->getArgument('arg1');

        if (($arg1) && ($arg1=='test')) {

            $io->note(sprintf('You passed an argument: %s', $arg1));
            $cout_error=0;
            $all=0;
            foreach ($results as $result) {
                $all++;
                $cout_error+=$this->test($result);
            }
            echo "Всех записей в файле:".$all."\n";
            echo "количество строк,не прошедших валидацию:".$cout_error."\n";
        }

        if ($input->getOption('option1')) {
            // ...
        }

        //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
    protected function test($result){
        //var_dump($result);die();
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(
            [
                'fields' => [
                    'Product Code' => [
                        new Assert\NotBlank()
                    ],
                    'Product Name' => [
                        new Assert\NotBlank()
                    ],
                    'Product Description' => [
                    ],
                    'Stock' => [
                        //new Assert\Type('integer'),
                    ],
                    'Cost in GBP' => [
                        new Assert\NotBlank(),
                        //new Assert\Type('float'),
                    ],
                    'Discontinued' => [
                        //new Assert\EqualTo("yes",""),
                    ]
                ]
            ]
        );
        $violations = $validator->validate($result, $constraint);
        /*$violations = $validator->validate($result['Cost in GBP'], array(
            new NotBlank(),
        ));*/

        if (0 !== count($violations)) {
            // есть ошибки, теперь вы можете их отобразить
            foreach ($violations as $violation) {
                echo $violation->getMessage()."\n";
            }
        }
        return count($violations);
    }
}
