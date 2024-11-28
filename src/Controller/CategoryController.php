<?php
// j'ai créé via le terminal de PHPStorm une entité Catégory avec en propriété id (auto générée), title et color.
//Je l'ai mappé grâce à doctrine avec les lignes de commande adéquates : j'ai généré la requête SQL de création de table et
//j'ai éxécuté la requête SQL en BDD
//j'ai créé manuellement en BDD différentes catégories dans la talbe catégory

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    //je créé une route qui me permettra de récupérer dans l'url /category et ainsi cela appelera le controlleur ci dessous
    //je créé ma méthode category qui va me permettre d'afficher toutes mes catégories
    //j'utilise CategoryRepository qui a été instancié automatiquement quand j'ai créé mon entité Catégory
    //elle me permet de récupérer toutes les catégories créés en BDD
    #[Route('/categories', name: 'categories_list')]
    public function categories(CategoryRepository $categoryRepository): Response
    {


        //dans cette variable category, je récupère toutes les catégories en BDD de ma table Category
        $categories = $categoryRepository->findAll();

        return $this->render('categories_list.html.twig', [
            'categories' => $categories
        ]);
    }

    //idem pour la route, sauf qu'ici je précise que je vais récupérer l'id directement après mon slash
    #[Route('/category/{id}', name: 'category_show', requirements: ['id' => '\d+'])]
    public function categoryShow(int $id, CategoryRepository $categoryRepository): Response
    {


        //dans ma variable categoryFound ci-dessous, j'utilise le find id pour pouvoir récupérer les catégories par leur
        //id en BDD dans ma table category (grace a categoryRepository)
        $categoryFound = $categoryRepository->find($id);

        //je fais une condition, si l'id n'est pas présent en BDD, alors je renvoie vers ma page d'erreur 404
        if (!$categoryFound) {
            return $this->redirectToRoute('not_found');
        }

        //j'appelle mon fichier html twig qui me permettra d'afficher ma page sur mon navigateur
        //je précise ici que le paramètre twig category fait référence a ma variable categoryFound dans ma méthode ici
        return $this->render('category_show.html.twig', [
            'category' => $categoryFound
        ]);





    }

}