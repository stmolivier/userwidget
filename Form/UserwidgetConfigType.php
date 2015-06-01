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
                'workspace', 'text'
                /*'entity',
                array(
                    'class' => 'ClarolineCursusBundle:Cursus',
                    'query_builder' => function (EntityRepository $er) {

                        return $er->createQueryBuilder('c')
                            ->where('c.course IS NULL')
                            ->orderBy('c.title', 'ASC');
                    },
                    'property' => 'titleAndCode',
                    'required' => false,
                    'label' => 'cursus'
                )*/
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
