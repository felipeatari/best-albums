<?php

namespace App\Controller;

use App\View\View;

class Error
{
    public function error($code, $message)
    {
        return View::render('error', [
            'code' => $code,
            'message' => $message
        ]);
    }
}