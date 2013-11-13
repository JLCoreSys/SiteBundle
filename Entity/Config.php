<?php

namespace CoreSys\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use CoreSys\MediaBundle\Entity\Image;

/**
 * Config
 *
 * @ORM\Table(name="site_config")
 * @ORM\Entity(repositoryClass="CoreSys\SiteBundle\Entity\ConfigRepository")
 */
class Config
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
     * @ORM\Column(name="site_name", type="string", length=255, nullable=true)
     */
    private $siteName;

    /**
     * @var string
     *
     * @ORM\Column(name="site_title", type="string", length=255, nullable=true)
     */
    private $siteTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="site_slogan", type="string", length=255, nullable=true)
     */
    private $siteSlogan;

    /**
     * @var string
     *
     * @ORM\Column(name="site_keywords", type="string", length=255, nullable=true)
     */
    private $siteKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="site_description", type="text", nullable=true)
     */
    private $siteDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="site_admin_email", type="string", length=255, nullable=true)
     */
    private $siteAdminEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="site_webmaster_email", type="string", length=255, nullable=true)
     */
    private $siteWebmasterEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="site_support_email", type="string", length=255, nullable=true)
     */
    private $siteSupportEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allow_invites", type="boolean", nullable=true)
     */
    private $allowInvites;

    /**
     * @var string
     *
     * @ORM\Column(name="terms_of_use_title", type="string", length=255, nullable=true)
     */
    private $termsOfUseTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="terms_of_use", type="text", nullable=true)
     */
    private $termsOfUse;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy_policy_title", type="string", length=255, nullable=true)
     */
    private $privacyPolicyTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy_policy", type="text", nullable=true)
     */
    private $privacyPolicy;

    /**
     * @var string
     *
     * @ORM\Column(name="about_us_title", type="string", length=255, nullable=true)
     */
    private $aboutUsTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="about_us", type="text", nullable=true)
     */
    private $aboutUs;

    /**
     * @var string
     *
     * @ORM\Column(name="rules_title", type="string", length=255, nullable=true)
     */
    private $rulesTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="rules", type="text", nullable=true)
     */
    private $rules;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_us_title", type="string", length=255, nullable=true)
     */
    private $contactUsTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_us", type="text", nullable=true)
     */
    private $contactUs;

    /**
     * @var string
     *
     * @ORM\Column(name="help_title", type="string", length=255, nullable=true)
     */
    private $helpTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="help", type="text", nullable=true)
     */
    private $help;

    /**
     * @var file
     */
    private $logo_file_one;

    /**
     * @var file
     */
    private $logo_file_two;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CoreSys\MediaBundle\Entity\Image")
     * @ORM\JoinTable(name="site_config_images",
     *      joinColumns={@ORM\JoinColumn(name="config_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")})
     */
    private $images;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="CoreSys\MediaBundle\Entity\Image")
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", nullable=true)
     */
    private $logo;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="CoreSys\MediaBundle\Entity\Image")
     * @ORM\JoinColumn(name="alt_logo_id", referencedColumnName="id", nullable=true)
     */
    private $alt_logo;

    public function __construct()
    {
        $this->setSiteName( 'Default site name' );
        $this->setSiteTitle( 'Default site title' );
        $this->setSiteSlogan( 'Default site slogan' );
        $this->setSiteDescription( 'Default site description.' );
        $this->setSiteKeywords( 'site, sitename' );
        $this->setSiteAdminEmail( 'admin@domain.com' );
        $this->setSiteSupportEmail( 'support@domain.com' );
        $this->setSiteWebmasterEmail( 'webmaster@domain.com' );
        $this->setAllowInvites( TRUE );
        $this->setImages( new ArrayCollection() );
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages( $images )
    {
        $this->images = $images;

        return $this;
    }

    public function addImage( Image $image = NULL )
    {
        if ( !empty( $image ) && !$this->images->contains( $image ) ) {
            $this->images->add( $image );
        }

        return $this;
    }

    public function removeImage( Image $image = NULL )
    {
        if ( !empty( $image ) && $this->images->contains( $image ) ) {
            $this->images->removeElement( $image );
        }

        return $this;
    }

    /**
     * @return \CoreSys\SiteBundle\Entity\Image
     */
    public function getAltLogo()
    {
        return $this->alt_logo;
    }

    /**
     * @param \CoreSys\SiteBundle\Entity\Image $alt_logo
     */
    public function setAltLogo( Image $alt_logo = NULL )
    {
        $this->alt_logo = $alt_logo;

        return $this;
    }

    /**
     * @return \CoreSys\SiteBundle\Entity\Image
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param \CoreSys\SiteBundle\Entity\Image $logo
     */
    public function setLogo( Image $logo = NULL )
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return \CoreSys\SiteBundle\Entity\file
     */
    public function getLogoFileOne()
    {
        return $this->logo_file_one;
    }

    /**
     * @param \CoreSys\SiteBundle\Entity\file $logo_file_one
     */
    public function setLogoFileOne( $logo_file_one )
    {
        $this->logo_file_one = $logo_file_one;
    }

    /**
     * @return \CoreSys\SiteBundle\Entity\file
     */
    public function getLogoFileTwo()
    {
        return $this->logo_file_two;
    }

    /**
     * @param \CoreSys\SiteBundle\Entity\file $logo_file_two
     */
    public function setLogoFileTwo( $logo_file_two )
    {
        $this->logo_file_two = $logo_file_two;
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
     * Get siteName
     *
     * @return string
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * Set siteName
     *
     * @param string $siteName
     *
     * @return Config
     */
    public function setSiteName( $siteName )
    {
        $this->siteName = $siteName;

        return $this;
    }

    /**
     * Get siteTitle
     *
     * @return string
     */
    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    /**
     * Set siteTitle
     *
     * @param string $siteTitle
     *
     * @return Config
     */
    public function setSiteTitle( $siteTitle )
    {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    /**
     * Get siteSlogan
     *
     * @return string
     */
    public function getSiteSlogan()
    {
        return $this->siteSlogan;
    }

    /**
     * Set siteSlogan
     *
     * @param string $siteSlogan
     *
     * @return Config
     */
    public function setSiteSlogan( $siteSlogan )
    {
        $this->siteSlogan = $siteSlogan;

        return $this;
    }

    /**
     * Get siteKeywords
     *
     * @return string
     */
    public function getSiteKeywords()
    {
        return $this->siteKeywords;
    }

    /**
     * Set siteKeywords
     *
     * @param string $siteKeywords
     *
     * @return Config
     */
    public function setSiteKeywords( $siteKeywords )
    {
        $this->siteKeywords = $siteKeywords;

        return $this;
    }

    /**
     * Get siteDescription
     *
     * @return string
     */
    public function getSiteDescription()
    {
        return $this->siteDescription;
    }

    /**
     * Set siteDescription
     *
     * @param string $siteDescription
     *
     * @return Config
     */
    public function setSiteDescription( $siteDescription )
    {
        $this->siteDescription = $siteDescription;

        return $this;
    }

    /**
     * Get siteAdminEmail
     *
     * @return string
     */
    public function getSiteAdminEmail()
    {
        return $this->siteAdminEmail;
    }

    /**
     * Set siteAdminEmail
     *
     * @param string $siteAdminEmail
     *
     * @return Config
     */
    public function setSiteAdminEmail( $siteAdminEmail )
    {
        $this->siteAdminEmail = $siteAdminEmail;

        return $this;
    }

    /**
     * Get siteWebmasterEmail
     *
     * @return string
     */
    public function getSiteWebmasterEmail()
    {
        return $this->siteWebmasterEmail;
    }

    /**
     * Set siteWebmasterEmail
     *
     * @param string $siteWebmasterEmail
     *
     * @return Config
     */
    public function setSiteWebmasterEmail( $siteWebmasterEmail )
    {
        $this->siteWebmasterEmail = $siteWebmasterEmail;

        return $this;
    }

    /**
     * Get siteSupportEmail
     *
     * @return string
     */
    public function getSiteSupportEmail()
    {
        return $this->siteSupportEmail;
    }

    /**
     * Set siteSupportEmail
     *
     * @param string $siteSupportEmail
     *
     * @return Config
     */
    public function setSiteSupportEmail( $siteSupportEmail )
    {
        $this->siteSupportEmail = $siteSupportEmail;

        return $this;
    }

    /**
     * Get allowInvites
     *
     * @return boolean
     */
    public function getAllowInvites()
    {
        return $this->allowInvites;
    }

    /**
     * Set allowInvites
     *
     * @param boolean $allowInvites
     *
     * @return Config
     */
    public function setAllowInvites( $allowInvites )
    {
        $this->allowInvites = $allowInvites;

        return $this;
    }

    /**
     * Get termsOfUseTitle
     *
     * @return string
     */
    public function getTermsOfUseTitle()
    {
        return $this->termsOfUseTitle;
    }

    /**
     * Set termsOfUseTitle
     *
     * @param string $termsOfUseTitle
     *
     * @return Config
     */
    public function setTermsOfUseTitle( $termsOfUseTitle )
    {
        $this->termsOfUseTitle = $termsOfUseTitle;

        return $this;
    }

    /**
     * Get termsOfUse
     *
     * @return string
     */
    public function getTermsOfUse()
    {
        return $this->termsOfUse;
    }

    /**
     * Set termsOfUse
     *
     * @param string $termsOfUse
     *
     * @return Config
     */
    public function setTermsOfUse( $termsOfUse )
    {
        $this->termsOfUse = $termsOfUse;

        return $this;
    }

    /**
     * Get privacyPolicyTitle
     *
     * @return string
     */
    public function getPrivacyPolicyTitle()
    {
        return $this->privacyPolicyTitle;
    }

    /**
     * Set privacyPolicyTitle
     *
     * @param string $privacyPolicyTitle
     *
     * @return Config
     */
    public function setPrivacyPolicyTitle( $privacyPolicyTitle )
    {
        $this->privacyPolicyTitle = $privacyPolicyTitle;

        return $this;
    }

    /**
     * Get privacyPolicy
     *
     * @return string
     */
    public function getPrivacyPolicy()
    {
        return $this->privacyPolicy;
    }

    /**
     * Set privacyPolicy
     *
     * @param string $privacyPolicy
     *
     * @return Config
     */
    public function setPrivacyPolicy( $privacyPolicy )
    {
        $this->privacyPolicy = $privacyPolicy;

        return $this;
    }

    /**
     * Get aboutUsTitle
     *
     * @return string
     */
    public function getAboutUsTitle()
    {
        return $this->aboutUsTitle;
    }

    /**
     * Set aboutUsTitle
     *
     * @param string $aboutUsTitle
     *
     * @return Config
     */
    public function setAboutUsTitle( $aboutUsTitle )
    {
        $this->aboutUsTitle = $aboutUsTitle;

        return $this;
    }

    /**
     * Get aboutUs
     *
     * @return string
     */
    public function getAboutUs()
    {
        return $this->aboutUs;
    }

    /**
     * Set aboutUs
     *
     * @param string $aboutUs
     *
     * @return Config
     */
    public function setAboutUs( $aboutUs )
    {
        $this->aboutUs = $aboutUs;

        return $this;
    }

    /**
     * Get rulesTitle
     *
     * @return string
     */
    public function getRulesTitle()
    {
        return $this->rulesTitle;
    }

    /**
     * Set rulesTitle
     *
     * @param string $rulesTitle
     *
     * @return Config
     */
    public function setRulesTitle( $rulesTitle )
    {
        $this->rulesTitle = $rulesTitle;

        return $this;
    }

    /**
     * Get rules
     *
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set rules
     *
     * @param string $rules
     *
     * @return Config
     */
    public function setRules( $rules )
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Get contactUsTitle
     *
     * @return string
     */
    public function getContactUsTitle()
    {
        return $this->contactUsTitle;
    }

    /**
     * Set contactUsTitle
     *
     * @param string $contactUsTitle
     *
     * @return Config
     */
    public function setContactUsTitle( $contactUsTitle )
    {
        $this->contactUsTitle = $contactUsTitle;

        return $this;
    }

    /**
     * Get contactUs
     *
     * @return string
     */
    public function getContactUs()
    {
        return $this->contactUs;
    }

    /**
     * Set contactUs
     *
     * @param string $contactUs
     *
     * @return Config
     */
    public function setContactUs( $contactUs )
    {
        $this->contactUs = $contactUs;

        return $this;
    }

    /**
     * Get helpTitle
     *
     * @return string
     */
    public function getHelpTitle()
    {
        return $this->helpTitle;
    }

    /**
     * Set helpTitle
     *
     * @param string $helpTitle
     *
     * @return Config
     */
    public function setHelpTitle( $helpTitle )
    {
        $this->helpTitle = $helpTitle;

        return $this;
    }

    /**
     * Get help
     *
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * Set help
     *
     * @param string $help
     *
     * @return Config
     */
    public function setHelp( $help )
    {
        $this->help = $help;

        return $this;
    }
}
