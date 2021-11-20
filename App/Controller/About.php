<?php

namespace App\Controller;

use App\View\View;

class About
{
    public function index()
    {
        return View::render('about', []);
    }
}