<?php

namespace App\Controller;

class Session
{
    public function index()
    {
        session_start();
        if (isset($_SESSION) && !empty($_SESSION)) {
            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";exit;
        } else {
             die('Não há sessões');
        }
    }
}
