<?php

/**
 * UserType.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder Form interface builder
     * @param array                $options Array of options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'username',
            TextType::class,
            [
              'label' => 'label.username',
              'required' => 'true',
              'attr' => ['max_length' => 20],
            ]
        );
        $builder->add(
            'password',
            PasswordType::class,
            [
              'label' => 'label.password',
              'required' => 'true',
            ]
        );
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver Options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Get block prefix.
     *
     * @return string Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
