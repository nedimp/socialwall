<?php
namespace NourAlmasrieh\SocialWall;

use SilverStripe\Dev\BuildTask;

class SocialkAPITask extends BuildTask
{
    protected $title = 'Refresh Social (Facebook + Instagram) API Credentials';
    protected $description = 'Refresh Social (Facebook + Instagram) API Credentials';
    
    public function process()
    {
        $this->execute();
    }
    public function execute()
    {
        if(InstagramProvider::get() != null){
            foreach (InstagramProvider::get() as $instaProvider) {
                $instaProvider->RequestAccessToken();
                $instaProvider->RequestFreshData();
            }
        }
        
        if(FacebookProvider::get() != null){
            foreach (FacebookProvider::get() as $fbProvider){
                $fbProvider->RequestAccessToken();
                $fbProvider->RequestFreshData();
            }
        }
    }
    public function run($request)
    {
        $this->execute();
    }
}
