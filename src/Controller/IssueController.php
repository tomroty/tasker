<?php

namespace App\Controller;

use App\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IssueController extends AbstractController
{
    #[Route('/issues/{id}', name: 'issue_show')]
    public function show(?Issue $issue): Response
    {
        if (!$issue) {
           return $this->redirectToRoute('issue_list');
        }

        return $this->render('issue/show.html.twig', [
            'issue' => $issue,
        ]);
    }

    #[Route('/issues', name: 'issue_list')]
    public function list(): Response
    {
        return $this->render('issue/list.html.twig', [
            'controller_name' => 'IssueController',
        ]);
    }
}
