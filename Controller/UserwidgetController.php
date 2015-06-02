<?php

namespace Simusante\UserwidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Claroline\CoreBundle\Entity\Widget\WidgetInstance;
use Claroline\CoreBundle\Manager\UserManager;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Simusante\UserwidgetBundle\Form\UserwidgetConfigType;
use Simusante\UserwidgetBundle\Manager\UserwidgetManager;
use Claroline\CoreBundle\Manager\FacetManager;
use Simusante\UserwidgetBundle\Entity\UserwidgetConfig;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * userwidget controller.
 *
 */
class UserwidgetController extends Controller
{
    /**
     * Object manager
     */
    private $om;

    /**
     * Role repository, retrieved from the core, through the OM
     */
    private $roleRepo;  //necessary to retrieve the role with right format

    /**
     * @var
     */
    private $formFactory;
    /**
     * manager for the widget
     * @var
     */
    private $userwidgetManager;
    private $userManager;
    private $request;
    private $facetManager;
    /**
     * @DI\InjectParams({
     *      "om"                    = @DI\Inject("claroline.persistence.object_manager"),
     *     "formFactory"            = @DI\Inject("form.factory"),
     *      "userwidgetManager"     = @DI\Inject("simusante.manager.userwidget"),
     *      "userManager"           = @DI\Inject("claroline.manager.user_manager"),
     *      "requestStack"          = @DI\Inject("request_stack"),
     *     "facetManager"           = @DI\Inject("claroline.manager.facet_manager"),
     * })
     */
    public function __construct(
        ObjectManager $om,
        FormFactory $formFactory,
        UserwidgetManager $userwidgetManager,
        UserManager $userManager,
        FacetManager $facetManager,
        RequestStack $requestStack
    )
    {
        //Object manager initialization
        $this->om                = $om;
        //role repo access
        //$this->roleRepo           = $om->getRepository('ClarolineCoreBundle:Role');
        //user repo access
        $this->userManager       = $userManager;
        $this->formFactory       = $formFactory;
        $this->facetManager = $facetManager;
        $this->userwidgetManager = $userwidgetManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    /******************
     * Widget methods *
     ******************/

    /**
     * called on onDisplay Listener method
     * call the userwidgetDisplay.html.twig, which calls the dynamic list generator twig file userwidgetList.html.twig
     */
    /**
     * @EXT\Route(
     *     "/userwidget/widget/{widgetInstance}",
     *     name="simusante_userwidget",
     *     options={"expose"=true}
     * )
     * @EXT\Template("SimusanteUserwidgetBundle:Widget:userwidgetDisplay.html.twig")
     */
    public function userwidgetDisplayAction(WidgetInstance $widgetInstance)
    {
        return array('widgetInstance' => $widgetInstance);
      /*  //Get a Role object
        $role = $this->roleRepo->findOneByName('ROLE_WS_CREATOR');
        //Get the list of users with the role $role
        $users = $this->userRepo->findByRoles(array($role));

        $workspace = $this->wsRepo->findOneByCode('ea1');
        //Get user array from query
        $users = $this->userRepo->findUsersByWorkspace(array($workspace));
        //template rendering
        return $this->render('SimusanteUserwidgetBundle:Widget:userwidgetDisplay.html.twig', array('users'=> $users));*/
    }
    /**
     * List of users from workspace
     * @param string  $search
     * @param integer $page
     * @param integer $max
     * @param string  $orderedBy
     * @return array
     */
    /**
     * @EXT\Route(
     *     "/userwidget/{widgetInstance}/page/{page}/max/{max}/ordered/by/{orderedBy}/order/{order}/search/{search}",
     *     name="simusante_userwidget_list",
     *     defaults={"page"=1, "search"="", "max"=20, "orderedBy"="title","order"="ASC"},
     *     options={"expose"=true}
     * )
     * @EXT\ParamConverter("authenticatedUser", options={"authenticatedUser" = true})
     * @EXT\Template("SimusanteUserwidgetBundle:Widget:userwidgetList.html.twig")
     */
    public function userwidgetListAction(
        WidgetInstance $widgetInstance,
        $search = '',
        $page = 1,
        $max = 20,
        $orderedBy = 'id',
        $order = 'ASC',
        $withPager = true
    )
    {
        //retrieve this widget config
        $config = $this->userwidgetManager->getUserwidgetConfig($widgetInstance);

        $publicProfilePreferences = $this->facetManager->getVisiblePublicPreference();

        //if no ws is selected
        if (is_null($config)) {
            //retrieve all users
            $users = $this->userManager->getAllUsers($page, $max, $orderedBy, $order);
        } else {
            $configWorkspace = $config->getWorkspace();
            //retrieve users from the selected ws
            $users = $this->userManager->getUsersByWorkspaces(array($configWorkspace), $page, $max, $withPager);
        }

        return array(
            'widgetInstance'            => $widgetInstance,
            'users'                     => $users,
            'search'                    => $search,
            'page'                      => $page,
            'max'                       => $max,
            'orderedBy'                 => $orderedBy,
            'order'                     => $order,
            'publicProfilePreferences'  => $publicProfilePreferences
        );
    }

    /**
     * Called on onConfigure Listener method for form POST
     * @param WidgetInstance $widgetInstance
     * @return array    AJAX response
     */
    /**
     * @EXT\Route(
     *     "/userwidget/widget/{widgetInstance}/configure/form",
     *     name="simusante_userwidget_configure_form",
     *     options={"expose"=true}
     * )
     * @EXT\ParamConverter("authenticatedUser", options={"authenticatedUser" = true})
     * @EXT\Template("SimusanteUserwidgetBundle:Widget:userwidgetConfigureForm.html.twig")
     */
    public function userwidgetConfigureFormAction(WidgetInstance $widgetInstance)
    {
        $config = $this->userwidgetManager->getUserwidgetConfig($widgetInstance);

        $form = $this->formFactory->create(
            new UserwidgetConfigType(),
            $config
        );

        return array(
            'form' => $form->createView(),
            'config' => $config
        );
    }
    /**
     * Ajax response to the configuration form post
     * @param UserwidgetConfig $config
     * @return array|JsonResponse
     */
    /**
     * @EXT\Route(
     *     "/userwidget/widget/configure/config/{config}",
     *     name="simusante_userwidget_configure",
     *     options={"expose"=true}
     * )
     * @EXT\ParamConverter("authenticatedUser", options={"authenticatedUser" = true})
     * @EXT\Template("SimusanteUserwidgetBundle:Widget:userwidgetConfigureForm.html.twig")
     */
    public function userwidgetConfigureAction(UserwidgetConfig $config)
    {
        $form = $this->formFactory->create(
            new UserwidgetConfigType(),
            $config
        );
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->userwidgetManager->persistUserwidgetConfig($config);

            return new JsonResponse('success', 204);
        } else {

            return array(
                'form' => $form->createView(),
                'config' => $config
            );
        }
    }
}