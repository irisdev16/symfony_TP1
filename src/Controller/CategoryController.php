<?php
// j'ai créé via le terminal de PHPStorm une entité Catégory avec en propriété id (auto générée), title et color.
//Je l'ai mappé grâce à doctrine avec les lignes de commande adéquates : j'ai généré la requête SQL de création de table et
//j'ai éxécuté la requête SQL en BDD
//j'ai créé manuellement en BDD différentes catégories dans la talbe catégory

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    //je créé une méthode createCategory pour la création de mes catégories
    // j'utiliser la classe EntityManager et son instance de classe pour pré-sauvegarder et executer la catégorie créé
    // dans ma BDD
    //j'initie ma variable catégory a null
    //je récupère les données en POST de mon formulaire
    //si la requête est bien une requete post, alors je créé une nouvel categorie avec le titre
    // passé dans les champs du formulaire par l'utilisateur
    //condition : si le formulaire est vide, je renvoie vers ma page d'erreur
    // je renvoie grâce a ma méthode render vers mon fichier twig qui renvoie du html sur mon navgateur
    #[Route('/categories/create', name: 'category_create')]
    public function createCategory(Request $request,EntityManagerInterface $entityManager): Response
    {

        //dd('HELLO');

        $message= "Veuillez remplir les champs";

        if ($request->isMethod('POST')) {

            $title = $request->request->get('title');
            $color = $request->request->get('color');

            $category = new Category();
            $category->setTitle($title);
            $category->setColor($color);

            if(!empty($category->getTitle())){
                $entityManager->persist($category);
                $entityManager->flush();
                $message = "Catégorie bien créée";
            } else
                $message = "Attention, vous n'avez pas rempli tous les champs";
        }


        return $this->render('category_create.html.twig', [
            'message' => $message
        ]);

    }

    #[Route('/category/delete/{id}', name: 'category_delete', requirements: ['id' => '\d+'])]
    public function removeCategory(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {

        $categoryRemoved = $categoryRepository->find($id);

        if (!$categoryRemoved) {
            return $this->redirectToRoute('not_found');
        }

        $entityManager->remove($categoryRemoved);
        $entityManager->flush();

        return $this->render('category_delete.html.twig', [
            'category' => $categoryRemoved
        ]);
    }

    //je créé une méthode update pour modifier ma catégorie
    //je créé en amont la route pour mon URL en précisant que cela doit être un INTEGER
    //je passe en paramètre de ma fonction l'id, la classe EntityManager et son instance de classe, la classe CategoryRepository et son instance de classe
    // j'utilise le Repository pour aller récupérer les catégory par leur id notamment grâce a doctrine
    //grâce aux setter présent dans mon entité Category, je modifie le titre de ma catégorie
    //avec la classe EntityManager, j'utilise l'instance de classe $entityManager pour  pré-sauvegarder les modifications apportées
    //en BDD et executer ces modifications dans ma BDD.
    //je retourne grace a la méthode render un résultat qui me mène sur une vue Twig qui renvoie du hmtl

    #[Route('/category/update/{id}', name: 'category_update', requirements: ['id' => '\d+'])]
    public function updateCategory(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $categoryUpdated = $categoryRepository->find($id);

        $categoryUpdated->setTitle('POLITECHNIQUE++');

        $entityManager->persist($categoryUpdated);
        $entityManager->flush();

        return $this->render('category_update.html.twig', [
            'category' => $categoryUpdated
        ]);

    }
}
