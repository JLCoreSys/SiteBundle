<?php

namespace CoreSys\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 * @package CoreSys\SiteBundle\Controller
 * @Route("/")
 */
class DefaultController extends Controller
{

    /**
     * @Route("/", name="site_base")
     * @Template()
     */
    public function siteBaseAction()
    {
        exit;
    }

    /**
     * @Route("/script_base_url", name="script_base_url")
     * @Template()
     */
    public function scriptBaseUrlAction()
    {
        exit;
    }

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
echo 'Coming Soon'; exit;
        return array('name' => $name);
    }
}
