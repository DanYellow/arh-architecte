<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProjectRepository;

class FrontController extends AbstractController
{
    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"})
     */
    public function index(ProjectRepository $projectRepository, int $page = 1)
    {
        dd($projectRepository->findAll());
        return $this->render('front/index.html.twig', [
            "list_projects" => $projectRepository->findAll()
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
