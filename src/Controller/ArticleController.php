<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



//ma classe ArticleController hérite de la classe AbstractController (classe générée par symfony)
class ArticleController extends AbstractController
{
    //je créé ma route, lorsque que /article sera appelé dans mon url,
    //c'est le controlleur ci dessous qui sera appelée
    //je créé la méthode articles qui contient mon tableau d'articles
    #[Route('/articles', name: 'article_list')]
    public function articles(){
        $articles = [
            [
                'id' => 1,
                'title' => 'Article 1',
                'content' => 'Content of article 1',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 2,
                'title' => 'Article 2',
                'content' => 'Content of article 2',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 3,
                'title' => 'Article 3',
                'content' => 'Content of article 3',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 4,
                'title' => 'Article 4',
                'content' => 'Content of article 4',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 5,
                'title' => 'Article 5',
                'content' => 'Content of article 5',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 6,
                'title' => 'Article 6',
                'content' => 'Content of article 6',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',

            ]

        ];

        //ici, la méthode render de la classe AbstractController instanciée en haut
        //me permet d'appeler ma vue twig et donc d'afficher mon html ainsi que mon tableau d'articles
        return $this->render('article-list.html.twig',[
            'articles' => $articles
        ]);
    }



    //je créé une route pour l'url en indiquant que lorsque je tape /article dans mon url,
    //c'est ma méthode si dessous qui s'affichera
    //l'écriture /{id} me permet de récupérer l'id directement aprèes le slash dans mon url

    #[Route('/article/{id}', 'article_show')]
    //je créé une nouvelle méthode articleShow afin de récupérer mes articles qui contient mon tableau d'articles
        //MAIS attention, je dois rentrer l'id en paramètre de ma fonction "articleShow"
        //symfony gère le reste en récupérant automtiquement "id" dans l'url
        //plus besoin du request comme fait dans la fonction précédente
        //le routeur acceptera toutes les urls qui ont la forme "/article/quelquechose",ou "/article/3", ou
        // "/article/46" ..

    public function articleShow($id)
    {


        $articles = [
            [
                'id' => 1,
                'title' => 'Article 1',
                'content' => 'Content of article 1',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 2,
                'title' => 'Article 2',
                'content' => 'Content of article 2',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 3,
                'title' => 'Article 3',
                'content' => 'Content of article 3',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 4,
                'title' => 'Article 4',
                'content' => 'Content of article 4',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 5,
                'title' => 'Article 5',
                'content' => 'Content of article 5',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',
            ],
            [
                'id' => 6,
                'title' => 'Article 6',
                'content' => 'Content of article 6',
                'image' => 'https://static.vecteezy.com/system/resources/thumbnails/012/176/986/small_2x/a-3d-rendering-image-of-grassed-hill-nature-scenery-png.png',

            ]

        ];



        //j'initie une variable articleFound et je la met a null
        $articleFound = null;

        //je boucle sur mon tableau d'articles pour récupéré chaque id
        //si l'ID de mon article dans mon tableau correspond à l'id de mon url (récupéré avec $id plus haut)
        foreach ($articles as $article) {
            if ($article['id'] === (int)$id) {
                //alors je remplace ma variable null par l'article entier correspondant a l'ID entré en URL
                $articleFound = $article;
            }

        }
            //j'appelle ici ma vue twig qui me permettra d'afficher du hmtl dans mon navigateur
            //ici j'utilise la méthode "render" qui prend donc en paramètre mon fichier twig
            // et un tableau contenant les variables que je veux utiliser dans mon fichier twig
            return $this->render('article_show.html.twig', [
                'article' => $articleFound
            ]);


    }

}