<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Message;

class ZMessageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $client = $manager->getRepository(User::class)->findOneBy(['username' => 'Liothasu']);
        $handyman = $manager->getRepository(User::class)->findOneBy(['username' => 'handyman']);

        // Client messages and corresponding handyman responses
        $messagePairs = [
            [
                'client_message' => [
                    'title' => 'Installing a wall shelf',
                    'content' => 'Hi, I bought a wall shelf but I\'m not sure how to install it properly. Can you provide some advice on the ideal height and installation method?',
                ],
                'handyman_response' => [
                    'title' => 'Tips for Installing a Wall Shelf',
                    'content' => 'Hi, to install your wall shelf, first determine the ideal location based on your needs. Then, use a level to ensure the shelf is perfectly straight. Use quality anchors and screws to securely attach the shelf to the wall, making sure to follow the manufacturer\'s instructions. Feel free to reach out if you need further assistance!',
                ],
            ],
            [
                'client_message' => [
                    'title' => 'Repairing a faucet leak',
                    'content' => 'Hi, I have a faucet leak in my kitchen for a few days now and I\'m not sure how to fix it. Can you help by providing instructions on how to resolve this issue?',
                ],
                'handyman_response' => [
                    'title' => 'Fixing a Faucet Leak',
                    'content' => 'Hi, to repair a faucet leak in your kitchen, first turn off the water supply to the faucet. Then, disassemble the faucet to access the seal and cartridge. Check if the seal is worn or damaged and replace it if necessary. Also, ensure the cartridge is in good working condition. If you need additional help, feel free to contact me, and I\'ll be happy to assist!',
                ],
            ],
            [
                'client_message' => [
                    'title' => 'Painting a room',
                    'content' => 'Hi, I\'m planning to paint a room in my house this weekend. Do you have any tips on the best method for preparing the walls and applying the paint to achieve a professional result?',
                ],
                'handyman_response' => [
                    'title' => 'Tips for Painting a Room',
                    'content' => 'Hi, to achieve a professional result when painting a room, start by cleaning and preparing the walls by removing dirt and smoothing them if necessary. Use a quality primer to even out the surface and apply the paint in thin, even coats. Make sure to choose a color that matches your style and decor. Feel free to ask me any questions if you need additional assistance!',
                ],
            ],
            [
                'client_message' => [
                    'title' => 'Repairing a broken fence',
                    'content' => 'Hi, a section of my fence has broken, and I\'m not sure how to repair it. Can you provide guidance on fixing it securely?',
                ],
                'handyman_response' => [
                    'title' => 'Fixing a Broken Fence',
                    'content' => 'Hi, to repair a broken fence securely, start by assessing the damage and gathering the necessary materials such as replacement boards, nails, and a hammer. Remove the damaged section of the fence and replace it with new boards, ensuring they are securely attached to the existing structure. Once the repair is complete, consider reinforcing the surrounding areas for added stability. If you have any further questions, feel free to reach out!',
                ],
            ],
        ];

        // Create Message entities for each client message and corresponding handyman response
        foreach ($messagePairs as $messagePair) {
            // Client message
            $clientMessage = new Message();
            $clientMessage->setTitle($messagePair['client_message']['title']);
            $clientMessage->setContent($messagePair['client_message']['content']);
            $clientMessage->setTimeMsg(new \DateTimeImmutable());
            $clientMessage->setIsRead(false);
            $clientMessage->setSender($client);
            $clientMessage->setRecipient($handyman);

            $manager->persist($clientMessage);

            // Handyman response
            $handymanResponse = new Message();
            $handymanResponse->setTitle($messagePair['handyman_response']['title']);
            $handymanResponse->setContent($messagePair['handyman_response']['content']);
            $handymanResponse->setTimeMsg(new \DateTimeImmutable());
            $handymanResponse->setIsRead(false);
            $handymanResponse->setSender($handyman);
            $handymanResponse->setRecipient($client);
            $handymanResponse->setOriginalMessage($clientMessage);

            $manager->persist($handymanResponse);
        }
        $manager->flush();
    }
}
