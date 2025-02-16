<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(LivreRepository $livreRepo): Response
    {

        $livres = $livreRepo->findAll();


        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
            'livres' => $livres,
        ]);
    }

    // Ici une route pour ajouter des livres à notre table
    #[Route('/livre/ajouter', name: 'app_ajouter')]
    // On va utiliser ici dans la méthode, la notion d'utilisation de notre outil Request, c'est ce qu'on appelle une "injection de dépendance" (c'est à dire à amène cette fonctionnalité dans la méthode)
    public function ajouter(Request $request, EntityManagerInterface $em)
    {

        // dd($request);
        // Normalement lorsque je traite un form en PHP, je le manipule avec la superglobale $_POST
        // On remarque grâce à la barre de debug ainsi qu'au dd()  (dump and die), que $_POST récupère bien les infos du formulaire, par contre en Symfony on va préférer utiliser l'outil "Request"
        // Cet outil englobe nombreuses informations en rapport avec une requête de l'utilisateur qu'elle soit GET ou POST 
        // dd($_POST);

        if ($request->isMethod("POST")) {
            // dd($request);
            // A l'intérieur de l'objet Request qui inclue toutes les informations de requête utilisateur, j'ai accès à l'indice request qui représente POST (et l'indice query quant à lui représente GET)
            //Lorsque j'ai des objets de type "Bag" je peux toujours appeler la méthode get() en spécifiant entre parenthèses le param que je souhaite récupérer
            $titre = $request->request->get("titre");
            $auteur = $request->request->get("auteur");

            // dd($titre, $auteur);

            // Je n'utilise plus le MySQL à la main, grâce à doctrine tout est géré au travers des objets !
            // Je dois donc instancier un objet de type Livre pour lui donner les valeurs que je viens de récupérer 
            // Par la suite je pourrais intéragir avec la bdd 

            $livre = new Livre;
            $livre->setTitre($titre);
            $livre->setAuteur($auteur);

            // dd($livre);

            // On doit maintenant gérer l'insertion de cette entité dans notre table Livre
            // Pour cela on utilise le service EntityManager qui est en charge de gérer les insertions de nos entités vers la BDD 
            // A cette étape là, l'objet Livre représente un enregistrement de la BDD, c'est l'EntityManager qui va le valider

            // La méthode persist permet de préparer une requête INSERT INTO à partir d'un objet entité 
            $em->persist($livre);
            // La méthode "flush" exécute toutes les requêtes SQL préparées par persist et c'est fini 
            $em->flush();


            //redirection sur la page d'accueil
            return $this->redirectToRoute("app_livre");

            // return $this->redirectToRoute("app_livre");

        }

        return $this->render(
            "livre/formulaire.html.twig",
            [
                'livres' => []
            ]
        );
    }

// Suppression du livre
    #[Route('/livre/supprimer/{id}', name: 'app_supprimer')]
    public function supprimer(Livre $livre, EntityManagerInterface $em): Response
    {
        
        $em->remove($livre);
        $em->flush();

       
        return $this->redirectToRoute('app_livre');
    }
// modification du livre
    #[Route('/livre/modifier/{id}', name: 'app_modifier')]
public function modifier(Livre $livre, Request $request, EntityManagerInterface $em): Response
{
    if ($request->isMethod("POST")) {
        $titre = $request->request->get("titre");
        $auteur = $request->request->get("auteur");

        $livre->setTitre($titre);
        $livre->setAuteur($auteur);

        $em->flush();

        return $this->redirectToRoute('app_livre');
    }

    return $this->render('livre/formulaire.html.twig', [
        'livre' => $livre,
    ]);
}
#[Route('/livre/nouveau', name: 'app_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $em)
    {
        $livre = new Livre;

        // Ici la méthode createForm (méthode héritée des controllers symfony), me permet de générer un formulaire que j'ai défini via ma commande symfony console make:form
        // Ici, je crée un formulaire de type : LivreType
        // Egalement, symfony me permet de lier directement une entité à ce formulaire ! 
        // Le new Livre que j'ai créé ci dessus, est donc, directement rattaché aux champs du formulaire 
        // Quand je vais saisir le titre et l'auteur, ces informations là (si valide), seront directement insérées dans les propriétés de l'objet livre, je n'aurai pas besoin de manipuler les setLivre, setAuteur contrairement à la méthode précédente 'ajouter'
        $formLivre = $this->createForm(LivreType::class, $livre);
        // A partir de cette ligne $formLivre "contient" un formulaire entier qui ne me reste plus qu'à envoyer au render

        // handleRequest : Permet à la variable formLivre de gérer les informations envoyées par le navigateur
        // Cela va permettre de vérifier si l'information est bien postée via le formulaire
        $formLivre->handleRequest($request);

        if ($formLivre->isSubmitted() && $formLivre->isvalid()) {
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute("app_livre");
        }
        // dump($livre);

        return $this->render("livre/new.html.twig", ["formLivre" => $formLivre]);
    }


    // route de recherche de livre 

    #[Route('/search', name: 'search_books')] // Définit la route /search
    public function search(
        Request $request, // Pour récupérer les données de la requête
        LivreRepository $livreRepository // Injection du repository
    ): Response 
    {
        // Récupère le paramètre 'q' de l'URL
        $query = $request->query->get('q');
        
        // Utilise la méthode créée dans le repository
        $livres = $livreRepository->searchLivres($query);
        
        // Renvoie la vue avec les résultats
        return $this->render('livre/search.html.twig', [
            'livres' => $livres,
            'query' => $query
        ]);
    }
}
