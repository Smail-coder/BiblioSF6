<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    #[Route('/test/accueil', name: 'app_accueil')]
    public function accueil()
    {

        $nombre = 45;
       $prenom ='bob';

        return $this->render("base.html.twig",[
            "nombre"=>$nombre,
            "prenom"=> $prenom
        ]);
    }
    #[Route('/test/heritage', name: 'pageheritage')]
    public function heritage()
    {
        return $this->render("test/heritage.html.twig");
    }

    #[Route('/test/transitif', name: 'app_transitif')]
    public function transitif()
    {
        return $this->render("test/transitif.html.twig");
    }

    #[Route('/test/tableau', name: 'app_tableau')]
    public function tableau()
    {

        $tab = ["jour" =>"06", "mois" => "fevrier", "annee" => "2025"];

        return $this->render("test/tableau.html.twig",

        [
            "tableau" => $tab,
            "tableau2" => [40, "test", true],
            "nombre" => 5,
            "chaine" =>"coucou",
        ]);
        
    }

    #[Route('/test/salutation/{prenom}', name: 'app_salutation')]
    public function salutation($prenom)
    {

       
        

        return  $this->render("test/salutation.html.twig",[
            "prenom"=>$prenom
        ]);
    }

    #[Route('/test/calcule/{nombre1}/{nombre2}', name: 'app_calcule')]
    public function calcule(int $nombre1, int $nombre2)
    {

        

        return $this->render("test/calcule.html.twig",[
            'nombre1' => $nombre1,
            'nombre2' => $nombre2
        ]);
    }
    #[Route('/test/calculator', name: 'app_calculator')]
    public function calculator(Request $request) : Response
    {
         
         $nombre1 = $request->query->get('nombre1', 2);
         $nombre2 = $request->query->get('nombre2', 2);
         $somme = $nombre1 + $nombre2; 
         $soustraction = $nombre1 - $nombre2;
         $multiplication = $nombre1 * $nombre2;
         $division = $nombre1 / $nombre2;

        return $this->render("test/calculator.html.twig",[
            'nombre1' => $nombre1,
            'nombre2' => $nombre2,
            'somme' => $somme,
            'soustraction'=> $soustraction,
            'multiplication' => $multiplication,
            'division' => $division
        ]);
    }

}
