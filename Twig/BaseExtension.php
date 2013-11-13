<?php

namespace CoreSys\SiteBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;
use Doctrine\ORM\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Base case extension to be used among all CoreSys Twig Extensions
 * Adds common functionality that would otherwise be copy/pasted
 *
 * Class BaseExtension
 * @package CoreSys\SiteBundle\Twig
 */
class BaseExtension extends Twig_Extension
{
    /**
     * @var EntityManager $entity_manager
     */
    private $entity_manager;

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var string
     */
    protected $name = 'base_extension';

    /**
     * construct a new common extensions object
     *
     * @param EntityManager      $entity_manager
     * @param ContainerInterface $container
     * @param session            $session
     */
    public function __construct( EntityManager $entity_manager, ContainerInterface $container, Session $session )
    {
        $this->entity_manager = $entity_manager;
        $this->container      = $container;
        $this->session        = $session;
    }

    /**
     * get session
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * get the entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entity_manager;
    }

    /**
     * Get a specified repository
     *
     * @param string $name
     * @return repo | null
     */
    public function getRepo( $name )
    {
        return $this->getEntityManager()->getRepository( $name );
    }

    /**
     * get the container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return parent::getFilters();
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return parent::getFunctions();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * get
     *
     * @param string name
     * @return mixed
     */
    public function get( $name )
    {
        return $this->getContainer()->get( $name );
    }

    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->get('router')->generate($route, $parameters, $referenceType);
    }
}