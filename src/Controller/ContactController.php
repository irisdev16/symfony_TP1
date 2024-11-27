<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//je créé ma classe ContactController et je lui fais hériter la classe AbstractController issu de symfony
class ContactController extends AbstractController
{

    //Je créé ma route, qui dit que lorsque j'écrirai /contact dans mon url, c'est mon controller ci dessous
    //qui sera appelé
    //Je créé la méthode contact qui permet d'afficher mon formulaire
    #[Route('/contact', 'contact_form')]
    //je passe la classe Request dans les paramètres de ma méthode et j'y ajoute aussi la variable $request = autowire
    public function contact(Request $request): Response
    {

        //je récupère les données du formulaire en POST
        //d'abord je récupère tout le formulaire
        //ensuite je récupère le nom pour pouvoir le mettre a coté de mon message
        $contact = $request->request->get('contact');
        $nom = $request -> request -> get ('nom');

        //j'initie un message a null
        $message = null;

        //si la requête est POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //et si la clé nom existe
            if(key_exists('nom', $_POST)){
                //alors j'affiche le message ci dessous
                $message = 'Message bien envoyé';
            }

        }

        //je retourne la vue twig qui contient mon html
        return $this->render('contact.html.twig', [
            //je dois récupérer également ces élément de mon controller pour qu'il soit possible de les appeler
            //dans mon twig
            'contact' => $contact,
            'message' => $message,
            'nom' => $nom
        ]);
    }
}