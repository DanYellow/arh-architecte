<?php

namespace App\Controller\Admin;

use App\Controller\Admin\ProjectCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use App\Entity\Project;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractDashboardController
{
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
        yield MenuItem::subMenu('Projets', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Afficher projets', 'fas fa-eye', Project::class),
            MenuItem::linkToCrud('Créer projet', 'fas fa-plus', Project::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::section();
        yield MenuItem::linkToUrl('Accéder au site', "fa fa-anchor", '/');
        yield MenuItem::section();
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-running');
    }
}
