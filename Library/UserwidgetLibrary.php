<?php

namespace Simusante\UserwidgetBundle\Library;

use JMS\DiExtraBundle\Annotation as DI;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;
use Simusante\UserwidgetBundle\Entity\UserwidgetConfig;

/**
 * Definition of the service
 */
/**
 * @DI\Service("simusante.manager.userwidgetlib")
 */
class UserwidgetLibrary
{
    private $om;
    /**
     * User repository, retrieved from the core, through the OM
     */
    private $userRepo;
    /**
     * Workspace repository, retrieved from the core, through the OM
     */
    private $wsRepo;
    private $userwidgetConfigRepo;

    /**
     * @DI\InjectParams({
     *    "om" = @DI\Inject("claroline.persistence.object_manager")
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
        //user repo access
        $this->userRepo           = $om->getRepository('ClarolineCoreBundle:User');
        //ws repo access
        $this->wsRepo            = $om->getRepository('ClarolineCoreBundle:Workspace\Workspace');//$om->getRepository(Entity)
        $this->userwidgetConfigRepo = $om->getRepository('ClarolineCursusBundle:CoursesWidgetConfig');
    }

    /**
     * retrieve the widget instance
     * @param WidgetInstance $config
     * @return object
     */
    public function getConfig(WidgetInstance $config)
    {
        return $this->om
            ->getRepository('SimusanteUserwidgetBundle:Config')
            ->findOneBy(array('widgetInstance' => $config));
    }

    public function getUserwidgetConfiguration(WidgetInstance $widgetInstance)
    {
        $config = $this->userwidgetConfigRepo->findOneBy(
            array('widgetInstance' => $widgetInstance->getId())
        );

        if (is_null($config)) {
            $config = new UserwidgetConfig();
            $config->setWidgetInstance($widgetInstance);
            $this->persistUserwidgetConfiguration($config);
        }

        return $config;
    }

    public function persistUserwidgetConfiguration(UserwidgetConfig $config)
    {
        $this->om->persist($config);
        $this->om->flush();
    }

    public function getUsersFromWorkspaces(
        $workspace,
        $orderedBy,
        $order,
        $page,
        $max
    )
    {
        $ws = $this->wsRepo->findOneByCode($workspace);
        //Get user array from query
        $users = $this->userRepo->findUsersByWorkspace(array($ws));
    }
}
