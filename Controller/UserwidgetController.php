<?php

namespace Simusante\UserwidgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use Symfony\Component\HttpFoundation\Response;

/**
 * userwidget controller.
 *
 */
class UserwidgetController extends Controller
{
    public function displayUserAction()
    {
        return $this->render('SimusanteUserwidgetBundle::toto.html.twig');
    }
}