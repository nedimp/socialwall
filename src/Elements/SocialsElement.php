<?php

namespace NourAlmasrieh\SocialWall;

use SilverStripe\Dev\Debug;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\FieldType\DBVarchar;
use UncleCheese\Forms\ImageOptionsetField;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use RyanPotter\SilverStripeColorField\Forms\ColorField;

class SocialsElement extends BaseElement
{
    private static $table_name = 'SocialsElement';
    private static $singular_name = 'Socials Block';
    private static $plural_name = 'Socials Blöcke';
    private static $description = 'Zeigt Sociel Media Einträge an';
    private static $inline_editable = false;
    private static $db = [
        'ElementStyle' => 'Text',
        'BackgroundColor' => DBVarchar::class . '(7)',
        'ExternalLink' => 'Text',
        'ButtonCaption' => 'Text',
        'Content' => 'HTMLText',
        'ShowOnLimitPosts' => 'Boolean(0)',
        'ShowOnAllSocialsPost'  => 'Boolean(0)',
        'ShowOnFacebook' => 'Boolean(0)',
        'ShowOnInstagram' => 'Boolean(0)',
    ];
    private static $has_one = [
        'LinkedPage' => SiteTree::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName([
            'HTML'
        ]);
        $layoutField = ImageOptionsetField::create('ElementStyle', 'Layout wählen')->setSource($this->getLayoutOptions());
        $layoutField->setImageHeight($this->getConfigVariable('FieldSettings', 'ImageHeight'));
        $layoutField->setImageWidth($this->getConfigVariable('FieldSettings', 'ImageWidth'));
        $layoutField->setDescription($this->getConfigVariable('FieldSettings', 'FieldDescription'));
        $fields->addFieldToTab('Root.Main', $layoutField, 'Title');

        /*Get all fields*/
        $schema = DataObject::getSchema();
        $allFields = $schema->fieldSpecs($this);
        $columns = array_keys($allFields);

        $fields->addFieldsToTab('Root.Main',  ColorField::create('BackgroundColor','Hintergrund Farbe'));   
        $fields->addFieldsToTab('Root.Main', HTMLEditorField::create('Content', 'Inhalt')->setRows(8),'SubTitle');;
        $fields->addFieldsToTab('Root.Main', TextField::create('ButtonCaption', 'Button Beschriftung'),'SocialPageID');
        $fields->insertAfter('ButtonCaption', TreeDropdownField::create('LinkedPageID', 'Interne Verlinkung', SiteTree::class)
            ->setDescription('Wird bevorzugt ausgegeben.')
            ->setEmptyString('Auswählen'));
        $fields->addFieldsToTab('Root.Main', TextField::create('ExternalLink', 'Externe Verlinkung')
            ->setDescription('Muss mit "https://" gepflegt werden.<br>Wird alternativ zur internen Verlinkung ausgegeben.'),'SocialPageID');

        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnLimitPosts', 'Sollen die Posts auf 8 Stück Limitiert werden?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnAllSocialsPost', 'Sollen die Posts Alle von Facebook & Instagram angezeigt?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnFacebook', 'Sollen nur die Posts von Facebook angezeigt?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnInstagram', 'Sollen nur die Posts von Instagram angezeigt?'));
        
        $this->extend('updateSocialsElementCMSFields', $fields);

        foreach ($columns as $field) {
            if (!in_array($field, $this->getReservedFields())) {
                if ($this->ElementStyle == '') {
                    /*As long as no Layout is selected, all Fields will be removed*/
                    $fields->removeByName($field);
                    if (!$fields->dataFieldByName($field)) {
                        $fields->removeByName($field);
                        $field = str_replace('ID', '', $field);
                        $fields->removeByName($field);
                    }
                } else {
                    if (!$this->getConfigVariable('Layouts', $this->ElementStyle)['FieldsVisibleElement'][$field]) {
                        $fields->removeByName($field);
                        $field = str_replace('ID', '', $field);
                        $fields->removeByName($field);
                    }
                }
            }
        }
        return $fields;
    }

    public function getType()
    {
        return 'Social Media Block';
    }
    private function getLayoutOptions(): array
    {
        $options = [];
        $configVars = Config::inst()->get('NourAlmasrieh\SocialsElement')['Layouts'];
        foreach ($configVars as $layoutVar){
            $layoutID = $layoutVar['id'];
            if($this->getLayoutVariableFromConfig($layoutID)){
                if(stristr($layoutVar['imgPath'], 'themes/') !== false){
                    $img = $layoutVar['imgPath'];
                } else {
                    $img = ModuleLoader::getModule('nour-almasrieh/socialwall')->getResource($layoutVar['imgPath']);
                    if($img){
                        $img->getURL();
                    }
                }
                $options[$layoutID] = [
                    'title' => $layoutVar['title'],
                    'image' => ($img) ? Director::absoluteBaseURL() . 'resources/' . $img : '',
                ];
            }
        }
        return $options;
    }
    private function getLayoutVariableFromConfig($layout){
        return $this->getConfigVariable('Layouts', $layout)['enabled'];
    }
    private function getConfigVariable($type, $field){
        return Config::inst()->get('NourAlmasrieh\SocialsElement')[$type][$field];
    }

    private function getReservedFields(): array
    {   
        return [
            'Title',
            'ShowTitle',
            'ElementStyle',
            'ExtraClass',
        ];
    }
    public function HTML(){
        return $this->owner->Content;
    }
    public function renderElementStyle(){
        $template = $this->owner->ElementStyle;
        if($template != ''){
            return $this->owner->renderWith('NourAlmasrieh/SocialWall/Includes/' . $template);
        }
        return null;
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
                "Date" =>  $instagram->CreatedDate,
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
                "Date" =>  $instagram->CreatedDate,
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
