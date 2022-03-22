<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use \Liip\ImagineBundle\Imagine\Cache\CacheManager;


class SitemapController extends AbstractController
{
    #[Route('/sitemap', name: 'app_sitemap', defaults: ['_format' => "xml"]
    )]
    public function index(Request $request, ProjectRepository $projectRepository, CacheManager $imagineCacheManager): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        // On ajoute les URLs "statiques"
        $urls[] = ['loc' => $this->generateUrl('index')];
        $urls[] = ['loc' => $this->generateUrl('a_propos')];

        $listOnlineProjects = $projectRepository->getProjectsForPage(1, 1000);
        foreach ($listOnlineProjects as $project) {
            $listImages = [];
            foreach ($project->getProjectImages() as $image) {
                $resolvedPath = $imagineCacheManager->getBrowserPath("uploads/projects/{$image->getName()}", 'details_project_image');
                $listImages[] = [
                    'title' => $image->getName(),
                    'loc' => $resolvedPath
                ];
            }

            $now = $project->getUpdatedAt();
            if (is_null($now)) {
                $now = new \DateTimeImmutable();
            }

            $urls[] = [
                'loc' => $this->generateUrl('project', [
                    'param' => $project->getSlug(),
                ]),
                'lastmod' => $now->format('Y-m-d'),
                'image' => $listImages
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
        );

        // Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;
    }
}
