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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service()
 */
class Listener
{
    /*
     * Container of the data
     */
    private $container;
    /*
     *
     */
    private $httpKernel;
    /*
     *
     */
    private $request;
    /**
     * @param ContainerInterface $container
     * @DI\InjectParams({
     *  "container"=@DI\Inject("service_container"),
     *  "requestStack"=@DI\Inject("request_stack"),
     *  "httpKernel"=@DI\Inject("http_kernel")
     * })
     */
    public function __contruct(ContainerInterface $container, requestStack $requestStack, HttpKernelInterface $httpKernel)
    {
        $this->container = $container;
        $this->httpKernel = $httpKernel;
        $this->request = $requestStack->getCurrentRequest();
    }
    /*
     * Listener to the widget display
     */
    /**
     * @DI\Observe("widget_simusante_user_widget")
     *
     * @param DisplayWidgetEvent $event
     */
    public function onDisplay(DisplayWidgetEvent $event)
    {
//        $widgetInstance = $event->getInstance();
        $params = array();
        $params['_controller'] = 'SimusanteUserwidgetBundle:Userwidget:displayUser';
//        $params['widgetInstance'] = $widgetInstance->getId();
        $this->redirect($params, $event);
    }

    private function redirect($params, $event)
    {
        // ???
        $subRequest = $this->request->duplicate(array(), null, $params);
        //Create a response
        $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        //fill the event with the content
        $event->setContent($response->getContent());
        $event->stopPropagation();
    }
}
