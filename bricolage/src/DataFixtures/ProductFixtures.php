<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Supplier;
use App\Entity\Promo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        // Define supplier data
        $suppliersData = [
            'BricoLand' => 'New York',
            'Hardouw' => 'Paris',
            'ToolAll' => 'Tokyo',
            'FixItRight' => 'Sydney',
            'HandySupplies' => 'London',
            'BuildMaster' => 'Rome',
            'ToolBox' => 'Dubai',
            'HomeBase' => 'Rio de Janeiro',
            'AceHardware' => 'Moscow',
            'ProBuilders' => 'Singapore'
        ];

        // Create and persist suppliers
        foreach ($suppliersData as $supplierName => $supplierCity) {
            $supplier = new Supplier();
            $supplier->setNameFactory($supplierName);
            $supplier->setCity($supplierCity);
            $manager->persist($supplier);
        }

        // Flush suppliers to generate IDs
        $manager->flush();

        // Define category data
        $categoriesData = [
            'Power Tools',
            'Hand Tools',
            'Garden Tools',
            'Paint & Supplies',
            'Plumbing',
            'Electrical',
            'Building Materials',
            'Hardware',
            'Safety & Security',
            'Storage & Organization'
        ];

        // Create and persist categories
        foreach ($categoriesData as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setSlug($this->slugger->slug($categoryName)->lower());
            $manager->persist($category);
        }

        // Flush categories to generate IDs
        $manager->flush();

        // Define product data with corresponding category indices
        $productsData = [
            [
                'name' => 'Power Drill',
                'color' => 'Blue',
                'designation' => 'Cordless power drill with lithium-ion battery.',
                'unit_price' => 89.99,
                'price_vat' => 108.89,
                'stock' => 15,
                'images' => ['power_drill_1.jpg', 'power_drill_2.jpg'],
                'category_index' => 0, // Power Tools
            ],
            [
                'name' => 'Circular Saw',
                'color' => 'Red',
                'designation' => 'Heavy-duty circular saw with laser guide.',
                'unit_price' => 129.99,
                'price_vat' => 157.29,
                'stock' => 10,
                'images' => ['circular_saw_1.jpg', 'circular_saw_2.jpg'],
                'category_index' => 1, // Hand Tools
            ],
            [
                'name' => 'Angle Grinder',
                'color' => 'Blue',
                'designation' => 'High-speed angle grinder for cutting and grinding.',
                'unit_price' => 59.99,
                'price_vat' => 72.59,
                'stock' => 20,
                'images' => ['angle_grinder_1.jpg', 'angle_grinder_2.jpg'],
                'category_index' => 0, // Power Tools
            ],
            [
                'name' => 'Paint Roller Set',
                'color' => 'Black',
                'designation' => 'Set of paint rollers with different textures.',
                'unit_price' => 29.99,
                'price_vat' => 36.29,
                'stock' => 30,
                'images' => ['paint_roller_1.jpg'],
                'category_index' => 3, // Paint & Supplies
            ],
            [
                'name' => 'Adjustable Wrench',
                'color' => 'Silver',
                'designation' => 'Heavy-duty adjustable wrench for various tasks.',
                'unit_price' => 14.99,
                'price_vat' => 18.14,
                'stock' => 25,
                'images' => ['adjustable_wrench_1.jpg'],
                'category_index' => 4, // Plumbing
            ],
            [
                'name' => 'Electric Screwdriver',
                'color' => 'Black/Orange',
                'designation' => 'Cordless electric screwdriver with LED light.',
                'unit_price' => 39.99,
                'price_vat' => 48.39,
                'stock' => 18,
                'images' => ['electric_screwdriver_1.jpg', 'electric_screwdriver_2.jpg', 'electric_screwdriver_3.jpg'],
                'category_index' => 5, // Electrical
            ],
            [
                'name' => 'Wood Chisel Set',
                'color' => 'Wood',
                'designation' => 'Set of wood chisels with ergonomic handles.',
                'unit_price' => 24.99,
                'price_vat' => 30.23,
                'stock' => 12,
                'images' => ['wood_chisel_1.jpg'],
                'category_index' => 6, // Building Materials
            ],
            [
                'name' => '3m Tape Measure',
                'color' => 'Yellow',
                'designation' => 'Durable measuring tape with locking mechanism.',
                'unit_price' => 9.99,
                'price_vat' => 12.09,
                'stock' => 35,
                'images' => ['measuring_tape_1.jpg'],
                'category_index' => 7, // Hardware
            ],
            [
                'name' => '50W Construction light',
                'color' => 'Yellow',
                'designation' => 'Portable, rugged, waterproof worksite floodlight with powerful illumination.',
                'unit_price' => 19.99,
                'price_vat' => 24.19,
                'stock' => 22,
                'images' => ['construction_light_1.jpg', 'construction_light_2.jpg', 'construction_light_3.jpg'],
                'category_index' => 5, // Electrical
            ],
            [
                'name' => 'Safety Goggles',
                'color' => 'Transparent',
                'designation' => 'Protective safety goggles with anti-fog lenses.',
                'unit_price' => 12.99,
                'price_vat' => 15.72,
                'stock' => 40,
                'images' => ['safety_goggles_1.jpg'],
                'category_index' => 8, // Safety & Security
            ],
            [
                'name' => 'Fiberglass nail hammer',
                'color' => 'Black/Silver',
                'designation' => 'Heavy-duty hammer for construction and demolition.',
                'unit_price' => 19.99,
                'price_vat' => 24.19,
                'stock' => 20,
                'images' => ['hammer_1.jpg', 'hammer_2.jpg', 'hammer_3.jpg'],
                'category_index' => 0, // Hand Tools
            ],
            [
                'name' => 'Pliers Set',
                'color' => 'Red',
                'designation' => 'Set of pliers with various sizes and types.',
                'unit_price' => 34.99,
                'price_vat' => 42.34,
                'stock' => 15,
                'images' => ['pliers_1.jpg', 'pliers_2.jpg'],
                'category_index' => 1, // Hand Tools
            ],
            [
                'name' => 'Cordless Drill Set',
                'color' => 'Black',
                'designation' => 'Set of cordless drills with multiple accessories.',
                'unit_price' => 79.99,
                'price_vat' => 96.79,
                'stock' => 10,
                'images' => ['cordless_drill_1.jpg', 'cordless_drill_2.jpg', 'cordless_drill_3.jpg', 'cordless_drill_4.jpg'],
                'category_index' => 2, // Power Tools
            ],
            [
                'name' => '87.3° PVC elbow for bonding, female / female, D.32',
                'color' => 'Gray',
                'designation' => 'Professional-grade tile cutter for precise tile cutting.',
                'unit_price' => 169.99,
                'price_vat' => 205.69,
                'stock' => 8,
                'images' => ['tile_cutter_1.jpg', 'tile_cutter_2.jpg'],
                'category_index' => 4, // Plumbing
            ],
            [
                'name' => 'Safety Gloves',
                'color' => 'Black',
                'designation' => 'Durable safety gloves with non-slip grip.',
                'unit_price' => 8.99,
                'price_vat' => 10.88,
                'stock' => 50,
                'images' => ['safety_gloves_1.jpg'],
                'category_index' => 8, // Safety & Security
            ],
            [
                'name' => 'Sanding Block Set',
                'color' => 'Orange',
                'designation' => 'Set of sanding blocks for smooth surface finishing.',
                'unit_price' => 12.99,
                'price_vat' => 15.72,
                'stock' => 20,
                'images' => ['sanding_block_1.jpg', 'sanding_block_2.jpg', 'sanding_block_3.jpg', 'sanding_block_4.jpg'],
                'category_index' => 6, // Building Materials
            ],
            [
                'name' => 'Wire Cutter',
                'color' => 'Red',
                'designation' => 'Heavy-duty wire cutter for cutting various wire types.',
                'unit_price' => 24.99,
                'price_vat' => 30.23,
                'stock' => 15,
                'images' => ['wire_cutter_1.jpg', 'wire_cutter_2.jpg', 'wire_cutter_3.jpg'],
                'category_index' => 7, // Hardware
            ],
            [
                'name' => 'Drill Bit Set – 1mm to 10mm – 19 different sizes and storage case',
                'color' => 'Titanium Coated',
                'designation' => 'Titanium-coated high-speed drill bits: The 170-piece Multi Drill Bit Set for DIY drilling jobs at home or in the workshop, ensuring quick and accurate results.',
                'unit_price' => 29.99,
                'price_vat' => 36.29,
                'stock' => 12,
                'images' => ['drill_bit_set_1.jpg', 'drill_bit_set_2.jpg'],
                'category_index' => 2, // Power Tools
            ],
            [
                'name' => 'Toolbox',
                'color' => 'Red',
                'designation' => 'Durable toolbox with multiple compartments and trays.',
                'unit_price' => 49.99,
                'price_vat' => 60.49,
                'stock' => 5,
                'images' => ['toolbox_1.jpg', 'toolbox_2.jpg'],
                'category_index' => 9, // Storage & Organization
            ],
            [
                'name' => 'Safety Helmet',
                'color' => 'White',
                'designation' => 'High-visibility safety helmet for construction work.',
                'unit_price' => 24.99,
                'price_vat' => 30.23,
                'stock' => 18,
                'images' => ['safety_helmet_1.jpg'],
                'category_index' => 8, // Safety & Security
            ],
        ];

        // Create and persist products
        $products = [];
        foreach ($productsData as $productData) {
            $product = new Product();
            $product->setNameProduct($productData['name']);
            $product->setColor($productData['color']);
            $product->setDesignation($productData['designation']);
            $product->setStock($productData['stock']);
            $product->setUnitPrice($productData['unit_price']);
            $product->setPriceVAT($productData['price_vat']);
            $product->setSlug($this->slugger->slug($productData['name'])->lower());

            // Generate reference in the format xxx-xxx
            $reference = strtoupper(substr($this->generateRandomString(3) . '-' . $this->generateRandomString(3), 0, 7));
            $product->setReference($reference);

            // Assign a random supplier to the product
            $randomSupplierIndex = array_rand($suppliersData);
            $supplierName = $randomSupplierIndex;
            $supplierCity = $suppliersData[$randomSupplierIndex];

            $supplierRepository = $manager->getRepository(Supplier::class);
            $supplier = $supplierRepository->findOneBy(['nameFactory' => $supplierName, 'city' => $supplierCity]);
            $product->setSupplier($supplier);

            // Assign the corresponding category to the product
            $categoryRepository = $manager->getRepository(Category::class);
            $category = $categoryRepository->findOneBy(['name' => $categoriesData[$productData['category_index']]]);
            $product->setCategory($category);

            foreach ($productData['images'] as $imageName) {
                $image = new Image();
                $image->setName($imageName);
                $image->setFilename($imageName);
                $product->addImage($image);

                $manager->persist($image);
            }

            $manager->persist($product);
            $products[] = $product;
        }

        // Define promo data
        $promosData = [
            [
                'name' => 'Summer Sale',
                'percent' => 25.0,
                'date_begin' => new \DateTimeImmutable('2024-06-01'),
                'date_end' => new \DateTimeImmutable('2024-08-31'),
                'product_indices' => [0, 2, 4, 6],
            ],
        ];

        // Create and persist promos
        foreach ($promosData as $promoDatum) {
            $promo = new Promo();
            $promo->setName($promoDatum['name']);
            $promo->setPercent($promoDatum['percent']);
            $promo->setDateBegin($promoDatum['date_begin']);
            $promo->setDateEnd($promoDatum['date_end']);

            foreach ($promoDatum['product_indices'] as $productIndex) {
                $product = $products[$productIndex];
                $promo->addProduct($product);
                $product->addPromo($promo);
            }

            $manager->persist($promo);
        }

        $manager->flush();
    }

    private function generateRandomString(int $length = 6): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}