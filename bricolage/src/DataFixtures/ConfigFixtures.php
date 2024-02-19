<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $configs[] = new Config('Copyright Text', 'blog_copyright', 'All rights reserved', TextType::class);
        $configs[] = new Config('Number of Blogs Per Page', 'blog_limit', 3, NumberType::class);
        $configs[] = new Config('Anyone Can Register', 'users_can_register', true, CheckboxType::class);
        $configs[] = new Config('About Me', 'blog_about', 'Liothasu', TextType::class);
        $configs[] = new Config('Hardware-store', 'blog_title', 'Hardware-store', TextType::class);


        foreach ($configs as $config) {
            $manager->persist($config);
        }

        $manager->flush();
    }
}
