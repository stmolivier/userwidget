<?php

namespace Simusante\UserwidgetBundle\Manager;

use JMS\DiExtraBundle\Annotation as DI;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Simusante\UserwidgetBundle\Entity\UserwidgetConfig;

/**
 * @DI\Service("simusante.manager.userwidget")
 */
class UserwidgetManager
{
    /**
     * @var object object manager
     */
    private $om;
    /**
     * @DI\InjectParams({
     * "om" = @DI\Inject("claroline.persistence.object_manager"),
     * })
     * @param ObjectManager $om
     */
    public function __construct(
        ObjectManager $om
    )
    {
        $this->om = $om;
        $this->userWidgetConfigRepo = $om->getRepository('SimusanteUserwidgetBundle:UserwidgetConfig');
    }

    /**
     * @param WidgetInstance $widgetInstance
     * @return object|UserwidgetConfig
     */
    public function getUserwidgetConfig(WidgetInstance $widgetInstance)
    {
        $config = $this->userWidgetConfigRepo->findOneBy(
            array('widgetInstance' => $widgetInstance->getId())
        );

        //Create a default config
        if (is_null($config)) {
            $config = new UserwidgetConfig();
            $config->setWidgetInstance($widgetInstance);
            // TODO : improve this
            $config->setWorkspace(0);
            $this->persistUserwidgetConfig($config);
        }

        return $config;
    }
    /**
     * Save the widget configuration
     *
     * @param UserwidgetConfig $config
     */
    public function persistUserwidgetConfig(UserwidgetConfig $config)
    {
        $this->om->persist($config);
        $this->om->flush();
    }
}