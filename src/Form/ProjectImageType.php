<?php

namespace App\Form;

use App\Entity\ProjectImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

use Symfony\Component\Form\CallbackTransformer;

class ProjectImageType extends AbstractType
{
    private const MIME_TYPES = [
        'image/png',
        'image/jpg',
        'image/jpeg',
        'image/heif',
        'image/webp'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new Image([
                    'maxSize' => '2048k',
                    'mimeTypes' => ProjectImageType::MIME_TYPES,
                    'mimeTypesMessage' => 'Merci de bien vouloir uploader une image correcte',
                ])
            ],

            // 'data_class' => null,
        ]);

        $builder->add('position', NumberType::class, [
            'mapped' => true,
            'required' => false,
        ]);

        $builder->get('position')
            ->addModelTransformer(new CallbackTransformer(
                function () {
                    return null;
                },
                function ($position) {
                    return (int) $position;
                }
            ));

        $builder->add('delete', FileType::class, [
            'mapped' => false,
            'required' => false,
        ]);
        $builder->get('delete')
            ->addModelTransformer(new CallbackTransformer(
                function () {
                    return null;
                },
                function ($isDeleted) {
                    return $isDeleted;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectImage::class,
            "allow_extra_fields" => true,
            'attr' => [
                "mimeTypes" => ProjectImageType::MIME_TYPES,
                'block_name' => 'media_proto'
            ]
        ]);
    }

    public function getBlockPrefix(): string
    {
        // This name represents the form block name "address_form_(row|widget|...)"
        return 'project_image_form';
    }
}
