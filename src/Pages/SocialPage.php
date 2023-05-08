<?php
namespace NourAlmasrieh\SocialWall;
use Page;
use SilverStripe\Dev\Debug;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;

class SocialPage extends Page
{
    private static $singular_name = 'Social Seite';
    private static $plural_name = 'Social Seiten';
    private static $description = 'Seite zeigt Social Wall Einträge an';
    private static $db = [
        'ShowOnLimitPosts' => 'Boolean(0)',
        'ShowOnAllSocialsPost'  => 'Boolean(0)',
        'ShowOnFacebook' => 'Boolean(0)',
        'ShowOnInstagram' => 'Boolean(0)',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnLimitPosts', 'Sollen die Posts auf 8 Stück Limitiert werden?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnAllSocialsPost', 'Sollen die Posts Alle von Facebook & Instagram angezeigt?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnFacebook', 'Sollen nur die Posts von Facebook angezeigt?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnInstagram', 'Sollen nur die Posts von Instagram angezeigt?'));
        $fields->addFieldToTab('Root.Import Social',LiteralField::create("ImportButton","
            <div class='text-center'>
                <br>
                Um neue Einträge in Social Media zu erhalten
                <a class='btn action btn-primary my-5 ml-3' target='_blank' href='/dev/tasks/NourAlmasrieh-SocialWall-SocialkAPITask'>Import</a>
            </div>
        "), 'MenuTitle');
        $this->extend('updateSocialPageCMSFields', $fields);

        return $fields;
    }
    public function getSlides()
    {
        $allSocialPost = ArrayList::create();
        $FacebookPosts = FacebookPosts::get();
        foreach($FacebookPosts as $facebook){
            $allSocialPost->push(new ArrayData([
                "PlatformID" =>  $facebook->PlatformID,
                "PlatformLink" =>  $facebook->PlatformLink,
                "Platform" => $facebook->Platform,
                "PlatformIconClass" => $facebook->PlatformIconClass,
                "Message" => $facebook->Message,
                "ImageLink" => $facebook->ImageLink,
                "CreatedDate" =>  $facebook->Date(),
                "Bild" =>  $facebook->Bild(),
                "Username" => $facebook->Username,
                "UsernameConf" => $facebook->ConfSocial()->Username,
                "ProfileImageConf" => $facebook->ConfSocial()->ProfileImage,
                "PlatformLinkConf" => $facebook->ConfSocial()->PlatformLink,
            ]));
        }
        $InstagramPosts = InstagramPosts::get();
        foreach($InstagramPosts as $instagram){
            $allSocialPost->push(new ArrayData([
                "PlatformID" =>  $instagram->PlatformID,
                "PlatformLink" =>  $instagram->PlatformLink,
                "Platform" => $instagram->Platform,
                "PlatformIconClass" => $instagram->PlatformIconClass,
                "Message" => $instagram->Message,
                "CreatedDate" =>  $instagram->Date(),
                "MediaType" =>  $instagram->MediaType,
                "Bild" =>  $instagram->Bild(),
                "Username" => $instagram->Username,
                "UsernameConf" => $instagram->ConfSocial()->Username,
                "ProfileImageConf" => $instagram->ConfSocial()->ProfileImage,
                "PlatformLinkConf" => $instagram->ConfSocial()->PlatformLink,
            ]));
        }        
        if($this->ShowOnLimitPosts){
            return $allSocialPost->limit(8); 
        }
        return $allSocialPost;  
    }
    public function getFacebook()
    {
        $allSocialPost = ArrayList::create();
        $FacebookPosts = FacebookPosts::get();
        foreach($FacebookPosts as $facebook){
            $allSocialPost->push(new ArrayData([
                "PlatformID" =>  $facebook->PlatformID,
                "PlatformLink" =>  $facebook->PlatformLink,
                "Platform" => $facebook->Platform,
                "PlatformIconClass" => $facebook->PlatformIconClass,
                "Message" => $facebook->Message,
                "ImageLink" => $facebook->ImageLink,
                "CreatedDate" =>  $facebook->Date(),
                "Bild" =>  $facebook->Bild(),
                "Username" => $facebook->Username,
                "UsernameConf" => $facebook->ConfSocial()->Username,
                "ProfileImageConf" => $facebook->ConfSocial()->ProfileImage,
                "PlatformLinkConf" => $facebook->ConfSocial()->PlatformLink,
            ]));
        }
        if($this->ShowOnLimitPosts){
            return $allSocialPost->limit(8); 
        }
        return $allSocialPost;  
    }
    public function getInstagram()
    {
        $allSocialPost = ArrayList::create();
        $InstagramPosts = InstagramPosts::get();
        foreach($InstagramPosts as $instagram){
            $allSocialPost->push(new ArrayData([
                "PlatformID" =>  $instagram->PlatformID,
                "PlatformLink" =>  $instagram->PlatformLink,
                "Platform" => $instagram->Platform,
                "PlatformIconClass" => $instagram->PlatformIconClass,
                "Message" => $instagram->Message,
                "ImageLink" => $instagram->ImageLink,
                "CreatedDate" =>  $instagram->Date(),
                "MediaType" =>  $instagram->MediaType,
                "Bild" =>  $instagram->Bild(),
                "Username" => $instagram->Username,
                "UsernameConf" => $instagram->ConfSocial()->Username,
                "ProfileImageConf" => $instagram->ConfSocial()->ProfileImage,
                "PlatformLinkConf" => $instagram->ConfSocial()->PlatformLink,
            ]));
        }
        if($this->ShowOnLimitPosts){
            return $allSocialPost->limit(8); 
        }
        return $allSocialPost;  
    }

    public function getPosts()
    {
        if($this->ShowOnAllSocialsPost){
            return $this->getSlides();
        }elseif($this->ShowOnFacebook){
            return $this->getFacebook();
        }elseif($this->ShowOnInstagram){
            return $this->getInstagram();
        }
        return $this->getSlides();
    }
}
