<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokerController
{

    //je créé une route pour mon url
    //pour afficher cette page, il me faudra ajouter /poker a mon url
    #[Route('/poker', name: 'poker')]

    //je créé une méthode homePoker dans laquelle j'appelle la
    //méthode createFromGlobals.
    //je n'ai pas besoin de faire une instance de classe avec "New"
    //avec cette méthode Request et CreateFromGLobals, je récupère
    // toutes les données de requêtes HTTP comme POST, GET, SESSION, etc...
    public function homePoker(){

        //query me permet de récupérer les données de GET
        //je précise donc que ma requête sera un GET
        //il me faudra entrer un get age dans mon url

        $request = Request::createFromGlobals();
        $age = $request->query->get('age');



        //je retourne une reponse HTML
        return new Response('Bienvenue sur le site de Poker');
    }

}