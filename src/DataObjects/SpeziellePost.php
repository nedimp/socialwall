<?php

namespace NourAlmasrieh\SocialWall;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Forms\DropdownField;
use NourAlmasrieh\SocialWall\AllPosts;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use Symbiote\GridFieldExtensions\GridFieldTitleHeader;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldAddNewInlineButton;

class SpeziellePost extends DataObject{
    private static $db = [
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'AllPosts' => AllPosts::class,
        'SocialsElement' => SocialsElement::class,
    ];

    private static $summary_fields = [
        'AllPosts.ID'  =>  'ID',
        'AllPosts.Platform'  =>  'Platform',
        'AllPosts.CreatedDate' => 'CreatedDate',
        'AllPosts.Message'  =>  'Message',
    ];
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'SocialsElementID',
            'SortOrder',
        ]);
        $fields->addFieldToTab('Root.Main', new DropdownField('AllPostsID', 'Posts auswählen', AllPosts::get()->exclude('ID', 'ID')->map('ID', 'Message')));
        return $fields;
    }
}