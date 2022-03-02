<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProjectRepository;
use App\Repository\ProjectImageRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Project;
use App\Entity\ProjectImage;


class FrontController extends AbstractController
{
    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"})
     */
    public function index(ManagerRegistry $doctrine, int $page = 1)
    {
        $dql = 'SELECT p FROM App\Entity\Project p WHERE p.is_online = 1';

        $em = $doctrine->getManager();
        $query = $em->createQuery($dql)->getResult();
        
        return $this->render('front/index.html.twig', [
            "list_projects" => $query
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
