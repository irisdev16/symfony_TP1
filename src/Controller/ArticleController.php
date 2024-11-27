<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



//ma classe ArticleController hérite de la classe AbstractController (classe générée par symfony)
class ArticleController extends AbstractController
{
    //je créé ma route, lorsque que /article sera appelé dans mon url,
    //c'est le controlleur ci dessous qui sera appelée
    //je créé la méthode articles qui contient mon tableau d'articles
    //la classe articleRepository est instancié automatiquement quand j'ai initié mon entité Article
    //elle me permet entre autres de récupérer les articles créés en BDD, comme avec un SELECT *
    #[Route('/articles', name: 'article_list')]
    public function articles(ArticleRepository $articleRepository): Response
    {

        //dans ma variable articles, j'utilise le findAll pour récupéré tous les articles en BDD de ma table Article
        $articles = $articleRepository->findAll();


        //ici, la méthode render de la classe AbstractController instanciée en haut
        //me permet d'appeler ma vue twig et donc d'afficher mon html ainsi que mon tableau d'articles
        return $this->render('article-list.html.twig',[
            'articles' => $articles
        ]);
    }

    //je créé une route pour l'url en indiquant que lorsque je tape /article dans mon url,
    //c'est ma méthode si dessous qui s'affichera
    //l'écriture /{id} me permet de récupérer l'id directement aprèes le slash dans mon url
    #[Route('/article/{id}', 'article_show', ['id'=>'\d+'])]
    //je créé une nouvelle méthode articleShow afin de récupérer mes articles qui contient mon tableau d'articles
        //MAIS attention, je dois rentrer l'id en paramètre de ma fonction "articleShow"
        //symfony gère le reste en récupérant automtiquement "id" dans l'url
        //plus besoin du request comme fait dans la fonction précédente
        //le routeur acceptera toutes les urls qui ont la forme "/article/quelquechose",ou "/article/3", ou
        // "/article/46" ..

        //j'ajoute en paramètre la classe ArticleRepository qui est généré automatiquement quand j'ai inité
        // mon entité Article via ma BDD
        //cela me permet notamment de récupérer les les articles créé en BDD dans ma table article comme avec le
        //SELECT *

    public function articleShow(int $id, ArticleRepository $articleRepository) : Response
    {

        //dans ma variable articleFound, j'utilise le find($id) pour récupérer les articles par leur id dans ma table
        //Article en BDD
        $articleFound = $articleRepository->find($id);


        //si la page n'a pas été trouvée, je renvoie sur la page not found
        if (!$articleFound) {
            return $this->redirectToRoute('not_found');
        }

        //j'appelle ici ma vue twig qui me permettra d'afficher du hmtl dans mon navigateur
        //ici j'utilise la méthode "render" qui prend donc en paramètre mon fichier twig
        // et un tableau contenant les variables que je veux utiliser dans mon fichier twig
        return $this->render('article_show.html.twig', [
            'article' => $articleFound
        ]);


    }

    #[Route('/articles/search-results','article_search_results')]
    //plutot que d'utiliser la classe Request
    //je passe cette classe dans les paramètre de ma méthode = système d'instanciation automatique de symfony
        //Request est le type de la classe que je veux
        //$request est ma variable sur laquelle ma classe sera instanciée et stockée == autowire
    public function articleSearchResults(Request $request): Response
    {

        $search = $request->query->get('search');



        return $this->render('article_search_results.html.twig', [
            'search' => $search
        ]);
    }

}