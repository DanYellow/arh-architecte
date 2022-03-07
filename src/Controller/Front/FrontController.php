<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProjectRepository;
use App\Repository\ProjectImageRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Project;
use App\Entity\ProjectImage;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProjectRepository $projectRepositiory, Request $request)
    {
        $currentPage = (int)$request->query->get('page', 1);
        $numberItemsPerPage = 1;
        $listProjects = $projectRepositiory->getProjectsForPage($currentPage, $numberItemsPerPage);

        $nbTotalProjects = $projectRepositiory->getTotalProjects();

        return $this->render(
            'front/index.html.twig',
            compact('listProjects', 'nbTotalProjects', 'currentPage', 'numberItemsPerPage')
        );
    }

    /**
     * @Route("/a-propos", name="a_propos")
     */
    public function about(ProjectRepository $projectRepositiory)
    {
        $listProjects = $projectRepositiory->getBiographyProjects();
        return $this->render(
            'front/about.html.twig', 
            compact('listProjects')
        );
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
