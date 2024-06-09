<?php

/**
 * AdCategoryType.
 */

namespace App\Form\Type;

use App\Entity\AdCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AdCategoryType.
 */
class AdCategoryType extends AbstractType
{
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder Interface builder
     * @param array                $options Array of options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label.name',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver Option Resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => AdCategory::class]);
    }

    /**
     * Get block prefix.
     *
     * @return string Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'adCategory';
    }
}
