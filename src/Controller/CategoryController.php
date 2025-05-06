<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController {

    #[Route('/list-category', name:'list-category')]
    public function displayListCategory(CategoryRepository $categoryRepository){
        // requête sql sur la table category
        $categories = $categoryRepository->findAll();

        return $this->render('list-category.html.twig', [
            'categorys' => $categories]);
    }
    #[Route('/detail-category/{id}', name: 'detail-category')]

    public function displayDetailsCategory($id, CategoryRepository $categoryRepository) {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->redirectToRoute('404');
        }
        return $this->render('detail-category.html.twig', [
            'category' => $category
        ]);
    }
}