<?php

namespace Simusante\UserwidgetBundle\Form;

use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Repository\WorkspaceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserwidgetConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       /* $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($user) {

            }
        );*/
        $builder->add(
            'workspace',
            'entity',   //to have a listbox of entity items
                array(
                    'class' => 'ClarolineCoreBundle:Workspace\Workspace',
                    'query_builder' => function(WorkspaceRepository $wr) {
                        return $wr->createQueryBuilder('w')
                            ->where('w.isPersonal = 0');
                    },
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
