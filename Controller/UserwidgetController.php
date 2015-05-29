<?php

namespace Simusante\UserwidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Claroline\CoreBundle\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * userwidget controller.
 *
 */
class UserwidgetController extends Controller
{
    private $om;
    private $userRepo;
    private $roleRepo;  //nécessaire pour récupérer le role sous le bon format
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayUserAction()
    {
        /*
        //Récupère un objet Role
        $role = $this->roleRepo->findOneByName('ROLE_WS_CREATOR');
        //Récupère la liste des utilisateur ayant le role
        $users = $this->userRepo->findByRoles(array($role));
        */
        $workspace = $this->wsRepo->findOneByName('ws2');
        //Récupère un objet Role
        $users = $this->userRepo->findUsersByWorkspace(array($workspace));

        return $this->render('SimusanteUserwidgetBundle::toto.html.twig', array('users'=> $users));
    }
}