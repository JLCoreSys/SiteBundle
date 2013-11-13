<?php

namespace CoreSys\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IpAddress
 *
 * @ORM\Table(name="ip_address", indexes={@ORM\Index(name="ip_address_idx", columns={"ip_address"})})
 * @ORM\Entity(repositoryClass="CoreSys\SiteBundle\Entity\IpAddressRepository")
 * @ORM\HasLifecycleCallbacks
 */
class IpAddress
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=32)
     */
    private $ip_address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_seen", type="datetime")
     */
    private $last_seen;

    /**
     * @var array
     *
     * @ORM\Column(name="hits", type="array")
     */
    private $hits;

    /**
     * @var integer
     *
     * @ORM\Column(name="hits_count", type="integer")
     */
    private $hits_count;

    /**
     * @var array
     *
     * @ORM\Column(name="clicks", type="array")
     */
    private $clicks;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicks_count", type="integer")
     */
    private $clicks_count;

    /**
     * @var integer
     *
     * @ORM\Column(name="impressions_count", type="integer")
     */
    private $impressions_count;

    /**
     * @var array
     *
     * @ORM\Column(name="impressions", type="array")
     */
    private $impressions;

    /**
     *
     */
    public function __construct()
    {
        $this->setCreated( new \DateTime() );
        $this->setLastSeen( new \DateTime() );
        $this->setClicks( array() );
        $this->setImpressions( array() );
        $this->setHits( array() );
        $ip = isset( $_SERVER[ 'REMOTE_ADDR' ] ) ? $_SERVER[ 'REMOTE_ADDR' ] : '127.0.0.1';
        $this->setIpAddress( $ip );
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get ip_address
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * Set ip_address
     *
     * @param string $ipAddress
     *
     * @return IpAddress
     */
    public function setIpAddress( $ipAddress )
    {
        $this->ip_address = $ipAddress;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return IpAddress
     */
    public function setCreated( $created )
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get last_seen
     *
     * @return \DateTime
     */
    public function getLastSeen()
    {
        return $this->last_seen;
    }

    /**
     * Set last_seen
     *
     * @param \DateTime $lastSeen
     *
     * @return IpAddress
     */
    public function setLastSeen( $lastSeen )
    {
        $this->last_seen = $lastSeen;

        return $this;
    }

    /**
     * Get hits_count
     *
     * @return integer
     */
    public function getHitsCount()
    {
        return $this->hits_count;
    }

    /**
     * Set hits_count
     *
     * @param integer $hitsCount
     *
     * @return IpAddress
     */
    public function setHitsCount( $hitsCount )
    {
        $this->hits_count = $hitsCount;

        return $this;
    }

    /**
     * Get clicks_count
     *
     * @return integer
     */
    public function getClicksCount()
    {
        return $this->clicks_count;
    }

    /**
     * Set clicks_count
     *
     * @param integer $clicksCount
     *
     * @return IpAddress
     */
    public function setClicksCount( $clicksCount )
    {
        $this->clicks_count = $clicksCount;

        return $this;
    }

    /**
     * Get impressions_count
     *
     * @return integer
     */
    public function getImpressionsCount()
    {
        return $this->impressions_count;
    }

    /**
     * Set impressions_count
     *
     * @param integer $impressionsCount
     *
     * @return IpAddress
     */
    public function setImpressionsCount( $impressionsCount )
    {
        $this->impressions_count = $impressionsCount;

        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return $this
     */
    public function addHit( \DateTime $date = NULL )
    {
        if ( empty( $date ) ) {
            $date = new \DateTime();
        }

        $y = $date->format( 'Y' );
        $m = $date->format( 'm' );
        $d = $date->format( 'd' );
        $h = $date->format( 'H' );
        $m2 = $date->format( 'i' );
        $s = $date->format( 's' );
        $date = gmmktime( $h, $m2, $s, $m, $d, $y );

        $hits    = $this->getHits();
        $hits[ ] = $date;
        $this->setHits( $hits );

        return $this;
    }

    /**
     * Get hits
     *
     * @return array
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set hits
     *
     * @param array $hits
     *
     * @return IpAddress
     */
    public function setHits( $hits )
    {
        $this->hits = $hits;
        $this->setHitsCount( count( $hits ) );

        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return $this
     */
    public function addClick( \DateTime $date = NULL )
    {
        if ( empty( $date ) ) {
            $date = new \DateTime();
        }

        $y = $date->format( 'Y' );
        $m = $date->format( 'm' );
        $d = $date->format( 'd' );
        $h = $date->format( 'H' );
        $m2 = $date->format( 'i' );
        $s = $date->format( 's' );
        $date = gmmktime( $h, $m2, $s, $m, $d, $y );

        $clickss    = $this->getClicks();
        $clickss[ ] = $date;
        $this->setClicks( $clickss );

        return $this;
    }

    /**
     * Get clicks
     *
     * @return array
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set clicks
     *
     * @param array $clicks
     *
     * @return IpAddress
     */
    public function setClicks( $clicks )
    {
        $this->clicks = $clicks;
        $this->setClicksCount( count( $clicks ) );

        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return $this
     */
    public function addImpression( \DateTime $date = NULL )
    {
        if ( empty( $date ) ) {
            $date = new \DateTime();
        }

        $y = $date->format( 'Y' );
        $m = $date->format( 'm' );
        $d = $date->format( 'd' );
        $h = $date->format( 'H' );
        $m2 = $date->format( 'i' );
        $s = $date->format( 's' );
        $date = gmmktime( $h, $m2, $s, $m, $d, $y );

        $impressions    = $this->getImpressions();
        $impressions[ ] = $date;
        $this->setImpressions( $impressions );

        return $this;
    }

    /**
     * Get impressions
     *
     * @return array
     */
    public function getImpressions()
    {
        return $this->impressions;
    }

    /**
     * Set impressions
     *
     * @param array $impressions
     *
     * @return IpAddress
     */
    public function setImpressions( $impressions )
    {
        $this->impressions = $impressions;
        $this->setImpressionsCount( count( $impressions ) );

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function PrePersist()
    {
        $this->setLastSeen( new \DateTime() );
    }
}
