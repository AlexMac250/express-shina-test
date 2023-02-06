<?php

namespace App\DataFixtures;

use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\ProductModel;
use App\Entity\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');

        /** @var Manufacturer[] $manufacturers */
        $manufacturers = [];

        for ($i = 1; $i <= 5; ++$i) {
            $manufacturer = new Manufacturer();

            $manufacturer->setName($faker->company());

            $manufacturers[] = $manufacturer;

            $manager->persist($manufacturer);
        }

        /** @var ProductType[] $productTypes */
        $productTypes = [];

        for ($i = 1; $i <= 5; ++$i) {
            $productType = new ProductType();

            $productType->setName($faker->words(asText: true));

            $productTypes[] = $productType;

            $manager->persist($productType);
        }

        /** @var ProductModel[] $productModels */
        $productModels = [];

        foreach ($manufacturers as $manufacturer) {
            $productModel = new ProductModel();

            $productType = $faker->randomElement($productTypes);

            $productModel->setName("Model {$faker->word()}");

            $productType->addProductModel($productModel);
            $manufacturer->addProductModel($productModel);

            $productModels[] = $productModel;

            $manager->persist($productModel);
        }

        foreach ($productModels as $productModel) {
            for ($i = 1; $i <= 10; ++$i) {
                $product = new Product();

                $modelName = $productModel->getName();
                $product->setName("{$modelName} - {$faker->word()} {$i}");
                $product->setPrice($faker->randomFloat(2, 100, 5000));

                $productModel->addProduct($product);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
