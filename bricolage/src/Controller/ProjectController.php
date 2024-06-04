<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Service\ProjectMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/project', name: 'project_')]
#[IsGranted("ROLE_USER")]
class ProjectController extends AbstractController
{
    private ProjectMailer $projectMailer;

    public function __construct(ProjectMailer $projectMailer)
    {
        $this->projectMailer = $projectMailer;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $existingProject = $entityManager->getRepository(Project::class)->findOneBy(['user' => $user]);

        if ($existingProject) {
            return $this->redirectToRoute('project_summary', [
                'id' => $existingProject->getId()
            ]);
        }

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUser($user);

            $entityManager->persist($project);
            $entityManager->flush();

            $handymanEmail = 'handyman@brico-project.com';
            $userEmail = $this->getUser()->getEmail();

            if ($handymanEmail) {
                $this->projectMailer->sendProjectCreationNotification($handymanEmail, $userEmail, $project->getTitle(), $project->getDescription());
            }

            $this->addFlash('info', 'Your project has been notified by one of our handymen, you will receive a message/email.');

            return $this->redirectToRoute('project_summary', [
                'id' => $project->getId()
            ]);
        }

        return $this->render('pages/project/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/summary/{id}', name: 'summary')]
    public function projectSummary(Project $project): Response
    {
        if ($this->getUser() !== $project->getUser()) {
            $this->addFlash('error', 'You are not allowed to view this project');
            return $this->redirectToRoute('project_index');
        }

        return $this->render('pages/project/summary.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deleteProject(Project $project, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user !== $project->getUser()) {
            $this->addFlash('error', 'You are not allowed to delete this project');
            return $this->redirectToRoute('project_index');
        }

        $entityManager->remove($project);
        $entityManager->flush();

        $this->addFlash('success', 'Your project has been cancelled ');

        return $this->redirectToRoute('project_index');
    }
}
