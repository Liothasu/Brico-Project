<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\SendMessageType;
use App\Form\ReplyMessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_ROLE for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */

class MessageController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/message', name: 'message')]
    public function index(): Response
    {
        return $this->render('/pages/message/index.html.twig');
    }

    #[Route('/send', name: 'send')]
    public function send(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(SendMessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $this->addFlash("message", "Message envoyé avec succès.");
            return $this->redirectToRoute("message");
        }

        return $this->render("pages/message/send.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/sent', name: 'sent')]
    public function sent(): Response
    {
        return $this->render('pages/message/sent.html.twig');
    }

    #[Route('/read/{id}', name: 'read')]
    public function read(Message $message): Response
    {
        $message->setIsRead(true);
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $this->render('pages/message/read.html.twig', compact("message"));
    }

    #[Route('/reply/{id}', name: 'reply')]
    public function reply($id, Request $request, MessageRepository $messageRepository): Response
    {
        $originalMessage = $messageRepository->find($id);

        $replyMessage = new Message();
        $replyMessage->setOriginalMessage($originalMessage);

        $originalTitle = $originalMessage->getTitle();

        $replyTitle = "RE: " . $originalTitle;

        $form = $this->createForm(ReplyMessageType::class, $replyMessage, [
            'original_title' => $originalTitle,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($originalMessage->getTitle() !== null) {
                $replyMessage->setTitle($replyTitle);
            }

            $replyMessage->setSender($this->getUser());
            $replyMessage->setRecipient($originalMessage->getSender());

            $originalMessage->addReply($replyMessage);

            $this->entityManager->remove($originalMessage);
            $this->entityManager->flush();

            $this->entityManager->persist($replyMessage);
            $this->entityManager->flush();

            $this->addFlash("message", "Reply sent successfully.");
            return $this->redirectToRoute('message');
        }

        return $this->render('pages/message/reply.html.twig', [
            'form' => $form->createView(),
            'originalMessage' => $originalMessage,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Message $message, EntityManagerInterface $entityManager): Response
{
    foreach ($message->getReplies() as $reply) {
        $entityManager->remove($reply);
    }

    $entityManager->remove($message);
    $entityManager->flush();

    return $this->redirectToRoute("message");
}
}
