<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProjectRepository $projectRepository, Request $request)
    {
        $currentPage = (int)$request->query->get('page', 1);
        $numberItemsPerPage = 3;
        $listProjects = $projectRepository->getProjectsForPage($currentPage, $numberItemsPerPage);

        $nbTotalProjects = $projectRepository->getTotalProjects();

        return $this->render(
            'front/index.html.twig',
            compact('listProjects', 'nbTotalProjects', 'currentPage', 'numberItemsPerPage')
        );
    }

    /**
     * @Route("/a-propos", name="a_propos")
     */
    public function about(ProjectRepository $projectRepository)
    {
        $listProjects = $projectRepository->getBiographyProjects();
        return $this->render(
            'front/about.html.twig', 
            compact('listProjects')
        );
    }

    /**
     * @Route("/projet/{id}", name="project")
     */
    public function a_project(ProjectRepository $projectRepository, string $id)
    {
        $id = (int)$id;
        $project = $projectRepository->findOneById($id);
        if(!is_null($project)) {
            return $this->render('front/details-project.html.twig', compact('project'));
        }
        
        // dd(is_null($project));
        $listProjects = $projectRepository->getBiographyProjects();
        return $this->render('front/missing-project.html.twig', compact('listProjects'));
    }
}
