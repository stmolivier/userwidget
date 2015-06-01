<?php

namespace Simusante\UserwidgetBundle\Manager;

use JMS\DiExtraBundle\Annotation as DI;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Simusante\UserwidgetBundle\Entity\UserwidgetConfig;

/**
 * @DI\Service("simusante.manager.userwidget")
 */
class CursusManager
{
    /**
     * @var object widget instance
     */
    private $widgetInstance;
    /**
     * @var object object manager
     */
    private $om;
    /**
     * @DI\InjectParams({
     * "om" = @DI\Inject("claroline.persistence.object_manager"),
     * })
     */
    public function __construct(
        ObjectManager $om
    )
    {
        $this->userWidgetConfigRepo = $om->getRepository('SimusanteUserwidgetBundle:UserwidgetConfig');
    }

    /**
     *
     */
    public getUserwidgetConfiguration(WidgetInstance $widgetInstance)
    {
        $config = $this->userWidgetConfigRepo->findOneBy(
            array('widgetInstance' => $widgetInstance->getId())
        );
        //Create a default config
        if (is_null($config)) {
            $config = new UserwidgetConfig();
            $config->setWidgetInstance($widgetInstance);
            $this->persistUserwidgetConfiguration($config);
        }

        return $config;
    }
    /**
     * Save the widget configuration
     *
     * @param UserwidgetConfig $config
     * @see persistUserwidgetConfiguration
     */
    public function persistUserwidgetConfiguration(UserwidgetConfig $config)
    {
        $this->om->persist($config);
        $this->om->flush();
    }
}