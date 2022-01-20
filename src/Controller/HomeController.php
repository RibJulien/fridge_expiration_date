<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $quote = [
            "Maître Corbeau ne laissera plus tomber son fromage 🧀",
            "Blanche Neige aurait dû croquer dans une pomme moche 🍎",
            "Quand Peau d’Âne prépare sa galette, le prince n’en perd pas une miette 🥞",
            "Petit Poucet a compris combien le pain est précieux 🥖",
            "A minuit le potiron de Cendrillon fera un bon bouillon 🎃",
            "Le gaspi salsifi (Vous l'avez ? 👀)",
            "Pas chance, vous êtes tombé sur l'explication !"
        ];
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
