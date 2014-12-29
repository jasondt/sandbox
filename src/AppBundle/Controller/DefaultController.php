<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/nope", name="nope")
     */
    public function nopeAction() {
        return $this->render('default/index.html.twig');
    }

}
