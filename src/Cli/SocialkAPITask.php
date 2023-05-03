<?php
namespace NourAlmasrieh\SocialkAPITask;
use SilverStripe\Dev\BuildTask;
use NourAlmasrieh\FacebookProvider\FacebookProvider;
use NourAlmasrieh\InstagramProvider\InstagramProvider;

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
