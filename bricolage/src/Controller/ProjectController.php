<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Service\ProjectMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/project', name: 'project_')]
class ProjectController extends AbstractController
{
    private ProjectMailer $projectMailer;

    public function __construct(ProjectMailer $projectMailer)
    {
        $this->projectMailer = $projectMailer;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, ProjectMailer $projectMailer): Response
    {
        $user = $this->getUser();

        $existingProject = $entityManager->getRepository(Project::class)->findOneBy(['user' => $user]);

        if ($existingProject) {
            return $this->redirectToRoute('project_summary', ['id' => $existingProject->getId()]);
        }

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUser($user);

            $entityManager->persist($project);
            $entityManager->flush();

            $session->set('project_validated', true);

            $handymanEmail = 'handyman@hardware-store.com';
            $userEmail = $this->getUser()->getEmail();
            
            if ($handymanEmail) {
                $this->projectMailer->sendProjectCreationNotification($handymanEmail, $userEmail, $project->getTitle(), $project->getDescription());
            }

            $this->addFlash('message', 'Your project has been notified by one of our handymen, you will receive a message/email.');
            return $this->redirectToRoute('project_summary', ['id' => $project->getId()]);
        }

        return $this->render('pages/project/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/summary/{id}', name: 'summary')]
    public function projectSummary(Project $project, SessionInterface $session): Response
    {
        $isProjectValidated = $session->get('project_validated', false);

        if ($isProjectValidated) {
            if ($this->getUser() !== $project->getUser()) {
                return $this->redirectToRoute('project_index');
            }

            return $this->render('pages/project/summary.html.twig', [
                'project' => $project,
            ]);
        }

        return $this->redirectToRoute('project_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deleteProject(Project $project, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user !== $project->getUser()) {
            return $this->redirectToRoute('project_index');
        }

        $entityManager->remove($project);
        $entityManager->flush();

        $this->addFlash('message', 'Your project has been cancelled ');
        return $this->redirectToRoute('project_index');
    }
}
