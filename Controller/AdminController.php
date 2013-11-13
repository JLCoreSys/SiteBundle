<?php

namespace CoreSys\SiteBundle\Controller;

use CoreSys\MediaBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CoreSys\SiteBundle\Controller\BaseController;
use CoreSys\SiteBundle\Form\SettingsType;

/**
 * Class AdminController
 * @package CoreSys\SiteBundle\Controller
 * @Route("/admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/settings", name="admin_site_settings")
     * @Template()
     */
    public function settingsAction()
    {
        $config = $this->getSiteSettings();
        $form = $this->createForm( new SettingsType(), $config );
        $request = $this->get( 'request' );
        $form->handleRequest( $request );

        if( $form->isValid() )
        {
            $settings = $form->getData();
/*
            $logoFileOne = $settings->getLogoFileOne();
            $logoFileTwo = $settings->getLogoFileTwo();

            // logo 1
            if(!empty( $logoFileOne ) ) {
                $logo = $settings->getLogo();
                if(!empty($logo)) {
                    $this->remove( $logo, array( 'site' ) );
                }

                $image = new Image();
                $image->setFile( $logoFileOne );
                $image->uploadImage( false );

                $this->persist( $image );
                $settings->setLogo( $image );

                $image->hasPublicImages('site');
            }
            // logo2
            if(!empty( $logoFileTwo ) ) {
                $logo2 = $settings->getAltLogo();
                if(!empty($logo2)) {
                    $this->remove( $logo2, array( 'site' ) );
                }

                $image2 = new Image();
                $image2->setFile( $logoFileTwo );
                $image2->uploadImage( false );

                $this->persist( $image2 );
                $settings->setAltLogo( $image2 );

                $image2->hasPublicImages('site');
            }
*/

            $this->persist( $settings );
            $this->flush();

            $this->setSuccess( 'Successfully saved site settings.' );
            return $this->redirect( $this->generateUrl( 'admin_site_settings' ) );
        }

        return array(
            'form' => $form->createView(),
            'settings' => $config
        );
    }
}
