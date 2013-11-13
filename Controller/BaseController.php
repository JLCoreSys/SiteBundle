<?php

namespace CoreSys\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class BaseController
 * @package CoreSys\SiteBundle\Controller
 */
class BaseController extends Controller
{

    /**
     * @var
     */
    private $settings_repo;

    /**
     * @var
     */
    private $site_settings;

    /**
     * @var
     */
    private $base_folder;

    /**
     * @var
     */
    private $web_folder;

    /**
     * @var
     */
    private $upload_folder;

    /**
     * @param null $name
     *
     * @return mixed
     */
    public function getRepository( $name = NULL )
    {
        return $this->getRepo( $name );
    }

    /**
     * @param null $name
     *
     * @return mixed
     */
    public function getRepo( $name = NULL )
    {
        return $this->getEntityManager()->getRepository( $name );
    }

    /**
     * @param $entity
     */
    public function persist( $entity )
    {
        if ( is_object( $entity ) ) {
            if ( method_exists( $entity, 'setContainer' ) ) {
                $container = $this->container;
                $entity->setContainer( $container );
            }
            $functions = array( 'PrePersist', 'prePersist', 'PrePersist' );
            foreach ( $functions as $function ) {
                if ( method_exists( $entity, $function ) ) {
                    $entity->$function();
                }
            }
        }
        $this->getEntityManager()->persist( $entity );
    }

    /**
     * @return object
     */
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $entity
     */
    public function remove( $entity, $params = array() )
    {
        if ( is_object( $entity ) ) {
            if ( method_exists( $entity, 'setContainer' ) ) {
                $container = $this->container;
                $entity->setContainer( $container );
            }
            $functions = array( 'preremove', 'preRemove', 'PreRemove', 'predelete', 'preDelete', 'PreDelete', 'remove', 'delete' );
            foreach ( $functions as $function ) {
                if ( method_exists( $entity, $function ) ) {
                    call_user_func_array( array( $entity, $function ), $params );
                }
            }
        }
        $this->getEntityManager()->remove( $entity );
    }

    /**
     *
     */
    public function flush()
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return mixed
     */
    public function getSiteSettings()
    {
        if ( !empty( $this->site_settings ) ) {
            return $this->site_settings;
        }

        $repo                = $this->getSettingsRepo();
        $this->site_settings = $repo->getConfig();

        return $this->site_settings;
    }

    /**
     * @return mixed
     */
    public function getSettingsRepo()
    {
        if ( !empty( $this->settings_repo ) ) {
            return $this->settings_repo;
        }
        $this->settings_repo = $this->getRepo( 'CoreSysSiteBundle:Config' );

        return $this->settings_repo;
    }

    /**
     * @param null $msg
     */
    public function setError( $msg = NULL )
    {
        $session = $this->get( 'session' );
        $session->getFlashBag()->add( 'error', $msg );
    }

    /**
     * @param null $msg
     */
    public function setSuccess( $msg = NULL )
    {
        $session = $this->get( 'session' );
        $session->getFlashBag()->add( 'success', $msg );
    }

    /**
     * @return string
     */
    public function getBaseFolder()
    {
        if ( !empty( $this->base_folder ) ) {
            return $this->base_folder;
        }

        $folder            = $this->getWebFolder();
        $this->base_folder = dirname( $folder );

        return $this->base_folder;
    }

    /**
     * @return string
     */
    public function getWebFolder()
    {
        if ( !empty( $this->web_folder ) ) {
            return $this->web_folder;
        }

        $base = dirname( __FILE__ );
        $web  = $base . DIRECTORY_SEPARATOR . 'web';
        while ( !is_dir( $web ) ) {
            $base = dirname( $base );
            $web  = $base . DIRECTORY_SEPARATOR . 'web';
        }

        $this->web_folder = $web;

        return $this->web_folder;
    }

    /**
     * @return null
     */
    public function getUploadFolder()
    {
        if ( !empty( $this->upload_folder ) ) {
            return $this->upload_folder;
        }

        $folder              = $this->getWebFolder() . DIRECTORY_SEPARATOR . 'upload';
        $this->upload_folder = $this->verifyFolder( $folder );

        return $this->upload_folder;
    }

    /**
     * @param null $folder
     *
     * @return null
     */
    public function verifyFolder( $folder = NULL )
    {
        if ( empty( $folder ) ) {
            return $folder;
        }

        if ( !is_dir( $folder ) ) {
            @mkdir( $folder, 0777 );
            @chmod( $folder, 0777 );
        }

        return $folder;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param null  $msg
     * @param array $params
     */
    public function echoJsonSuccess( $msg = NULL, $params = array() )
    {
        $output = array( 'success' => TRUE, 'msg' => $msg );
        foreach ( $params as $key => $val ) {
            $output[ $key ] = $val;
        }
        echo json_encode( $output );
        exit;
    }

    /**
     * @param null  $msg
     * @param array $params
     */
    public function echoJsonError( $msg = NULL, $params = array() )
    {
        $output = array( 'success' => FALSE, 'msg' => $msg );
        foreach ( $params as $key => $val ) {
            $output[ $key ] = $val;
        }
        echo json_encode( $output );
        exit;
    }

    /**
     * Dispatch an event
     *
     * @param Event  $event
     * @param string $type
     */
    public function dispatchEvent( &$event, $type = NULL )
    {
        $dispatcher = $this->get( 'event_dispatcher' );
        $dispatcher->dispatch( $type, $event );
    }
}
