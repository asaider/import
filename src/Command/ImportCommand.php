<?php

namespace App\Command;

use App\Entity\Tblproductdata;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;


use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class ImportCommand extends Command
{
    protected static $defaultName = 'Import';
    private $em;

    /**
     * CsvImportCommand constructor.
     *
     * @param EntityManagerInterface $em
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $reader = Reader::createFromPath('%kernel.root_dir%/../src/Data/stock.csv');
        $results = $reader->setHeaderOffset(0);

        $arg1 = $input->getArgument('arg1');

        if (($arg1) && ($arg1 == 'test')) {

            $io->note(sprintf('You passed an argument: %s', $arg1));
            $all = 0;
            $mas_error = [];
            foreach ($results as $result) {
                $error = $this->get_validation($result);
                if (count($error) > 0)
                    $mas_error[] = $error;
                $all++;
            }
            echo "Всех записей в файле:" . $all . "\n";
            echo "количество записей,готовых для импорта:" . ($all - count($mas_error)) . "\n";
            echo "количество записей,не прошедших валидацию:" . count($mas_error) . "\n";
            if (count($mas_error) > 0) {
                echo "Report" . "\n";
                foreach ($mas_error as $key => $item) {
                    echo "Product code:" . $item[0]['product_code'] . " ";
                    echo "Property:" . $item[0]['property'] . " ";
                    echo "Message:" . $item[0]['message'] . "\n";
                }
            }
        } else {
            $this->insert($results);
        }
        if ($input->getOption('option1')) {
            // ...
        }
        //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }

    protected function get_validation($result)
    {
        $data = [];

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
                        //new Assert\NotBlank(),
                    ],
                    'Stock' => [
                        new Assert\Type('numeric'),
                        new Assert\NotBlank(),
                        new Assert\GreaterThanOrEqual(0)
                    ],
                    'Cost in GBP' => [
                        new Assert\NotBlank(),
                        new Assert\Type('numeric'),
                        new Assert\GreaterThanOrEqual(0),
                        new Assert\LessThan(1000)
                    ],
                    'Discontinued' => [
                        new Assert\Choice(["yes", ""]),
                    ]
                ]
            ]
        );

        $violations = $validator->validate($result, $constraint);

        if (($result['Cost in GBP'] < 5) && ($result['Stock'] < 10))
            $data[] = [
                'product_code' => $result['Product Code'],
                'property' => "",
                'message' => "не подходит под условия:стоимость меньше 5,и количество меньше 10"
            ];
        if (0 !== count($violations)) {
            // есть ошибки, теперь вы можете их отобразить
            foreach ($violations as $violation) {
                $data[] = [
                    'product_code' => $result['Product Code'],
                    'property' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage()
                ];
                //echo $violation->getMessage()."\n";
            }
        }

        return $data;
    }

    protected function insert($data)
    {
        echo "hi" . "\n";
        foreach ($data as $row) {

            $product = new Tblproductdata();

            $product->setStrproductcode($row['Product Code']);
            $product->setStrproductname("gfhfh");
            $product->setStrproductdesc("fgfh");
            $product->setStock(5);
            $product->setPrice("dfgd");
            $product->setDtmadded(new \DateTime());
            $product->setDtmdiscontinued(new \DateTime());
            $product->setStmtimestamp(new \DateTime());

            $this->em->persist($product);
        }
        $this->em->flush();

    }
}
