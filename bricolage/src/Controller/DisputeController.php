<?php

namespace App\Controller;

use App\Entity\Dispute;
use App\Entity\User;
use App\Form\DisputeType;
use App\Repository\BlogRepository;
use App\Repository\CommentRepository;
use App\Repository\OrderRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact', name: 'contact_')]
class DisputeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function createDispute(Request $request, ProjectRepository $projectRepository, CommentRepository $commentRepository, OrderRepository $orderRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response 
    {
        $user = $this->getUser();

        $dispute = new Dispute();

        $projects = $projectRepository->findBy(['user' => $user]);
        $comments = $commentRepository->find(1);
        $orders = $orderRepository->findBy(['user' => $user]);

        $form = $this->createForm(DisputeType::class, $dispute, [
            'projects' => $projects,
            'comments' => $comments,
            'orders' => $orders,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $problemType = $dispute->getProblemType();

            if ($problemType === 'Blog') {
                if (!$dispute->getBlog() || !$dispute->getBlog()->getTitle()) {
                    $this->addFlash('warning', 'Please select a title for the blog.');
                    return $this->redirectToRoute('contact_index');
                }

                $dispute->setProject(null);
                $dispute->setComment(null);
                $dispute->setOrder(null);
            } elseif ($problemType === 'Project') {
                if (!$dispute->getProject() || !$dispute->getProject()->getNameProject()) {
                    $this->addFlash('warning', 'Please select a name for the project.');
                    return $this->redirectToRoute('contact_index');
                }

                $dispute->setBlog(null);
                $dispute->setComment(null);
                $dispute->setOrder(null);
            } elseif ($problemType === 'Comment') {
                if (!$dispute->getComment() || !$dispute->getComment()->getContent()) {
                    $this->addFlash('warning', 'Please select a matching comment.');
                    return $this->redirectToRoute('contact_index');
                }

                $dispute->setBlog(null);
                $dispute->setProject(null);
                $dispute->setOrder(null);
            } elseif ($problemType === 'Order') {
                if (!$dispute->getOrder() || !$dispute->getOrder()->getReference()) {
                    $this->addFlash('warning', 'Please select a matching order.');
                    return $this->redirectToRoute('contact_index');
                }

                $dispute->setBlog(null);
                $dispute->setProject(null);
                $dispute->setComment(null);
            }

            $dispute->setUser($user);
            $entityManager->persist($dispute);
            $entityManager->flush();

            $this->sendEmailNotification($dispute, $user, $mailer);

            $this->addFlash("info", "Your dispute has been created and notify an adminstrator.");

            return $this->redirectToRoute('contact_details', [
                'id' => $dispute->getId()
            ]);
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
            'orders' => $orders,
            'projects' => $projects,
            'comments' => $comments,
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function disputeDetails($id, EntityManagerInterface $entityManager): Response
    {
        $dispute = $entityManager->getRepository(Dispute::class)->find($id);

        if (!$dispute) {
            throw $this->createNotFoundException('Dispute no found');
        }

        return $this->render('pages/contact/details.html.twig', [
            'dispute' => $dispute,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function editDispute($id, Security $security, Request $request, BlogRepository $blogRepository, ProjectRepository $projectRepository, CommentRepository $commentRepository, OrderRepository $orderRepository, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = $security->getUser();

        $dispute = $entityManager->getRepository(Dispute::class)->find($id);

        if (!$dispute) {
            throw $this->createNotFoundException('Dispute not found');
        }

        $blogs = $blogRepository->find(1);
        $projects = $projectRepository->findBy(['user' => $user]);
        $comments = $commentRepository->find(1);
        $orders = $orderRepository->findBy(['user' => $user]);

        $form = $this->createForm(DisputeType::class, $dispute, [
            'blogs' => $blogs,
            'projects' => $projects,
            'comments' => $comments,
            'orders' => $orders,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $problemType = $dispute->getProblemType();

            if ($problemType === 'Blog') {
                if (!$dispute->getBlog() || !$dispute->getBlog()->getTitle()) {
                    $this->addFlash('warning', 'Please select a title for the blog.');
                    return $this->redirectToRoute('contact_edit', [
                        'id' => $dispute->getId()
                    ]);
                }

                $dispute->setProject(null);
                $dispute->setComment(null);
                $dispute->setOrder(null);
            } elseif ($problemType === 'Project') {
                if (!$dispute->getProject() || !$dispute->getProject()->getNameProject()) {
                    $this->addFlash('warning', 'Please select a name for the project.');
                    return $this->redirectToRoute('contact_edit', [
                        'id' => $dispute->getId()
                    ]);
                }

                $dispute->setBlog(null);
                $dispute->setComment(null);
                $dispute->setOrder(null);
            } elseif ($problemType === 'Comment') {
                if (!$dispute->getComment() || !$dispute->getComment()->getContent()) {
                    $this->addFlash('warning', 'Please select a matching comment.');
                    return $this->redirectToRoute('contact_edit', [
                        'id' => $dispute->getId()
                    ]);
                }

                $dispute->setBlog(null);
                $dispute->setProject(null);
                $dispute->setOrder(null);
            } elseif ($problemType === 'Order') {
                if (!$dispute->getOrder() || !$dispute->getOrder()->getReference()) {
                    $this->addFlash('warning', 'Please select a matching order.');
                    return $this->redirectToRoute('contact_edit', [
                        'id' => $dispute->getId()
                    ]);
                }

                $dispute->setBlog(null);
                $dispute->setProject(null);
                $dispute->setComment(null);
            }

            $dispute->setUser($user);
            $entityManager->persist($dispute);
            $entityManager->flush();

            $this->sendEmailNotification($dispute, $user, $mailer);

            $this->addFlash("info", "Your dispute has been updated and the administrator has been notified.");

            return $this->redirectToRoute('contact_details', [
                'id' => $dispute->getId()
            ]);
        }

        return $this->render('pages/contact/edit.html.twig', [
            'form' => $form->createView(),
            'dispute' => $dispute,
            'orders' => $orders,
            'projects' => $projects,
            'comments' => $comments,
        ]);
    }

    private function sendEmailNotification(Dispute $dispute, User $user, MailerInterface $mailer): void
    {
        $subject = $dispute->getTitle();
        $userEmail = $user->getEmail();
        $description = $dispute->getDescription();

        $email = (new Email())
            ->from($userEmail)
            ->to('admin@brico-project.com')
            ->subject($subject)
            ->html($description);

        $mailer->send($email);
    }
}
