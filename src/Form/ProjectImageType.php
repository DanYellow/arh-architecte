<?php

namespace App\Form;

use App\Entity\ProjectImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\CallbackTransformer;

class ProjectImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '2048k',
                    // 'mimeTypes' => [
                    //     'application/pdf',
                    //     'application/x-pdf',
                    // ],
                    // 'mimeTypesMessage' => 'Please upload a valid PDF document',
                ])
            ],
            // 'data_class' => null
        ]);

        // $builder->get('name')
        //     ->addModelTransformer(new CallbackTransformer(
        //         function ($tagsAsArray) {

        //             // transform the array to a string
        //             // return new ProjectImage();
        //             return null;
        //         },
        //         function ($tagsAsString) {
        //             dd($tagsAsString);
        //             // return null;
        //             return new ProjectImage();
        //         }
        //     ))
        // ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectImage::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        // This name represents the form block name "address_form_(row|widget|...)"
        return 'project_image_form';
    }
}
