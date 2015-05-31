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
use Claroline\CoreBundle\Event\ConfigureWidgetEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormFactory;
use Simusante\UserwidgetBundle\Entity\Config;
use Simusante\UserwidgetBundle\Form\ConfigType;

/**
 * @DI\Service()
 */
class UserwidgetListener
{
    /**
     * Container of the displayed data
     * @var
     */
    private $container;
    /**
     * @var
     */
    private $httpKernel;
    /**
     * @var
     */
    private $request;
    /**
     * @var
     */
    private $formFactory;
    /**
     * @var
     */
    private $templating;
    /**
     * @param ContainerInterface $container
     * @DI\InjectParams({
     *  "container"=@DI\Inject("service_container"),
     *  "requestStack"=@DI\Inject("request_stack"),
     *  "httpKernel"=@DI\Inject("http_kernel"),
     *  "templating"  = @DI\Inject("templating"),
     *  "formFactory" = @DI\Inject("form.factory")
     * })
     */
    public function __contruct(
        ContainerInterface $container,
        requestStack $requestStack,
        HttpKernelInterface $httpKernel,
        FormFactory $formFactory,
        TwigEngine $templating
    )
    {
        $this->container = $container;
        $this->httpKernel = $httpKernel;
        $this->request = $requestStack->getCurrentRequest();
        $this->templating = $templating;
        $this->formFactory = $formFactory;
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
    /*
     * call to controller method. Send param & event
     */
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

    /**
     * Widget configuration
     */
    /**
     * @DI\Observe("widget_simusante_user_widget_configuration")
     */
    public function onConfigure(ConfigureWidgetEvent $event)
    {
        //retrieve the instance of the event
        $instance = $event->getInstance();
        //
       // $config = $this->rssManager->getConfig($instance);

        $config = null;
        if ($config === null) {
            $config = new Config();
        }
        //Set the config form
        $form = $this->formFactory->create(new ConfigType, $config);

        $content = $this->templating->render(
            'SimusanteUserwidgetBundle::widgetformconfiguration.html.twig',
            array(
                'form' => $form->createView(),
                'isAdmin' => $instance->isAdmin(),
                'config' => $instance
            )
        );
        $event->setContent($content);
    }
}
