<?php

namespace App\Controller;

use App\View\View;

class Home
{
    public function index()
    {
        return View::render('home', []);
    }

    public function openAlbum()
    {
        $getId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        return View::render('home-open-album', [
            'id' => $getId
        ]);
    }
}