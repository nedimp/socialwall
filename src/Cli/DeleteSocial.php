<?php

namespace NourAlmasrieh\SocialWall;

use SilverStripe\Dev\BuildTask;

class DeleteSocial extends BuildTask
{
    protected $title = 'Delete Social(Facebook + Instagram)';
    protected $description = 'Delete Social(Facebook + Instagram)';
    
    public function run($request) {
        $listFacebookPosts = FacebookPosts::get();
        foreach($listFacebookPosts as $item) { 
            $item->delete();
        }
        $listInstagramPosts = InstagramPosts::get();
        foreach($listInstagramPosts as $item) { 
            $item->delete();
        }
    }
}
