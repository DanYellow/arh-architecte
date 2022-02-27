<?php

namespace App\Controller\Admin;

use App\Controller\Admin\ProjectCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use App\Entity\Project;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractDashboardController
{
    // #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProjectCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTranslationDomain('admin')
            ->setTitle('Armelle Richard–Hue Architecte');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Projets', 'fas fa-list', Project::class);
        // yield MenuItem::linkToCrud('ProjectImage', 'fas fa-list', ProjectImage::class);

        yield MenuItem::section();
        yield MenuItem::linkToUrl('Accéder au site', "fa fa-anchor", '/');
        yield MenuItem::section();
        // yield MenuItem::linkToExitImpersonation('Stop impersonation', 'fa fa-exit');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-running');
    }
}
