<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController {

    // On définit une route accessible via /create-article en GET ou POST
    #[Route('/create-article', name: "create-article")]
    public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager) {

        // Si la requête est une soumission de formulaire (POST)
        if ($request->isMethod("POST")) {

           
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $content = $request->request->get('content');
            $image = $request->request->get('image');


            // Option 1  Utiliser les setters (à activer si tu choisis cette voie)
             // On récupère les données du formulaire, permet de créer un article, fonctions "SET", rempli les données de l'instance de la classe article
             $article = new Article();
             $article->setTitle($title);
             $article->setDescription($description);
             $article->setContent($content);
             $article->setImage($image);


             // Option 2 : constructeur dans Article qui prend ces arguments : encapsulation
        //  $article = new Article($title, $description, $content, $image);

           // On prépare l'objet pour l'enregistrer en BDD
           $entityManager->persist($article);
           // On exécute la requête SQL correspondante (INSERT ici)
           $entityManager->flush();
        }
         // On retourne la vue du formulaire (template Twig à créer ou compléter)
        return $this->render('create-article.html.twig');
    }


#[Route('/list-articles', name: 'list-articles')]
public function displayListArticles(ArticleRepository $articleRepository){

    // permet de faire une requête SQl SELECT * sur la table article
    $articles = $articleRepository->findAll();
    return $this->render('list-articles.html.twig' , [
        'articles' => $articles
    ]);
}
// nouvelle route dans le contrôleur, URL attend un paramètre (id)
#[Route('/detail-article/{id}', name: 'detail-articles')]

// récupère l'id depuis l'url et le passe à la fonction
public function displayDetailsArticle($id, ArticleRepository $articleRepository) {
   $article = $articleRepository->find($id);


//    si l'article n'est pas trouvé avec l'id demandé, on envoie la page qui affiche l'erreur 404
   if (!$article) {
    return $this->redirectToRoute('404');
   }

   return $this->render('details-article.html.twig', [
    'article' => $article
   ]);
}

#[Route('/delete-article/{id}' , name: 'delete-article')]
public function displayDeleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager){
    // récupération de l'article pour pouvoir le supprimer
    $article = $articleRepository->find($id);

    // utilisation de la méthode remove de la classe EntityManager qui prend en parametre l'article à supprimer
    $entityManager->remove($article);
    $entityManager->flush();

    // ajout d'un message pour la suppression de l'article
    $this->addFlash('success', 'article supprimé');
    return $this->redirectToRoute('list-articles');

}
}