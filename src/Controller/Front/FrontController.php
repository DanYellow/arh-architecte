<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/{page}", name="index")
     */
    public function index(int $page)
    {
        return $this->render('front/index.html.twig', [
            "list_projects" => ["", "", ""]
        ]);
    }

    /**
     * @Route("/a-propos", name="a_propos")
     */
    public function a_propos()
    {
        return $this->render('front/a-propos.html.twig', [
            "list_projects" => ["", "", ""]
        ]);
    }
}
