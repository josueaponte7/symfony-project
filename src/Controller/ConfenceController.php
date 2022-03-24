<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfenceController extends AbstractController
{
    #[Route('/hello/{name}', name: 'app_confence')]
    public function index(string $name): Response
    {
        $greet = '';
        if ('' !== $name && '0' !== $name) {
            $greet = sprintf('<h1>Hello %s</h1>', htmlspecialchars($name));
        }

        return $this->render('confence/index.html.twig', [
            'name' => $name,
        ]);
    }
}
