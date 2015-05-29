<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Simusante\UserwidgetBundle\Listener;

use Claroline\CoreBundle\Event\DisplayWidgetEvent;
use Claroline\CoreBundle\Listener\NoHttpRequestException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service()
 */
class Listener
{
    private $container;
    /**
     * @param ContainerInterface $container
     * @DI\InjectParams({"container"=@DI\Inject("service_container")})
     */
    public function __contruct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @DI\Observe("widget_simusante_user_widget")
     *
     * @param DisplayWidgetEvent $event
     */
    public function onDisplay(DisplayWidgetEvent $event)
    {
        $twig = $this->container->get('templating');
        $content = $twig->render('SimusanteUserwidgetBundle::toto.html.twig');
        $event->setContent($content);
        $event->stopPropagation();
    }
}
