<?php
namespace NourAlmasrieh\SocialWall;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class AllPosts extends DataObject
{
    private static $db = [
        'PlatformID'    =>  'Text',
        'PlatformLink'  =>  'Text',
        'Platform'  =>  'Text',
        'PlatformIconClass' =>  'Text',
        'Message'   =>  'Text',
        'ImageLink' =>  'Text',
        'CreatedDate' => 'Date',
        'Username' =>  'Text',
        'MediaType' =>  'Text',
        "ProfileImageLink" => 'Text',
    ];
    private static $has_one = [
        'Bild' => Image::class,
    ];
    private static $summary_fields = [
        'ID'  =>  'ID',
        'Platform'  =>  'Platform',
        'CreatedDate' => 'Date',
    ];
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName([
        ]);
        $fields->addFieldToTab('Root.Main', TextField::create('Username','Username'));
        $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Message','Content'));
        $fields->addFieldToTab('Root.Main', DateField::create('CreatedDate','Date'));
        $fields->addFieldToTab('Root.Main', UploadField::create('Bild','Bild'));
        $fields->addFieldToTab('Root.Main', TextField::create('MediaType','MediaType'));
        $fields->addFieldToTab('Root.Main', TextField::create('PlatformID','PlatformID'));
        $fields->addFieldToTab('Root.Main', TextField::create('PlatformLink','PlatformLink'));
        $fields->addFieldToTab('Root.Main', TextField::create('Platform','Platform'));
        $fields->addFieldToTab('Root.Main', TextField::create('PlatformIconClass','PlatformIconClass'));
        $fields->addFieldToTab('Root.Main', TextField::create('ProfileImageLink','ProfileImageLink'));
        return $fields;
    }
    public function Date(){
        $date = date('d. m. Y',strtotime($this->CreatedDate));
        return $date;
    }
    public function AllConfSocial(){
        return AllConfSocial::get()->first();
    }
}