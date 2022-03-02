<?php

namespace App\Controller\Admin;

use App\Entity\ProjectImage;
use App\Entity\Project;

use App\Form\ProjectImageType;

use Doctrine\ORM\EntityManagerInterface;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\HttpFoundation\File\File;

use Doctrine\Common\Collections\ArrayCollection;


use App\Service\FileUploader;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $product = new Project();
        $product->setName("Test 112");

        return $product;
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Project) return;

        $list_project_images = $entityInstance->getProjectImages();

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Project) return;

        $list_project_images = $entityInstance->getProjectImages()->toArray();
        // usort($list_project_images, function ($a, $b) {
        //     return ($a->getPosition() > $b->getPosition()) ? 1 : -1;
        // });

        // dd($list_project_images);
        
        $files = parent::getContext()->getRequest()->files;
        $list_images_uploaded = array_values($files->get('Project')['projectImages']);
        $request = parent::getContext()->getRequest()->request;
        
        $listImageToDelete = array_values($request->all('Project')["projectImages"]);
        // dd($list_project_images,  $listImageToDelete, $list_images_uploaded);

        // https://github.com/EasyCorp/EasyAdminBundle/issues/2662
        // https://stackoverflow.com/questions/34967795/order-of-symfony-form-collectiontype-field


        if (count($list_images_uploaded) === 0) {
            $entityInstance->setIsOnline(false);
            $entityInstance->setInBiography(false);
        } else {
            $slugger = new AsciiSlugger();

            foreach ($list_project_images as $index => $value) {
                $pos = $index;
                $value = $list_project_images[$pos];
                $file = $list_images_uploaded[$pos]["name"];

                $hasToBeDeleted = false;
                if (array_key_exists('delete', $listImageToDelete[$pos])) {
                    $hasToBeDeleted = $listImageToDelete[$pos]["delete"] == "on";
                }

                if (is_null($file)) {
                    if ($hasToBeDeleted == true) {
                        $value->removeUpload($this->getParameter('projects_images_directory'));
                        $qb = $em->createQueryBuilder()->delete("App\Entity\ProjectImage", "pi")->where('pi.id = :id')->setParameter("id", $value->getId())->getQuery();
                        $qb->execute();
                    } else if (is_null($value->getName())) {
                        $entityInstance->removeProjectImage($value);
                    }
                } else {
                    $oldFileName = $value->getName();
                    $uniqueid = uniqid();
                    $newFilename = "{$slugger->slug(strtolower($entityInstance->getName()))}-{$uniqueid}.{$file->guessExtension()}";
                    if (!is_null($oldFileName)) {
                        $oldName = explode(".", $oldFileName);
                        $oldName = array_slice($oldName, 0, -1);
                        $newFilename = implode("", $oldName);
                        $newFilename = "{$newFilename}.{$file->guessExtension()}";

                        $value->removeUpload($this->getParameter('projects_images_directory'));
                    }

                    $value->setName($newFilename);
                    $file->move(
                        $this->getParameter('projects_images_directory'),
                        $newFilename
                    );
                }
            }
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the visible title at the top of the page and the content of the <title> element
            // it can include these placeholders:
            //   %entity_name%, %entity_as_string%,
            //   %entity_id%, %entity_short_id%
            //   %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste projets')
            ->setEntityLabelInSingular('Projet')
            // you can pass a PHP closure as the value of the title
            ->setPageTitle('new', "Nouveau projet")
            ->setPageTitle('edit', fn (Project $category) => sprintf('Modifier <b>%s</b>', $category->getName()))
            ->addFormTheme('admin/field/project-image.html.twig');
        // ->setPageTitle('edit', "Modifier %entity_is_online%");
    }

    public function configureFields(string $pageName): iterable
    {
        $current_year = date('Y');
        $list_years_raw = range($current_year + 1, 2010);

        $list_years = new \stdClass();
        foreach ($list_years_raw as $key => $value) {
            $list_years->$value = $value;
        }

        yield IdField::new('id')->hideOnForm()->hideOnDetail();
        yield Field::new('name', "Nom");
        yield DateTimeField::new('created_at', 'Crée le')->hideOnForm();

        yield BooleanField::new('is_online', "Mettre en ligne")
            ->renderAsSwitch(false)->onlyOnForms()
            ->setHelp("Ne sera pas mis en ligne en absence d'images");
        yield BooleanField::new('is_online', "Est en ligne")->renderAsSwitch(false)->hideOnForm();
        yield BooleanField::new('is_online', "Est en ligne")
            ->renderAsSwitch(true)
            ->hideOnForm()
            ->setFormTypeOptions(['disabled' => 'disabled', 'title' => "Ne sera pas pris en compte s'il n'a pas d'images"]);

        yield BooleanField::new('in_biography', "Afficher la première image dans la biographie")
            ->renderAsSwitch(false)
            ->onlyOnForms();
        yield BooleanField::new('in_biography', "Affiché dans la biographie")
            ->renderAsSwitch(false)
            ->hideOnForm();

        yield ChoiceField::new('year', "Année")
            ->setChoices((array) $list_years);
        yield CollectionField::new('projectImages', 'Images associées')
            ->setEntryType(ProjectImageType::class)
            ->renderExpanded()
            ->onlyOnForms();
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js')
            ->addJsFile('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js')
            ->addJsFile('ressources/js/reorder-project-images.js');
    }
}
