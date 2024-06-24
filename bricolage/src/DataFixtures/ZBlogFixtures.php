<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Media;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ZBlogFixtures extends Fixture
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $handymanUser = $manager->getRepository(User::class)->findOneBy(['username' => 'handyman']);

        $media1 = new Media();
        $media1->setName('Driftwood Shelf');
        $media1->setFilename('driftwood_shelf.jpg');
        $media1->setAltText('driftwood_shelf');
        $manager->persist($media1);

        $media2 = new Media();
        $media2->setName('Leaky Faucet');
        $media2->setFilename('leaky_faucet.jpg');
        $media2->setAltText('leaky_faucet');
        $manager->persist($media2);

        $media3 = new Media();
        $media3->setName('Pallet Coffee Table');
        $media3->setFilename('pallet_coffee_table.jpg');
        $media3->setAltText('pallet_coffee_table');
        $manager->persist($media3);

        $media4 = new Media();
        $media4->setName('Cozy Reading Nook');
        $media4->setFilename('cozy_reading_nook.jpg');
        $media4->setAltText('cozy_reading_nook');
        $manager->persist($media4);

        $typesData = [
            'DIY', 'Home Decor', 'Gardening', 'Crafts', 'Renovation',
            'Interior Design', 'Woodworking', 'Outdoor Projects', 'Repurposing', 'Home Improvement'
        ];

        $types = [];

        foreach ($typesData as $typeName) {
            $type = new Type();
            $type->setName($typeName);
            $type->setColor($this->generateRandomColor());
            $type->setSlug($this->slugger->slug($typeName)->lower());
            $manager->persist($type);
            $types[$typeName] = $type;
        }

        $blogsData = [
            [
                'title' => "Building a driftwood shelf",
                'featuredText' => "Discover how to create a unique shelf using driftwood. Perfect for adding a rustic touch to your interior.",
                'content' => "In this article, we show you step by step how to build a driftwood shelf. From choosing the wood to mounting the shelves, you'll learn everything you need to know to complete this DIY project.",
                'type' => ['DIY'],
                'media' => $media1,
            ],
            [
                'title' => "Fixing a leaky faucet",
                'featuredText' => "Learn how to fix a leaky faucet in a few simple steps. Save water and avoid high bills.",
                'content' => "Leaky faucets can be a nuisance, but fortunately, the repair is often straightforward. In this article, we show you how to identify and fix faucet leaks, whether in the kitchen or bathroom.",
                'type' => ['Home Improvement'],
                'media' => $media2,
            ],
            [
                'title' => "Creating a pallet coffee table",
                'featuredText' => "Transform a pallet into a gorgeous coffee table. An easy and budget-friendly DIY project for your living room.",
                'content' => "Pallets are a versatile material for DIY projects. In this tutorial, we guide you through the steps to create a unique coffee table from a recycled pallet. Perfect for adding an industrial touch to your living room.",
                'type' => ['DIY'],
                'media' => $media3,
            ],
            [
                'title' => "Creating a Cozy Reading Nook",
                'featuredText' => "Transform a corner of your home into a cozy reading nook where you can escape with your favorite book and unwind.",
                'content' => "A cozy reading nook can become your sanctuary for relaxation and reflection. In this article, we share tips and inspiration for creating a comfortable and inviting space dedicated to reading.",
                'type' => ['Home Decor', 'Interior Design'],
                'media' => $media4,
            ],
        ];

        foreach ($blogsData as $blogData) {
            $blog = new Blog();
            $blog->setTitle($blogData['title']);
            $blog->setFeaturedText($blogData['featuredText']);
            $blog->setContent($blogData['content']);
            $blog->setFeaturedMedia($blogData['media']);
            $blog->setAuthor($handymanUser);
            $blog->setSlug($this->slugger->slug($blogData['title'])->lower());

            foreach ($blogData['type'] as $typeName) {
                $type = $types[$typeName];
                $blog->addType($type);
            }

            $manager->persist($blog);
        }

        $manager->flush();
    }

    private function generateRandomColor()
    {
        $red = mt_rand(0, 255);
        $green = mt_rand(0, 255);
        $blue = mt_rand(0, 255);

        $hexColor = sprintf("#%02x%02x%02x", $red, $green, $blue);

        return $hexColor;
    }
}
