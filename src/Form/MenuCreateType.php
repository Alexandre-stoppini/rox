<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MenuCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'required'=> true,
                'constraints' => [
                    new File([
                        'maxSize'=> '10240k',
                        'mimeTypes'=>[
                            'application/pdf',
                            'application/x-pdf'
                        ],
                        'mimeTypesMessage' => 'Entre ici un fichier pdf valide'
                    ])
                ]
            ])
            ->add('name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
