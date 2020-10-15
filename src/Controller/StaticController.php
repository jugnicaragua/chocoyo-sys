<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{

    /**
     * @Route("/eula", name="eula", methods={"GET"})
     */
    public function eula(){
        return $this->render('static/eula.html.twig', []);
    }

    /**
     * @Route("/pp", name="pp", methods={"GET"})
     */
    public function pp(){
        return $this->render('static/pp.html.twig', []);
    }
}

