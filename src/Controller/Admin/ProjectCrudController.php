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
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\String\Slugger\AsciiSlugger;

use App\Admin\Field\UploadField;


use Symfony\Component\HttpFoundation\File\File;


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

        $list_images = $entityInstance->getProjectImages();
        if (count($list_images) === 0) {
            $entityInstance->setIsOnline(false);
            $entityInstance->setInBiography(false);
        } else {
            $slugger = new AsciiSlugger();
            foreach ($list_images as $index => $value) {

                $filecache = $value->getName();
                $file = new File($filecache);

                dd($file->getPath());

                // $newFilename = "{$slugger->slug(strtolower($entityInstance->getName()))}-image-{$index}.{$file->guessExtension()}";
                // // dd($newFilename);
                // $value->setName($newFilename);
                // $value->setPosition($index);

                // move_uploaded_file($file, "uploads/projects/{$newFilename}");
            }
        }

        // parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Project) return;

        $list_images = $entityInstance->getProjectImages();
        if (count($list_images) === 0) {
            $entityInstance->setIsOnline(false);
            $entityInstance->setInBiography(false);
        } else {
            $slugger = new AsciiSlugger();
            foreach ($list_images as $index => $value) {
                dd($value->getData());
                // $filecache = $value->getName();
                // $file = new File($filecache);

                // $newFilename = "{$slugger->slug(strtolower($entityInstance->getName()))}-image-{$index}.{$file->guessExtension()}";
                // // dd($newFilename);
                // $value->setName($newFilename);
                // $value->setPosition($index);

                // move_uploaded_file($file, "uploads/projects/{$newFilename}");
            }
        }

        // parent::persistEntity($em, $entityInstance);
    }

    // function updateEntity(EntityManagerInterface $entityManager, $entityInstance)

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the visible title at the top of the page and the content of the <title> element
            // it can include these placeholders:
            //   %entity_name%, %entity_as_string%,
            //   %entity_id%, %entity_short_id%
            //   %entity_label_singular%, %entity_label_plural%
            ->setPageTitle('index', 'Liste projets')

            // you can pass a PHP closure as the value of the title
            ->setPageTitle('new', "Nouveau projet")
            ->setPageTitle('edit', fn (Project $category) => sprintf('Modifier <b>%s</b>', $category->getName()))
            ->addFormTheme('foo.html.twig')
        ;
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

        yield BooleanField::new('in_biography', "Afficher dans la biographie")
            ->renderAsSwitch(false)
            ->onlyOnForms()
            ->setHelp("Ne sera pas mis en ligne en absence d'images");
        yield BooleanField::new('in_biography', "Affiché dans la biographie")
            ->renderAsSwitch(false)
            ->hideOnForm();

        yield ChoiceField::new('year', "Année")
            ->setChoices((array) $list_years);
        yield CollectionField::new('projectImages', 'Offres')
            ->setEntryType(ProjectImageType::class)
            ->renderExpanded()
            ->onlyOnForms()
            ->setEntryIsComplex(false);
            // yield AssociationField::new('projectImages', "Photographies");
            // yield UploadField::new('projectImages', 'Photos')
        ;
        //     // ->setEntryType(ProjectImageType::class)
        //     // ->renderExpanded()
        //     ->onlyOnForms();
    }

    
}
