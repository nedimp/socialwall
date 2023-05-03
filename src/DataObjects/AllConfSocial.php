<?php

namespace NourAlmasrieh\AllConfSocial;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;

class AllConfSocial extends DataObject
{
    private static $singular_name = "Einstellungen";
    private static $plural_name = "Einstellungen";

    private static $db = [
        "Username" => 'Text',
        "PlatformLink" =>  'Text',
    ];
    private static $has_one = [
        'ProfileImage' => Image::class,
    ];
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', TextField::create('Username','Profile Username'));
        $fields->addFieldToTab('Root.Main', UploadField::create('ProfileImage','Profile Bild'));
        $fields->addFieldToTab('Root.Main', TextField::create('PlatformLink','Platform Link'));

        return $fields;
    }
}