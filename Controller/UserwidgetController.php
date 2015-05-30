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
    /*
     * Object manager
     */
    private $om;
    /*
     * User repository, retrieved from the core, through the OM
     */
    private $userRepo;
    /*
     * Role repository, retrieved from the core, through the OM
     */
    private $roleRepo;  //necessary to retrieve the role with right format
    /*
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
        $workspace = $this->wsRepo->findOneByName('ws2');
        //Get user array from query
        $users = $this->userRepo->findUsersByWorkspace(array($workspace));
        //template rendering
        return $this->render('SimusanteUserwidgetBundle::toto.html.twig', array('users'=> $users));
    }
}