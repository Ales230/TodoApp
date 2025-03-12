<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]
final class TaskController extends AbstractController
{
    #[Route(name: 'app_task_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statusFilter = $request->query->get('status', 'all');
        $repository = $entityManager->getRepository(Task::class);

        if ($statusFilter === 'done') {
            $tasks = $repository->findBy(['status' => true]);
        } elseif ($statusFilter === 'pending') {
            $tasks = $repository->findBy(['status' => false]);
        } else {
            $tasks = $repository->findAll();
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'statusFilter' => $statusFilter,
        ]);
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Tâche ajoutée avec succès.');
            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Tâche modifiée avec succès.');
            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/toggle', name: 'app_task_toggle', methods: ['POST'])]
    public function toggleStatus(Task $task, EntityManagerInterface $entityManager): Response
    {
        $task->setStatus(!$task->isStatus());
        $entityManager->flush();

        $this->addFlash('success', 'Statut de la tâche mis à jour !');

        return $this->redirectToRoute('app_task_index');
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
            $this->addFlash('success', 'Tâche supprimée avec succès.');
        }

        return $this->redirectToRoute('app_task_index');
    }

    #[Route('/api/tasks', name: 'app_task_api', methods: ['GET'])]
    public function apiTasks(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $statusFilter = $request->query->get('status', 'all');
        $repository = $entityManager->getRepository(Task::class);

        if ($statusFilter === 'done') {
            $tasks = $repository->findBy(['status' => true]);
        } elseif ($statusFilter === 'pending') {
            $tasks = $repository->findBy(['status' => false]);
        } else {
            $tasks = $repository->findAll();
        }

        $data = array_map(function (Task $task) {
            return [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->isStatus(),
                'created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $tasks);

        return new JsonResponse($data);
    }
}
