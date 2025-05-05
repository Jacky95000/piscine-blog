<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// route page d'accueil
class PageController extends AbstractController {
    #[Route('/', name: "home")]
    public function displayHome() {
        return $this->render('home.html.twig');
    }
    #[Route('/404', name:"404")]
    public function display404() {
        $html = $this->renderView('404.html.twig');
        return new Response($html, 404);
    }
}