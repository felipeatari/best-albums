<?php

namespace App\Controller;

class SetData
{
    public function index()
    {
        session_start();
        $dataApi = new \App\Model\Src\Service\Data;
        $post = file_get_contents('php://input');
        if (isset($post) && !empty($post)) {
            $_SESSION['data'] = (object)json_decode($post, true);
            $this->validateForm();
            die(json_encode([
                'status' => 'success',
                'data' => $_SESSION['data']
            ]));
        }

        if (isset($_FILES['image']) && !empty($_FILES['image'])) {
            $file = (object)$_FILES['image'];
            $tmpName = $file->tmp_name;
            $extension = pathinfo($file->name, PATHINFO_EXTENSION);
            $nameImg = uniqid() . '.' . $extension;
            $dir = 'uploads/images/' . $nameImg;
            $_SESSION['image'] = ROOT . '/' . $dir;
            $_SESSION['name_img'] = $nameImg;
            $this->validateImg();
            if (!move_uploaded_file($tmpName, $dir)) {
                die(json_encode([
                    'status' => 'error',
                    'data' => 'Falha ao salvar a imagem.'
                ]));
            }
            die(json_encode([
                'status' => 'success',
                'data' => $file
            ]));
        }

        if (isset($_FILES['imgUpdate']) && !empty($_FILES['imgUpdate'])) {
            $idImg = filter_input(INPUT_POST, 'idImg', FILTER_VALIDATE_INT);
            $file = (object)$_FILES['imgUpdate'];
            $tmpName = $file->tmp_name;
            $extension = pathinfo($file->name, PATHINFO_EXTENSION);
            $nameImgUpdate = uniqid() . '.' . $extension;
            $dir = 'uploads/images/' . $nameImgUpdate;
            $imgUpdate = ROOT . '/' . $dir;
            $this->validateImgUpdate($idImg, $imgUpdate, $nameImgUpdate);
            if (!move_uploaded_file($tmpName, $dir)) {
                die(json_encode([
                    'status' => 'error',
                    'data' => 'Falha ao editar a imagem.'
                ]));
            }
            $dataApi->put($idImg, $imgUpdate, $nameImgUpdate);
        }
    }

    public function validateForm()
    {
        if (!isset($_SESSION['data']->name) || !isset($_SESSION['data']->band) || !isset($_SESSION['data']->year) || !isset($_SESSION['data']->genre) || !isset($_SESSION['data']->tracks)) {
            unset($_SESSION['data']);
            session_destroy();
            die(json_encode([
                'status' => 'error',
                'data' => 'Campo(s) vazio(s) ou inexistente(s).'
            ]));
        }
        if (empty($_SESSION['data']->name) || empty($_SESSION['data']->band) || empty($_SESSION['data']->year) || empty($_SESSION['data']->genre) || empty($_SESSION['data']->tracks)) {
            unset($_SESSION['data']);
            session_destroy();
            die(json_encode([
                'status' => 'error',
                'data' => 'Campo(s) vazio(s) ou inexistente(s).'
            ]));
        }
    }

    public function validateImg()
    {
        if (!isset($_SESSION['image'])) {
            unset($_SESSION['image']);
            session_destroy();
            die(json_encode([
                'status' => 'error',
                'data' => 'Campo(s) vazio(s) ou inexistente(s).'
            ]));
        }
        if (empty($_SESSION['image'])) {
            unset($_SESSION['image']);
            session_destroy();
            die(json_encode([
                'status' => 'error',
                'data' => 'Campo(s) vazio(s) ou inexistente(s).'
            ]));
        }
    }

    public function validateImgUpdate($idImg, $imgUpdate, $nameImgUpdate)
    {
        if (!isset($idImg) || empty($idImg)) {
            die(json_encode([
                'status' => 'error',
                'data' => 'Campo(s) vazio(s) ou inexistente(s).'
            ]));
        }
        if (!isset($imgUpdate) || empty($imgUpdate)) {
            die(json_encode([
                'status' => 'error',
                'data' => 'Campo(s) vazio(s) ou inexistente(s).'
            ]));
        }
        if (!isset($nameImgUpdate) || empty($nameImgUpdate)) {
            die(json_encode([
                'status' => 'error',
                'data' => 'Campo(s) vazio(s) ou inexistente(s).'
            ]));
        }
    }
}