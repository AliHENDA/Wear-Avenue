<?php

namespace App\Command;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\mySlugger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProductCategorySlugifyCommand extends Command
{
    protected static $defaultName = 'ProductCategorySlugify';
    protected static $defaultDescription = 'Add a short description for your command';

    private $entityManager;
    private $mySlugger;
    private $productRepository;
    private $categoryRepository;

    public function __construct(mySlugger $mySlugger, ManagerRegistry $doctrine, ProductRepository $productRepository, CategoryRepository $categoryRepository) {

        $this->mySlugger = $mySlugger;
        $this->entityManager = $doctrine->getManager();
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Mise à jour de nos slug dans la bdd');

        $categories = $this->categoryRepository->findAll();
        foreach($categories as $category)
        {
            $name = $category->getName();
            $category->setSlug($this->mySlugger->slugify($name));
        }

        $this->entityManager->persist($category);


        $products = $this->productRepository->findAll();
        foreach($products as $product)
        {
            $name = $product->getName();
            $product->setSlug($this->mySlugger->slugify($name));
        }

        $this->entityManager->persist($product);

        $this->entityManager->flush();

        // afficher le message stylé en cas de reussite
        $io->success('Les slugs ont bien été mis a jour !');

        return Command::SUCCESS;
    }
}