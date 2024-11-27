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


        //j'initie un message, un nom, un prenom a null
        $message = null;
        $nom = null;
        $prenom = null;


        //si la requête est POST
        if ($request->isMethod('POST')) {
            //je récupère les données du formulaire en POST
            //d'abord je récupère tout le formulaire
            //ensuite je récupère le nom pour pouvoir le mettre a coté de mon message
            $nom = trim($request->request->get('nom'));
            $prenom = trim($request->request->get('prenom'));
            $contenuMessage = trim($request->request->get('message'));


            //si les champs ne sont pas vide, alors je renvoie mon message de validation
            if (!empty($nom) && !empty($prenom) && !empty($contenuMessage)) {
                $message = 'Message bien envoyé'.' '.$nom .' ' .$prenom;
                //sinon, j'envoie mon message de non validation
            } else{
                $message = 'Veuillez remplir tous les champs'.' ' .$nom .' ' .$prenom;
            }

        }

        //je retourne la vue twig qui contient mon html
        return $this->render('contact.html.twig', [
            //je dois récupérer également ces élément de mon controller pour qu'il soit possible de les appeler
            //dans mon twig
            'message' => $message,
            'nom' => $nom,
            'prenom' => $prenom
        ]);
    }
}