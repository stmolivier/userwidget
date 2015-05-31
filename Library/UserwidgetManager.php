<?php

namespace Simusante\UserwidgetBundle\Library;

use JMS\DiExtraBundle\Annotation as DI;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;

/**
 * Definition of the service
 */
/**
 * @DI\Service("simusante.manager.user_widget")
 */
class UserwidgetManager
{
    private $om;

    /**
     * @DI\InjectParams({
     *    "om" = @DI\Inject("claroline.persistence.object_manager")
     * })
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
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
}
