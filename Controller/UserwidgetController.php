<?php

namespace Simusante\UserwidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Simusante\UserwidgetBundle\Form\ConfigType;
use Simusante\UserwidgetBundle\Entity\Config;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;

/**
 * userwidget controller.
 *
 */
class UserwidgetController extends Controller
{
    /**
     * Object manager
     */
    private $om;
    /**
     * User repository, retrieved from the core, through the OM
     */
    private $userRepo;
    /**
     * Role repository, retrieved from the core, through the OM
     */
    private $roleRepo;  //necessary to retrieve the role with right format
    /**
     * Workspace repository, retrieved from the core, through the OM
     */
    private $wsRepo;
    /**
     * @DI\InjectParams({
     *      "om"                 = @DI\Inject("claroline.persistence.object_manager")
     * })
     */
    public function __construct(
        ObjectManager $om
    )
    {
        //Object manager initialization
        $this->om                 = $om;
        //user repo access
        $this->userRepo           = $om->getRepository('ClarolineCoreBundle:User');
        //role repo access
        $this->roleRepo           = $om->getRepository('ClarolineCoreBundle:Role');
        //ws repo access
        $this->wsRepo            = $om->getRepository('ClarolineCoreBundle:Workspace\Workspace');//$om->getRepository(Entity)
    }

    /**
     * action called by the onDisplay method in the Listener
     */
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayUserAction()
    {
        /*
        //Get a Role object
        $role = $this->roleRepo->findOneByName('ROLE_WS_CREATOR');
        //Get the list of users with the role $role
        $users = $this->userRepo->findByRoles(array($role));
        */
        $workspace = $this->wsRepo->findOneByCode('ea1');
      /*  var_dump($workspace);
        die();*/
        //Get user array from query
        $users = $this->userRepo->findUsersByWorkspace(array($workspace));
        //template rendering
        return $this->render('SimusanteUserwidgetBundle::userwidget.html.twig', array('users'=> $users));
    }

    /**
     * action called by the onConfigure method in the Listener for form POST
     * AJAX response
     */
    /**
     * @EXT\Route(
     *     "/simple_text_update/config/{widget}",
     *     name="simusante_userwidget_config_update"
     * )
     * @EXT\Method("POST")
     */
    public function configureUserwidget(WidgetInstance $widget)
    {
        //Authorization to access this widget config
        if (!$this->get('security.authorization_checker')->isGranted('edit', $widget)) {
            throw new AccessDeniedException();
        }

        $userwidgetConfig = $this->get('simusante.manager.user_widget')->getConfig($widget);
        $form = $this->container->get('form.factory')->create(new ConfigType, new Config());
        $form->bind($this->getRequest());

        if ($userwidgetConfig) {
            if ($form->isValid()) {
                $userwidgetConfig->setWorkspace($form->get('workspace')->getData());
            } else {
                return $this->render(
                    'SimusanteUserwidgetBundle::widgetformconfiguration.html.twig',
                    array(
                        'form' => $form->createView(),
                        'isAdmin' => $widget->isAdmin(),
                        'config' => $widget
                    )
                );
            }
        } else {
            if ($form->isValid()) {
                $userwidgetConfig = new Config();
                $userwidgetConfig->setWidgetInstance($widget);
                $userwidgetConfig->setWorkspace($form->get('workspace')->getData());
            } else {
                return $this->render(
                    'SimusanteUserwidgetBundle::widgetformconfiguration.html.twig',
                    array(
                        'form' => $form->createView(),
                        'isAdmin' => $widget->isAdmin(),
                        'config' => $widget
                    )
                );
            }
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($userwidgetConfig);
        $em->flush();

        return new Response('', 204);
    }
}