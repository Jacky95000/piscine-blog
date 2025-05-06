<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController {

    #[Route('/list-categories', name:'list-categories')]
    public function displayListCategory(CategoryRepository $categoryRepository){
        // requête sql sur la table category
        $categories = $categoryRepository->findAll();

        return $this->render('list-categories.html.twig', [
            'categories' => $categories]);
    }
    #[Route('/details-category/{id}', name: 'details-category')]

    public function displayDetailsCategory($id, CategoryRepository $categoryRepository) {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->redirectToRoute('404');
        }
        return $this->render('details-category.html.twig', [
            'category' => $category
        ]);
    }
    #[Route('/create-category', name: 'create-category')]
    public function displayCreateCategory(request $request, EntityManagerInterface $entityManager){

        // création instance de category
        $category = new Category();

        // création du formulaire 
        // utilisation du gabarit formulaire "CategoryForm" généré avec "make:form"
        // et l'instance de category
        $categoryForm = $this->createForm(CategoryForm::class, $category);

        // je stocke la variable du formulaire qui est envoyées en POST
        if ($categoryForm->isSubmitted()) {
            // si oui, sauvegarde la category, propriétés ont été automatiquement remplies par symfony et le système de formulaire
            $entityManager->persist($category);
            $entityManager->flush();
        }
        return $this->render('create-category.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }
}