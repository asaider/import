<?php

namespace App\Command;

use App\Entity\Tblproductdata;
use App\Repository\TblproductdataRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ImportCommand extends Command
{
    protected static $defaultName = 'Import';

    private $em;

    /**
     * @var TblproductdataRepository
     */
    private $repository;


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
        $this->repository = $this->em->getRepository(Tblproductdata::class);
    }

    protected function configure()
    {
        $this
            ->setDescription('Import data from csv file')
            ->addArgument('mode', InputArgument::OPTIONAL, 'Test mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        try {
            $reader = Reader::createFromPath('%kernel.root_dir%/../src/Data/stock.csv');
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            exit();
        }
        $results = $reader->setHeaderOffset(0);

        $testMode = $this->isItTestMode($input);
        $errorList = [];
        $addCount = 0;
        if ($testMode)
            $output->writeln('<info>Test mode:</info>');
        $output->writeln('<info>All data:' . $reader->count() . '</info>');
        foreach ($results as $result) {
            $violations = $this->validate($result);

            if ($violations) {
                $errorList[] = $violations;
                continue;
            }
            if (!$testMode) {
                $this->save($result);
                $addCount += 1;
            }
        }
        $this->createErrorsReport($errorList,$output);
        $output->writeln('<info>Added/Update rows:' . $addCount . '</info>');
        if (!$testMode)
            $output->writeln('<info>error rows:' . ($reader->count()-$addCount) . '</info>');
    }


    private function isItTestMode(InputInterface $input)
    {
        $arg1 = $input->getArgument('mode');

        return ($arg1) && ($arg1 == 'test');
    }

    public function validate(array $input): array
    {
        $error = [];
        $validator = Validation::createValidator();
        $constraint = $this->getConstraint();

        $violations = $validator->validate($input, $constraint);
        $templateMessage = 'Product ' . $input['Product Code'];

        if (($input['Cost in GBP'] < 5) && ($input['Stock'] < 10)) {
            $error[] = $templateMessage . ' does not fit the conditions: the cost is less than 5, and the amount is less than 10';
        }


        foreach ($violations as $violation) {
            $error[] = $templateMessage . ' property ' . $violation->getPropertyPath() . $violation->getMessage();
        }

        return $error;
    }

    private function getConstraint(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'fields' => [
                    'Product Code' => [
                        new Assert\NotBlank(),
                        new Assert\Required()

                    ],
                    'Product Name' => [
                        new Assert\NotBlank(),
                        new Assert\Required()
                    ],
                    'Product Description' => [
                    ],
                    'Stock' => [
                        new Assert\Required(),
                        new Assert\Type('numeric'),
                        new Assert\NotBlank(),
                        new Assert\GreaterThanOrEqual(0)
                    ],
                    'Cost in GBP' => [
                        new Assert\Required(),
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
    }


    private function save(array $data):void
    {

        $product = $this->getProduct($data['Product Code']);
        $product->setStrproductcode($data['Product Code']);
        $product->setStrproductname($data['Product Name']);
        $product->setStrproductdesc($data['Product Description']);
        $product->setStock($data['Stock']);
        $product->setPrice($data['Cost in GBP']);
        if ($data['Discontinued'] == 'yes') {
            $product->setDtmdiscontinued(new \DateTime());
        }
        $product->setStmtimestamp(new \DateTime());

        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (\Exception $e) {
            echo $e->getMessage();

        }
    }

    private function getProduct($productCode): Tblproductdata
    {
        $product = $this->repository->findOneByProductCode($productCode);

        return is_null($product) ? $this->createNewProduct() : $product;

    }

    private function createNewProduct(){
        $product = new Tblproductdata();
        $product->setDtmadded(new \DateTime());
        return $product;
    }

    private function createErrorsReport(array $errors,OutputInterface $output)
    {
        foreach ($errors as $error) {
            $output->writeln($error);
        }
    }
}

