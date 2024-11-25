<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

//je créé une classe HomeController
class HomeController extends AbstractController
{

    //je créé une Route, c'est une sorte d'annotation (avec le #) qui permet de dire que
    //pour l'url notée '/', c'est mon controller si dessous qui s'affichera
    #[Route('/', name: 'home')]

    // je créé une méthode home
    //elle appelle une instance de la classe Response (classe issue de Symfony)
    //ici, j'ai une réponse http qui se traduit par du html
    //elle permet d'afficher ici le titre "Page Accueil" sur mon navigateur
    public function home() {
        return $this->render('home.html.twig');
    }
}