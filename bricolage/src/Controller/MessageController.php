<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'list_message')]
    public function listMessages(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

        return $this->render('message/list_message.twig', [
            'messages' => $messages,
        ]);
    }
    
    #[Route('/message/{id}', name: 'show_message')]
    public function showMessage(MessageRepository $messageRepository, int $id): Response
    {
        $message = $messageRepository->findMessageById($id);
        $message = $messageRepository->findMessageById($id);

        if (!$message) {
            throw $this->createNotFoundException('Message not found');
        }

        $recipients = $message->getUser();
        $sender = $messageRepository->findSenderByMessageId($id);

        return $this->render('message/show.twig', [
            'message' => $message,
            'recipients' => $recipients,
            'sender' => $sender,
        ]);
        
    }   
}
