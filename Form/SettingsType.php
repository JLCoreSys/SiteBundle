<?php

namespace CoreSys\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\DataEvent;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siteName', null, array(
                'required' => true,
                'label' => 'Site Name',
                'attr' => array(
                    'data-postdesc' => 'The name for this website'
                )
            ))
            ->add('siteTitle', null, array(
                'required' => true,
                'label' => 'Site Title',
                'attr' => array(
                    'data-postdesc' => 'The title for this website'
                )
            ))
            ->add('siteSlogan', null, array(
                'required' => true,
                'label' => 'Site Slogan',
                'attr' => array(
                    'data-postdesc' => 'The slogan for this website'
                )
            ))
            ->add('siteKeywords', null, array(
                'required' => true,
                'label' => 'Site Keywords',
                'attr' => array(
                    'data-postdesc' => 'The site keywords, words/phrases separated by commas'
                )
            ))
            ->add('siteAdminEmail', 'email', array(
                'required' => true,
                'label' => 'Admin Email',
                'attr' => array(
                    'data-postdesc' => 'The Administrator Email Address'
                )
            ))
            ->add('siteWebmasterEmail', 'email', array(
                'required' => true,
                'label' => 'Webmaster Email',
                'attr' => array(
                    'data-postdesc' => 'The Webmaster Email Address'
                )
            ))
            ->add('siteSupportEmail', 'email', array(
                'required' => true,
                'label' => 'Support Email',
                'attr' => array(
                    'data-postdesc' => 'The Support Email Address'
                )
            ))
            ->add('siteDescription', 'genemu_tinymce', array(
                'required' => false,
                'label' => 'Site Description',
                'attr' => array(
                    'data-postdesc' => 'The description for this website',
                    'rows' => 30,
                    'width' => '100%',
                    'class' => 'input full-width'
                )
            ))
            ->add('logo_file_one', 'file', array( 'required' => false, 'label' => 'Logo', 'attr' => array( 'placeholder' => 'Select logo file', 'data-postdesc2' => 'The sites main logo', 'rel' => 'uploadifive_single', 'data-logo-num' => 1) ) )
            ->add('logo_file_two', 'file', array( 'required' => false, 'label' => 'Alt Logo', 'attr' => array( 'data-postdesc2' => 'The sites alternate logo', 'rel' => 'uploadifive_single', 'data-logo-num' => 2 ) ) )
//            ->add('files', 'file', array( 'required' => false, 'label' => 'Logo', 'attr' => array( 'multiple' => 'multiple', 'data-postdesc' => 'The sites main logo' ) ) )
//            ->add('image_ids', 'hidden', array( 'required' => false ) )

//            ->add('allowInvites', 'checkbox', array(
//                'required' => false,
//                'label' => 'Allow Invites',
//                'attr' => array(
//                    'class' => 'switch wider',
//                    'data-postdesc' => 'Allow users to invite others to the site?',
//                    'data-text-on' => 'Allow',
//                    'data-text-off' => 'DisAllow'
//                )
//            ))
            ->add('termsOfUseTitle', null, array(
                'label' => 'Terms of Use Title',
                'required' => false,
                'attr' => array(
                    'data-postdesc' => 'The name/title for the sites terms of service.'
                )
            ))
            ->add('termsOfUse', 'genemu_tinymce', array(
                'required' => false,
                'label' => 'Terms of Use',
                'attr' => array(
                    'data-postdesc' => 'The Terms of Use for this website.',
                    'rows' => 30,
                    'width' => '100%',
                    'class' => 'input full-width'
                )
            ))

            ->add('privacyPolicyTitle', null, array(
                'label' => 'Privacy Policy Title',
                'required' => false,
                'attr' => array(
                    'data-postdesc' => 'The name/title for the sites privacy policy.'
                )
            ))
            ->add('privacyPolicy', 'genemu_tinymce', array(
                'required' => false,
                'label' => 'Privacy Policy',
                'attr' => array(
                    'data-postdesc' => 'The Privacy Policy for this website.',
                    'rows' => 30,
                    'width' => '100%',
                    'class' => 'input full-width'
                )
            ))

//            ->add('rulesTitle', null, array(
//                'label' => 'Rules Title',
//                'required' => false,
//                'attr' => array(
//                    'data-postdesc' => 'The name/title for the sites rules.'
//                )
//            ))
//            ->add('rules', 'genemu_tinymce', array(
//                'required' => false,
//                'label' => 'Rules',
//                'attr' => array(
//                    'data-postdesc' => 'The Rules for this website.',
//                    'rows' => 10,
//                    'class' => 'input full-width'
//                )
//            ))
        ;
    }

    public function getName()
    {
        return 'settings_type';
    }
}