<?php

namespace NourAlmasrieh\SocialsElement;

use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\DropdownField;
use NourAlmasrieh\SocialPage\SocialPage;
use UncleCheese\Forms\ImageOptionsetField;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class SocialsElement extends BaseElement
{
    private static $table_name = 'SocialsElement';
    private static $singular_name = 'Socials Block';
    private static $plural_name = 'Socials Blöcke';
    private static $description = 'Zeigt Sociel Media Einträge an';
    private static $inline_editable = false;
    private static $db = [
        'SubTitle' => 'Text',
        'Content' => 'HTMLText',
        'ButtonCaption' => 'Text',
        'ExternalLink' => 'Text',
        'ElementStyle' => 'Text',
    ];
    private static $has_one = [
        'SocialPage' => SocialPage::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([

        ]);
        $layoutField = ImageOptionsetField::create('ElementStyle', 'Layout wählen')->setSource($this->getLayoutOptions());
        $layoutField->setImageHeight($this->getConfigVariable('FieldSettings', 'ImageHeight'));
        $layoutField->setImageWidth($this->getConfigVariable('FieldSettings', 'ImageWidth'));
        $layoutField->setDescription($this->getConfigVariable('FieldSettings', 'FieldDescription'));
        $fields->addFieldToTab('Root.Main', $layoutField, 'Title');

        $fields->addFieldsToTab('Root.Main', [
            HTMLEditorField::create('Content', 'Inhalt')->setRows(8),
            DropdownField::create('SocialPageID', 'SocialPage', SocialPage::get()->map())
                ->setDescription('Hier muss ein SocialPage ausgewählt werden, von dem die Beiträge ausgegeben werden.')
                ->setEmptyString('Auswählen'),
            TextField::create('ButtonDesc', 'Button Beschriftung'),
            TextField::create('ExternalLink', 'Externe Verlinkung')
                ->setDescription('Muss mit "https://" gepflegt werden.<br>Wird alternativ zur internen Verlinkung ausgegeben.'),
        ]);
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
    public function renderElementStyle(){
        $template = $this->owner->ElementStyle;
        if($template != ''){
            return $this->owner->renderWith('Includes/' . $template);
        }
        return null;
    }
}
