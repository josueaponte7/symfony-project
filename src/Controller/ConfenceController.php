<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ConfenceController extends AbstractController
{
    #[Route('/', name: 'conference')]
    public function index(ConferenceRepository $conferenceRepository, SessionInterface $session)
    {
        $session->set('saludo', 'Hola Mundo');
        $conferences = $conferenceRepository->findAll();

        return $this->render('confence/index.html.twig', [
            'conferences' => $conferences,
        ]);
    }

    #[Route('/conference/{id}', name: 'conference_show')]
    public function show(Request $request, CommentRepository $commentRepository, Conference $conference, SessionInterface $session)
    {
        echo $session->get('saludo');
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentsPaginators($conference, $offset);

        return $this->render('confence/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]);
    }
}
