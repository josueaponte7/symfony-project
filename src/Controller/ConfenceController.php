<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFormType;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use App\Service\Utils\GetCities;
use App\Service\Utils\GetDummiesPostByUser;
use App\Service\Utils\GetDummiesUser;
use App\Service\Utils\SpamChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ConfenceController extends AbstractController
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    #[Route('/', name: 'conference', methods: ['GET'])]
    public function index(ConferenceRepository $conferenceRepository, SessionInterface $session)
    {
        echo $session->get('city');
        $conferences = $conferenceRepository->findAll();

        return $this->render('confence/index.html.twig', [
            'conferences' => $conferences,
        ]);
    }

    #[Route('/conference/{slug}', name: 'conference_show')]
    public function show(string $slug, Request $request, CommentRepository $commentRepository, ConferenceRepository $conferenceRepository, Conference $conferencex, SpamChecker $spamChecker)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $conferencey = $conferenceRepository->findBy(['slug' => $slug], [], 1, 0)[0];
            $comment->setConference($conferencey);

            $commentRepository->persist($comment);
            $commentRepository->save();

            $context = [
                'user_ip', $request->getClientIp(),
                'user_agent' => $request->headers->get('user-agent'),
                'referrer' => $request->headers->get('ferrer'),
                'permalink' => $request->getUri(),
            ];

            $this->bus->dispatch(new CommentMessage($comment->getId(), $context));

            return $this->redirectToRoute('conference_show', ['slug' => $conferencex->getSlug()]);
        }
        $conference = $conferenceRepository->findBy(['slug' => $slug], [], 1, 0)[0];
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentsPaginators($conference, $offset);

        return $this->render('confence/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(\count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView(),
        ]);
    }

    #[Route('/conference_add', name: 'conference_add', methods: ['GET'])]
    public function create(Request $request, ConferenceRepository $conferenceRepository, GetCities $getCities, SessionInterface $session)
    {
        $city = ($getCities)('capital');
        $year = Conference::yearRandom();
        $session->set('city', $city->capital);
        $conference = new Conference();
        $conference->setPais($city->name);
        $conference->setCity($city->capital);
        $conference->setYear($year);
        $conference->setIsInternational(true);
        $conferenceRepository->add($conference);

        return $this->redirectToRoute('conference');
    }

    #[Route('/conference_del/{id}', name: 'conference_del', methods: ['GET'])]
    public function deleteConference(Conference $conference, ConferenceRepository $conferenceRepository)
    {
        $conferenceRepository->remove($conference);

        return $this->redirectToRoute('conference');
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws \JsonException
     */
    #[Route('/comment_add/{slug}', name: 'comment_add', methods: ['GET'])]
    public function createComment(
        string $slug,
        ConferenceRepository $conferenceRepository,
        CommentRepository $commentRepository,
        GetDummiesUser $getDummiesUser,
        GetDummiesPostByUser $getDummiesPostByUser
    ) {
        $conference = $conferenceRepository->findBy(['slug' => $slug], [], 1, 0)[0];
        $post = ($getDummiesPostByUser)('posts');
        $user = (object) ($getDummiesUser)($post->userId);
        $comment = new Comment();
        $comment->setConference($conference);
        $comment->setAuthor($user->name);
        $comment->setEmail($user->email);
        $comment->setText($post->body);
        $commentRepository->add($comment);

        return $this->redirectToRoute('conference_show', ['slug' => $slug]);
    }

    #[Route('/comment_del/{id}', name: 'comment_del', methods: ['GET'])]
    public function deleteComment(Comment $comment, CommentRepository $commentRepository, SessionInterface $session)
    {
        $slug = $comment->getConference()->getSlug();
        $commentRepository->remove($comment);

        return $this->redirectToRoute('conference_show', ['slug' => $slug]);
    }
}
