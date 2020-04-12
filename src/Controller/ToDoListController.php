<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index()
    {
        // get all tasks [] not in any particular element, [] in descending order
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([], ['id' => 'DESC']);
        return $this->render('index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/create", name="create_task")
     */
    public function create(Request $request)
    {
        $title = trim($request->request->get('title'));

        // valiation for empty title input
        if (empty($title)) {
            return $this->redirectToRoute('to_do_list');
        }

        // object responsible to save task in the db
        $entityManager = $this->getDoctrine()->getManager();

        // create Task object
        $task = new Task;

        // set title
        $task->setTitle($title);

        // save title to db
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id)
    {
        // object responsible to save task in the db
        $entityManager = $this->getDoctrine()->getManager();

        // get all tasks [] not in any particular element, [] in descending order
        $task = $entityManager->getRepository(Task::class)->find($id);

        $task->setStatus(!$task->getStatus());

        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/delete-task/{id}", name="delete_task")
     */
    public function delete(Task $id)
    {

        //! here I am usung object parameter to get the task object, Task $id

        // object responsible to save task in the db
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($id);

        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }
}