<?php

namespace Simusante\UserwidgetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //warning : remove widgetInstance from the generated form :
        // php app/console doctrine:generate:form SimusanteUserwidgetBundle:Config
        $builder
            ->add('workspace', 'text')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Simusante\UserwidgetBundle\Entity\Config',
            //manually added to provide translation strings
            'translation_domain' => 'userwidget'
        ));
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'simusante_userwidgetbundle_config';
    }
}
