<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;
use App\Entity\Livre;
use App\Form\LivreType;


class livreController
{
    /**
     * @route("/livre/liste", name="livre_liste")
     */
    public function liste(Request $requette, RegistryInterface $doctrine, FormFactoryInterface $formFactory, Environment $twig)
    {
        $livres = $doctrine->getRepository(Livre::class)->findAll();

        $form = $formFactory->createBuilder(LivreType::class, $livres[0])->getForm();
        $form->handleRequest($requette);
        /*$requette->setLocale('fr');
        $locale = $requette->getLocale();
        var_dump($locale);*/
        if($form->isSubmitted() && $form->isValid()){
            $doctrine->getEntityManager()->flush();
        }

        /*return new Response(
            $twig->render('livre/liste.html.twig', compact('livres')
            ));*/
        return new Response(
            $twig->render('livre/liste.html.twig', [
                    'livres' => $livres,
                    'form' => $form->createView()
                ]
            ));
    }
}