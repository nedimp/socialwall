<?php

namespace NourAlmasrieh\SocialWall;

use SilverStripe\Assets\Image;
use SilverStripe\Assets\Folder;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;

class InstagramProvider extends DataObject
{
    private static $db = [
        'AccessToken'  =>  'Text',
        'AppID' =>  'Text',
        'AppSecret' =>  'Text',
        'AccessToken'   =>  "Text",
        'ExpirationDate'    =>  'Text',
    ];
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName([
           "ExpirationDate",
        ]);
        $fields->addFieldToTab('Root.Main', TextField::create('AppID','AppID'));
        $fields->addFieldToTab('Root.Main', TextField::create('AppSecret','AppSecret'));
        $fields->addFieldToTab('Root.Main', TextareaField::create('AccessToken','Access Token'));
        return $fields;
    }

    private function PostExists($data)
    {
        return AllPosts::get()->filter([
            "Platform"  =>  "instagram",
            "PlatformID"    =>  $data["id"]
        ])->first();
    }

    public function RequestFreshData()
    {

        $ch = curl_init();
        $url = "https://graph.instagram.com/me/media?fields=id,caption,media_type,thumbnail_url,media_url,permalink,username,profile_pic,timestamp&access_token=";
        $url .= $this->AccessToken;
        
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch); 
        $data = json_decode($output,true);
        //Debug::dump($data);die;
        if($data != null && array_key_exists("data",$data))
        {
            $dataarr = $data["data"];
            //Debug::dump($dataarr);
            foreach($dataarr as $posts){
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
                    $newpost->ImageLink = $posts["media_url"];
                    $mediadataImage = $posts["media_url"];
                    $newpost->thumbnailUrl = $posts["thumbnail_url"];
                    $mediadatathumbnaile = $posts["thumbnail_url"];
                    $mediatype = $posts["media_type"];
                    //Debug::dump($mediatype);
                    if($mediatype == "VIDEO" ){
                        if($mediadatathumbnaile != '' && $newpost->BildID == 0){
                            $title = $posts["id"];
                            $filepath = '/tmp/' .  preg_replace('/[^\p{L}\p{N}\s]/u', '',$title) .'.jpg';
                            file_put_contents($filepath, file_get_contents($mediadatathumbnaile)); 
                            $file = Image::create();
                            $file->setFromLocalFile($filepath);
                            $file->ParentID = Folder::find_or_make('InstagramPosts')->ID;
                            $fileID = $file->write();
                            $file->publishSingle();
                            $file->publishFile();
                            $newpost->BildID = $fileID;
                        }
                    }else{
                        if($mediadataImage != '' && $newpost->BildID == 0){
                            $title = $posts["id"];
                            $filepath = '/tmp/' .  preg_replace('/[^\p{L}\p{N}\s]/u', '',$title) .'.jpg';
                            file_put_contents($filepath, file_get_contents($mediadataImage)); 
                            $file = Image::create();
                            $file->setFromLocalFile($filepath);
                            $file->ParentID = Folder::find_or_make('InstagramPosts')->ID;
                            $fileID = $file->write();
                            $file->publishSingle();
                            $file->publishFile();
                            $newpost->BildID = $fileID;
                        }
                    }
                    $content = $posts['caption'];
                    $content1 = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);
                    $newpost->Message = $content1;
                    $newpost->CreatedDate = $posts["timestamp"];
                    $newpost->PlatformLink = $posts["permalink"];
                    $newpost->Platform = "instagram";
                    $newpost->PlatformIconClass = "fa-instagram";
                    $newpost->MediaType = $posts["media_type"];
                    $newpost->Username = $posts["username"];
                    $newpost->write();
                }
            }
            
        }
        echo 'Fishing all Instagram Posts <br>';
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
        $url = "https://graph.facebook.com/v15.0/oauth/access_token";
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
