<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
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

    //je créé une méthode createArticle pour la création de mes articles
    //j'utilise la classe EntityManagerInterface pour sauvegarder l'article créé dans ma BDD : grâce a celle ci et a
    // Doctrine, toutes mes propriété de mon entité Article sont récupéré automatiquement (titre, contenu, image, etc)
    //Article Repository récupère les infos en BDD pour les afficher dans mon navigateur alors que
    //Entity Manager envoie ce que je créé dans mon éditeur de code vers ma BDD
    #[Route('/article/create', 'article_create')]
    public function createArticle(EntityManagerInterface $entityManager): Response
    {

        //dd('HELLO');

        //je créé une instance de l'entité Article
        //grâce aux setter, j'établie en dur les propriété de mon article ajouté, normalement, il faudrai un
        //formulaire grâce auquel je récupère les propriété de l'article créé par l'utilisateur
        $article = new Article();
        $article->setTitle('Article 18');
        $article->setContent('Contenu de l"article 18');
        $article->setImage('https://next-images.123rf.com/index/_next/image/?url=https://assets-cdn.123rf.com/index/static/assets/top-section-bg.jpeg&w=3840&q=75');
        $article->setCreatedAt(new \DateTime());

        //dd($article);

        //ici, j'appelle l'instance de classe $entityManager lié a la classe EntityManager
        //le persist permet de faire une première sauvegarde de mon article créé (comme un commit avant un push)
        // flush permet de créér un enregistrement d'article dans mon BDD dans ma table Article
        $entityManager->persist($article);
        $entityManager->flush();

        //ma méthode attend une réponse, j'en met une juste pour pas créér d'erreur
        return $this->redirectToRoute('article_list');
    }


    #[Route('article/delete/{id}', 'article_delete', ['id'=>'\d+'] )]
    public function removeArticle (int $id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository) : Response
    {
        //dd('hello');

        //je récupère grâce au repository une instance de ma classe Article en BDD$
        $articleRemoved = $articleRepository->find($id);

        //si mon article a déjà été suprrimé ou n'existe pas, alors je renvoie sur la page d'erreur 'not-found'
        if (!$articleRemoved) {
            return $this->redirectToRoute('not_found');
        }

        //j'appelle l'instance de la classe $EntityManager, lié a la classe EntituManager passés tous les deux en
        // paramètre de ma méthode
        //j'utilise le remove pour supprimer un article par son id (comme instancié précédemment)
        $entityManager->remove($articleRemoved);
        $entityManager->flush();


        return $this->render('article_delete.html.twig', [
            'article' => $articleRemoved
        ]);
    }


    //je créé une méthode qui me permet de modifier mes articles
    //j'utilise le repository pour récupérer par l'id grace à doctrine les articles dans mon url
    //ainsi je cible quel article je souhaite modifier
    // grace aux setter présent dans mon entité Article, je récupère ici le titre et le contenu de l'article
    // selectionné avec l'id passé en url et je lui modifie son titre et son contenu
    //j'utilise ensuite une instance ($entityManager) de la classe EntityManager pour faire une pré sauvegarde en BDD
    // et executer les changement en BDD (persist et flush)
    //je retourne un résultat vers ma vue Twig.
    #[Route('article/update/{id}', 'article_update', ['id'=>'\d+'] )]
    public function updateArticle(int $id, ArticleRepository $articleRepository, EntityManagerInterface
    $entityManager) : Response
    {
        $articleUpdated = $articleRepository->find($id);


        $articleUpdated->setTitle('Article blabl');
        $articleUpdated->setContent('Contenu blalba');

        $entityManager->persist($articleUpdated);
        $entityManager->flush();

        return $this->render('article_update.html.twig', [
            'article' => $articleUpdated
        ]);

    }
}