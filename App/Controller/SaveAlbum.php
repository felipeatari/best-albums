<?php

namespace App\Controller;

use App\View\View;

class SaveAlbum
{
    public function index()
    {
        return View::render('save-album', []);
    }
}