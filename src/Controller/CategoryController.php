<?php
// j'ai créé via le terminal de PHPStorm une entité Catégory avec en propriété id (auto générée), title et color.
//Je l'ai mappé grâce à doctrine avec les lignes de commande adéquates : j'ai généré la requête SQL de création de table et
//j'ai éxécuté la requête SQL en BDD
//j'ai créé manuellement en BDD différentes catégories dans la talbe catégory

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
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

    //je créé une méthode createCategory pour la création de mes categories
    //je créé un instance de classe de mon entité Category puisque je suis dans la création de catégories et que je
    // veux pouvoir créer une nouvelle catégorie
    //je génère un formulaire grace au Gabarit CategoryType dans mon terminal (avec make:form)
    //j'utilise la méthode createForm issu de la classe héritéé AbstractController
    //j'utilise la méthode handleRequest en lui passant en paramètre $request : ça récupère la requête HTTP (POST ou
    // GET ou whatever)
    //condition : si le formulaire est bien soumis, je pré-sauvegarde et j'exécute la création de l'article en BDD
    //je créé une vue pour ce formulaire afin que celle ci soit lu dans mon fichier twig
    #[Route('/categories/create', name: 'category_create')]
    public function createCategory(Request $request,EntityManagerInterface $entityManager): Response
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($category);
            $entityManager->flush();
        }

       $formView = $form->createView();


        return $this->render('category_create.html.twig', [
            'formView' => $formView,
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

    //je créé une méthode qui me permet de modifier mes catégories
    //j'utilise le repository pour récupérer par l'id grace à doctrine les catégories dans mon url
    //ainsi je cible quelle catégorie je souhaite modifier
    // j'utilise la méthode createForm pour récupérer un formulaire créér dans mon terminal grâce a AbstractController
    //je lui passe en paramètre le gabarit (mon CategoryType) et mon categoryUpdated.
    //j'utilise la méthode handleRequest pour récupérer la requête HTTP (POST ici) de mon formulaire
    //condition : si le formulaire est bien soumis, je pré-sauvegarde et j'exécute la création de la catégorie en BDD
    //je créé une vue pour ce formulaire afin que celle ci soit lu dans mon fichier twig

    #[Route('/category/update/{id}', name: 'category_update', requirements: ['id' => '\d+'])]
    public function updateCategory(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, Request $request): Response
    {
        $categoryUpdated = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $categoryUpdated);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($categoryUpdated);
            $entityManager->flush();
        }

        $formView = $form->createView();

        return $this->render('category_update.html.twig', [
            'formView' => $formView
        ]);

    }
}
