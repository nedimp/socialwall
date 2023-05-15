<?php

namespace NourAlmasrieh\SocialWall;

use SilverStripe\Assets\Image;
use SilverStripe\Assets\Folder;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;

class FacebookProvider extends DataObject
{
    private static $db = [
        'AccessToken'  =>  'Text',
        'AppID' =>  'Text',
        'AppSecret' =>  'Text',
        'ExpirationDate'    =>  'Text',
        'FacebookPageID'    =>  'Text',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
           "ExpirationDate",
        ]);

        $fields->addFieldToTab('Root.Main', TextareaField::create('AccessToken','Access Token')
            ->setDescription("https://developers.facebook.com/docs/graph-api/guides/explorer#get-token-dropdown"));
        $fields->addFieldToTab('Root.Main', TextField::create('AppID','AppID'));
        $fields->addFieldToTab('Root.Main', TextField::create('AppSecret','AppSecret'));
        $fields->addFieldToTab('Root.Main', TextField::create('FacebookPageID','Facebook Seiten-ID')
            ->setDescription('Hier muss die Seiten-ID hinterlegt werden, von der die Posts genommen werden sollen.'));
        return $fields;
    }
    private function PostExists($data)
    {
        return AllPosts::get()->filter([
            "Platform"  =>  "facebook",
            "PlatformID"    =>  $data["id"]
        ])->first();
    }
    public function RequestFreshData()
    {
        $ch = curl_init();
        if($this->FacebookPageID != ''){
            $url = "https://graph.facebook.com/v16.0/" . $this->FacebookPageID;
        } else {
            $url = "https://graph.facebook.com/v16.0/me";
        }
        $url .= "?access_token=" . $this->AccessToken;
        $url .= "&fields=posts{permalink_url,message,full_picture,picture,updated_time},username,picture{url}";
        $url .= "&format=json&method=get";
        // set url
       
        curl_setopt($ch, CURLOPT_URL, $url);
        //Debug::dump($url);die;
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch); 
        $data = json_decode($output,true);
        if(array_key_exists("posts",$data))
        {
            $dataarr = $data["posts"]["data"];
            //Debug::dump($dataarr);
            foreach($dataarr as $posts){
                 //Debug::dump($dataarr);die;
                if(!$this->PostExists($posts))
                {
                    $newpost = AllPosts::get()->filterAny([
                        "PlatformID" => $dataarr->Id,
                    ]);
                    if(($newpost = AllPosts::get()->filter("PlatformID",$dataarr->Id)->first()) == null)
                    {
                        $newpost = AllPosts::create();
                    }
                    $newpost->PlatformID = $posts["id"];
                    $newpost->ImageLink = $posts["full_picture"];
                    $mediadataImage = $posts["full_picture"];
                    if($mediadataImage != '' && $newpost->BildID == 0){
                        $title = $posts["id"];
                        $filepath = '/tmp/' .  preg_replace('/[^\p{L}\p{N}\s]/u', '',$title) .'.jpg';
                        file_put_contents($filepath, file_get_contents($mediadataImage)); 
                        $file = Image::create();
                        $file->setFromLocalFile($filepath);
                        $file->ParentID = Folder::find_or_make('AllPosts')->ID;
                        $fileID = $file->write();
                        $file->publishSingle();
                        $file->publishFile();
                        $newpost->BildID = $fileID;
                    }
                    $content = $posts['message'];
                    $content1 = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);
                    $newpost->Message = $content1;
                    $newpost->CreatedDate = $posts["updated_time"];
                    $newpost->UserName = $posts["username"];
                    $newpost->PlatformLink = $posts["permalink_url"];
                    $newpost->ProfileImageLink = $data["picture"]["data"]["url"];
                    $newpost->Platform = "facebook";
                    $newpost->PlatformIconClass = "fa-facebook-f";
                    $newpost->write();
                }
            }
        }
        echo 'Fishing all Facebook Posts <br>';
    }
    public function RequestAccessToken()
    {
        $nowTimeStamp = strtotime(date("d-m-y H:i:s"));
        if($this->ExpirationDate > $nowTimeStamp)
        {
            return;
        }
        $ch = curl_init();
        $accesstoken = "";
        if($this->AccessToken != "")
        {
            $accesstoken = $this->AccessToken;
        }
        $url = "https://graph.facebook.com/v16.0/oauth/access_token";
        $url .= "?grant_type=fb_exchange_token";
        $url .= "&client_id=".$this->AppID;
        $url .= "&client_secret=".$this->AppSecret;
        $url .= "&fb_exchange_token=".$accesstoken;
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
        $data = json_decode($output,true);
        if(array_key_exists("access_token",$data) && array_key_exists("expires_in",$data))
        {
            $token = $data["access_token"];
            $expiresInSeconds = $data["expires_in"];
            $expiresInSeconds -= 3600;
            $nowTimeStamp = strtotime(date("d-m-y H:i:s"));
            $nowTimeStamp += $expiresInSeconds;
            $this->ExpirationDate = $nowTimeStamp;
            $this->AccessToken = $token;
        }
        $this->write();
    }
}