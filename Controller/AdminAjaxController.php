<?php

namespace CoreSys\SiteBundle\Controller;

use CoreSys\SiteBundle\Entity\Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CoreSys\MediaBundle\Entity\Image;

/**
 * Class AdminAjaxController
 * @package CoreSys\SiteBundle\Controller
 *
 * @Route("/admin/ajax/site")
 */
class AdminAjaxController extends BaseController
{
    /**
     * @Route("/add_logo_image/{logo_num}/{iid}", name="admin_ajax_site_add_logo", defaults={"logo_num"="null","iid"="null"})
     * @Template()
     */
    public function addLogoToSettingsAction($logo_num,$iid)
    {
        if( empty($logo_num) || empty($iid) ) {
            $this->echoJsonError( 'Logo num or image id not supplied' );
        }

        $settings = $this->getRepo( 'CoreSysSiteBundle:Config' );
        $settings = $settings->getConfig();

        $image_repo = $this->getRepo( 'CoreSysMediaBundle:Image' );
        $image = $image_repo->findOneById( $iid );

        if( empty( $image ) ) {
            $this->echoJsonError( 'Could not locate uploaded image' );
            exit;
        }

        if( $logo_num == 1 ) {
            $old = $settings->getLogo();
            if( !empty( $old ) ) { $this->remove( $old, array( 'site' ) ); }
            $settings->setLogo( $image );
        } else if( $logo_num == 2 ) {
            $old = $settings->getAltLogo();
            if( !empty( $old ) ) { $this->remove( $old, array( 'site' ) ); }
            $settings->setAltLogo( $image );
        } else {
            $this->echoJsonError( 'Logo num range is wrong.' );
            exit;
        }

        $this->persist( $image );
        $this->persist( $settings );
        $this->flush();

        $this->echoJsonSuccess( 'Success', array(
            'image_url' => $this->generateUrl( 'media_image_medium', array( 'slug' => $image->getId() ) )
        ) );
        exit;
    }

    /**
     * @Route("/remove_logo/{which}", name="admin_ajax_site_remove_logo", defaults={"which"="null"})
     * @Template()
     */
    public function removeLogoAction( $which )
    {
        $settings = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();

        if( $which == 'main' ) {
            $logo = $settings->getLogo();
            $this->remove( $logo, array( 'site' ) );
            $settings->setLogo( null );
        } else if( $which == 'alt' ) {
            $logo = $settings->getAltLogo();
            $this->remove( $logo, array( 'site' ) );
            $settings->setAltLogo( null );
        } else {
            $this->echoJsonError( 'Could not determine logo type "' . $which . '"' );
            exit;
        }

        $this->persist( $settings );
        $this->flush();

        $this->echoJsonSuccess( 'Success' );
        exit;
    }

    /**
     * @Route("/add_site_image/{id}", name="admin_ajax_site_add_image", defaults={"id"="null"})
     * @ParamConverter("image", class="CoreSysMediaBundle:Image")
     * @Template()
     */
    public function addSiteImageAction( Image $image )
    {
        $settings = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();
        $settings->addImage( $image );
        $this->persist( $image );
        $this->persist( $settings );
        $this->flush();

        $template = $this->renderView( 'CoreSysSiteBundle:Admin:site_images_table_row.html.twig', array( 'image' => $image, 'i' => 0 ) );

        $this->echoJsonSuccess( 'Successfully added sit image', array(
            'image_id' => $image->getId(),
            'title' => $image->getTitle(),
            'ext' => $image->getExt(),
            'template' => $template
        ) );
        exit;
    }

    /**
     * @Route("/remove_site_image/{id}", name="admin_ajax_site_remove_image", defaults={"id"="null"})
     * @ParamConverter("image", class="CoreSysMediaBundle:Image")
     * @Template()
     */
    public function removeSiteImageAction( Image $image )
    {
        $settings = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();
        $settings->removeImage( $image );
        $this->persist( $settings );
        $this->remove( $image, array( 'site' ) );
        $this->flush();

        $this->echoJsonSuccess( 'Success' );
        exit;
    }

    /**
     * @Route("/remove_site_images", name="admin_ajax_site_remove_images")
     * @Template()
     */
    public function removeSiteImagesAction()
    {
        $irepo = $this->getRepo( 'CoreSysMediaBundle:Image' );
        $settings = $this->getRepo( 'CoreSysSiteBundle:Config' )->getConfig();
        $request = $this->get( 'request' );
        $count = 0;

        $ids = $request->get( 'ids', array() );
        $ids = is_array( $ids ) ? $ids : array();
        $flush = false;

        foreach( $ids as $id )
        {
            $image = $irepo->findOneById( $id );
            if( !empty( $image ) ) {
                $flush = true;
                $settings->removeImage( $image );
                $this->remove( $image, array( 'site' ) );
                $count++;
            }
        }

        if( $flush ) {
            $this->persist( $settings );
            $this->flush();
        }

        $this->echoJsonSuccess( 'Success', array( 'removed' => $count ) );
        exit;
    }
}