<?php

namespace App\Controller;

use App\View\View;
use App\Model\DataBase\Connect;

class Admin
{
    public function index()
    {
        session_start();
        if (isset($_SESSION['logged']) && !empty($_SESSION['logged'])) {
            return View::render('admin-logged', []);
        }
        return View::render('admin-login', []);
    }

    public function loggedVerify() 
    {
        session_start();
        if (isset($_SESSION['logged']) && !empty($_SESSION['logged'])) {
            die(json_encode(["status" => "logged"]));
        }
        die("No session!");
    }

    public function loginInto()
    {
        session_start();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Headers: *');
        $post = file_get_contents('php://input');
        $json = (object)json_decode($post, true); 
        $connect = Connect::getInstance();
        $selectAdmin = "SELECT * FROM admin";
        $stmt = $connect->prepare($selectAdmin);
        $stmt->execute();
        $admin = $stmt->fetch();
        $api = new \App\Model\Src\Service\Data;
        if (($json->user === $admin->user) && (password_verify($json->password, $admin->password))) {
            $_SESSION['logged'] = true;
            die(json_encode([
                "status" => "success",
                "msg" => "Logado com sucesso!",
                "total" => $api->get()[0],
                "data" => $api->get()[1]
            ]));
        }
        die(json_encode([
            "status" => "error",
            "msg" => "Usuário não existe!"
        ]));
    }

    public function albumUpdate()
    {
        $getId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        return View::render('admin-update', [
            'id' => $getId
        ]);
    }

    public function signOut()
    {
        session_start();
        session_destroy();
        unset($_SESSION['logged']);
        die(json_encode([
            "status" => "ok",
            "msg" => "Session closed!"
        ]));
    }
}
