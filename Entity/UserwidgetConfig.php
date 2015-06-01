<?php

namespace Simusante\UserwidgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserwidgetConfig
 *
 * @ORM\Table(name="simusante_userwidget_config")
 * @ORM\Entity
 */
class UserwidgetConfig
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="workspace", type="string", length=255)
     */
    private $workspace;
    /**
     * @ORM\OneToOne(targetEntity="Claroline\CoreBundle\Entity\Widget\WidgetInstance")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $widgetInstance;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set workspace
     *
     * @param string $workspace
     *
     * @return UserwidgetConfig
     */
    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;

        return $this;
    }

    /**
     * Get workspace
     *
     * @return string
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * Set widgetInstance
     *
     * @param \Claroline\CoreBundle\Entity\Widget\WidgetInstance $widgetInstance
     *
     * @return UserwidgetConfig
     */
    public function setWidgetInstance(\Claroline\CoreBundle\Entity\Widget\WidgetInstance $widgetInstance = null)
    {
        $this->widgetInstance = $widgetInstance;

        return $this;
    }

    /**
     * Get widgetInstance
     *
     * @return \Claroline\CoreBundle\Entity\Widget\WidgetInstance
     */
    public function getWidgetInstance()
    {
        return $this->widgetInstance;
    }
}
