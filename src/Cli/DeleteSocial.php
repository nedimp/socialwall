<?php

namespace NourAlmasrieh\SocialWall;

use SilverStripe\Dev\BuildTask;

class DeleteSocial extends BuildTask
{
    protected $title = 'Delete Social(Facebook + Instagram)';
    protected $description = 'Delete Social(Facebook + Instagram)';
    
    public function run($request) {
        $listPosts = AllPosts::get();
        foreach($listPosts as $item) { 
            $item->delete();
        }
    }
}
