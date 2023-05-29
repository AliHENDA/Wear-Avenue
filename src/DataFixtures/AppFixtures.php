<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\SapesProvider;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        // On fournit au faker un Provider

        $faker->addProvider(new SapesProvider());

        // Categories

        // Array for our categories
        $categoriesList = [];

        for ($c = 1 ; $c <= 12; $c++) {
            $category = new Category();
            $category->setName($faker->unique()->productCategory());
            $category->setPicture("{{ asset('assets/img/women-03.jpg') }}");
            $category->setSubtitle('Lorem, ipsum dolor sit amet consecteturs.');
            $category->setSlug($this->slugger->slug($category->getName())->lower());

            $categoriesList[] = $category;
            $manager->persist($category);
        }
            $manager->flush();

    }
}