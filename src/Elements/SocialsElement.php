<?php

namespace NourAlmasrieh\SocialWall;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use NourAlmasrieh\SocialWall\AllPosts;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\FieldType\DBVarchar;
use DNADesign\Elemental\Models\BaseElement;
use NourAlmasrieh\SocialWall\SpeziellePost;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use RyanPotter\SilverStripeColorField\Forms\ColorField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Core\Manifest\ModuleResourceLoader;

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
        'SubTitle' => 'Text',
        'Content' => 'HTMLText',
        'ShowOnLimitPosts' => 'Boolean(0)',
        'ShowOnFacebook' => 'Boolean(0)',
        'ShowOnInstagram' => 'Boolean(0)',
        'ShowOnMasonry' => 'Boolean(0)',
        'PostsStyle' => 'Text',
    ];
    private static $has_one = [
        'LinkedPage' => SiteTree::class,
    ];
    private static $has_many = [
        'SpeziellePosts' => SpeziellePost::class
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName([
            'HTML',
            'SpeziellePosts'
        ]);
        
        $fields->addFieldToTab('Root.Main', DropdownField::create('ElementStyle', 'Layout wählen', $this->getLayoutOptions())->setEmptyString('Select Layout'), 'Title');

        /*Get all fields*/
        $schema = DataObject::getSchema();
        $allFields = $schema->fieldSpecs($this);
        $columns = array_keys($allFields);

        $fields->insertBefore('Title', DropdownField::create('PostsStyle', 'Posts', [
            'mixed' => 'Zufällige Posts (Neueste Post) angezeitgt wird',
            'custom' => 'Spezielle Auswahl - Posts können individuell angepasst werden',
            'bothstyle' => 'Erste 4er-Reihe - die Neueste Post & Zweite 4er-Reihe - die besondere Post können angepasst werden',
        ])->setEmptyString('Auswählen'));
        $fields->addFieldsToTab('Root.Main',  ColorField::create('BackgroundColor','Hintergrund Farbe'));   
        $fields->addFieldsToTab('Root.Main', TextField::create('SubTitle', 'SubTitle'));;
        $fields->addFieldsToTab('Root.Main', HTMLEditorField::create('Content', 'Inhalt')->setRows(8));
        $fields->addFieldsToTab('Root.Main', TextField::create('ButtonCaption', 'Button Beschriftung'),'SocialPageID');
        $fields->insertAfter('ButtonCaption', TreeDropdownField::create('LinkedPageID', 'Interne Verlinkung', SiteTree::class)
            ->setDescription('Wird bevorzugt ausgegeben.')
            ->setEmptyString('Auswählen'));
        $fields->addFieldsToTab('Root.Main', TextField::create('ExternalLink', 'Externe Verlinkung')
            ->setDescription('Muss mit "https://" gepflegt werden.<br>Wird alternativ zur internen Verlinkung ausgegeben.'),'SocialPageID');

        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnLimitPosts', 'Sollen die Posts auf 8 Stück Limitiert werden?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnFacebook', 'Sollen nur die Posts von Facebook angezeigt?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnInstagram', 'Sollen nur die Posts von Instagram angezeigt?'));
        $fields->addFieldToTab('Root.Main', CheckboxField::create('ShowOnMasonry', 'In Masonry angezeigt?'));
       
        if ($this->PostsStyle == 'custom' || $this->owner->PostsStyle == 'bothstyle') {
            $fields->addFieldsToTab('Root.Posts', 
            GridField::create(
                'SpeziellePosts',
                'Spezielle Posts',
                $this->SpeziellePosts()->Sort('SortOrder ASC'),
                GridFieldConfig_RecordEditor::create()
                    ->addComponent(GridFieldOrderableRows::create('SortOrder'))
            ));
        }
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
                    // Get the module resource path using ModuleResourceLoader
                $modulePath = ModuleResourceLoader::singleton()->resolveURL('nour-almasrieh/socialwall', $layoutVar['imgPath']);
                    if ($modulePath) {
                    $img = $modulePath;
                    }
                }
                $options[$layoutID] = [
                    'title' => $layoutVar['title'],
                    'image' => ($img) ? Director::absoluteBaseURL() . $img : '',
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
    public function getPosts()
    {
        if($this->ShowOnLimitPosts){
            return AllPosts::get()->limit(8); 
        }
        return AllPosts::get();
    }
    public function getOnPosts()
    {
        $arrayfiltter = [];
        if($this->ShowOnFacebook){
            $arrayfiltter[] = 'facebook';
        }
        if($this->ShowOnInstagram){
            $arrayfiltter[] = 'instagram';
        }
        if (count($arrayfiltter) > 0){
            return $this->getPosts()->filter("Platform", $arrayfiltter);
        }
        return $this->getPosts();
    }
}
