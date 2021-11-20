<?php

namespace App\Controller;

class Destroy
{
    public function index()
    {
        session_start();
        session_unset();
        session_destroy();
        unset($_SESSION['data']);
        unset($_SESSION['image']);
        die('Sessão destruida com sucesso');
    }
}
