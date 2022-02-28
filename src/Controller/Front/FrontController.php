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
        // dd($projectRepository->findAll());
        return $this->render('front/index.html.twig', [
            "list_projects" => $projectRepository->findAll()
        ]);
    }

    /**
     * @Route("/a-propos", name="a_propos")
     */
    public function about()
    {
        return $this->render('front/about.html.twig', [
            "list_projects" => ["", "", ""]
        ]);
    }

    /**
     * @Route("/projet/{id}", name="project", requirements={"page"="\d+"})
     */
    public function a_project(ProjectRepository $projectRepository, int $id)
    {
        $project = $projectRepository->findOneById($id);
        return $this->render('front/details-project.html.twig', [
            "project" => $project
        ]);
    }
}
