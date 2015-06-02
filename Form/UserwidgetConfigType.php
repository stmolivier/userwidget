<?php

namespace Simusante\UserwidgetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserwidgetConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'workspace',
                'entity',
                array(
                    'class' => 'ClarolineCoreBundle:Workspace\Workspace',
                    'property' => 'name',
                    'required' => true
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Simusante\UserwidgetBundle\Entity\UserwidgetConfig',
            //manually added to provide translation strings
            'translation_domain' => 'userwidget'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'simusante_userwidgetbundle_userwidgetconfig';
    }
}
