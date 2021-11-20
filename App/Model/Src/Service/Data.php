<?php

namespace App\Model\Src\Service;

use App\Model\DataBase\Connect;
use PDO;
use Exception;
use App\Model\Src\DeleteUnusedImg\DeleteUnusedImg;

class Data
{
    public function get()
    {
        new DeleteUnusedImg;
        $connect = Connect::getInstance();
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
        if (isset($id) && !empty($id)) {
            $selectID = "SELECT * FROM albums WHERE id = :id";
            $stmt = $connect->prepare($selectID);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->rowCount()
                ? $result = [$stmt->rowCount(), $stmt->fetchAll()]
                : throw new Exception("Album não encontrado.", 404);
        } elseif (isset($search)) {
            if (empty($search)) {
                throw new Exception("Campo vazio.", 400);
            }
            $selectSearch = "SELECT * FROM albums WHERE name LIKE '%$search%' OR band LIKE '%$search%'";
            $stmt = $connect->prepare($selectSearch);
            $stmt->execute();
            $stmt->rowCount()
                ? $result = [$stmt->rowCount(), $stmt->fetchAll()]
                : throw new Exception("Nenhum resultado encontrado.", 404);
        } else {
            $selectAll = "SELECT * FROM albums ORDER BY id DESC";
            $stmt = $connect->prepare($selectAll);
            $stmt->execute();
            $stmt->rowCount()
                ? $result = [$stmt->rowCount(), $stmt->fetchAll()]
                : throw new Exception("Nenhum registro encontrado", 200);
        }
        return $result;
    }

    public function post()
    {
        session_start();
        $connect = Connect::getInstance();
        $insert = 'INSERT INTO albums (name,band,year,genre,front_cover,tracks,name_img) VALUES (:name,:band,:year,:genre,:front_cover,:tracks,:name_img)';
        $confirm = filter_input(INPUT_POST, 'confirm', FILTER_SANITIZE_STRING);

        if (isset($confirm)) {
            $stmt = $connect->prepare($insert);
            $stmt->bindParam(':name', $_SESSION['data']->name);
            $stmt->bindParam(':band', $_SESSION['data']->band);
            $stmt->bindParam(':year', $_SESSION['data']->year);
            $stmt->bindParam(':genre', $_SESSION['data']->genre);
            $stmt->bindParam(':front_cover', $_SESSION['image']);
            $stmt->bindParam(':tracks', $_SESSION['data']->tracks);
            $stmt->bindParam(':name_img', $_SESSION['name_img']);
            if (!$stmt->execute()) {
                unset($_SESSION['data']);
                unset($_SESSION['image']);
                unset($_SESSION['name_img']);
                unset($_SESSION['tmp_name']);
                unset($_SESSION['dir']);
                throw new Exception("Album não cadastrados com sucesso.", 400);
            }

            unset($_SESSION['data']);
            unset($_SESSION['image']);
            unset($_SESSION['name_img']);
            unset($_SESSION['tmp_name']);
            unset($_SESSION['dir']);
            throw new Exception("Album cadastrados com sucesso.", 201);
        }
    }

    public function put($idImg = null, $imgUpdate = null, $nameImgUpdate = null)
    {
        $connect = Connect::getInstance();
        if (isset($idImg) && !empty($idImg)) {
            $update = 'UPDATE albums SET front_cover = :front_cover, name_img = :name_img WHERE id = :id';
            $stmt = $connect->prepare($update);
            $stmt->bindParam(':id', $idImg);
            $stmt->bindParam(':front_cover', $imgUpdate);
            $stmt->bindParam(':name_img', $nameImgUpdate);
            if (!$stmt->execute()) {
                die(json_encode([
                    'status' => 'error',
                    'data' => 'Falha ao salvar a imagem.'
                ]));
            }

            new DeleteUnusedImg;

            die(json_encode([
                'status' => 'success',
                'data' => 'Imagem salva com sucesso.'
            ]));
        }

        $put = file_get_contents('php://input');
        if (isset($put) && !empty($put)) {
            $data = (object)json_decode($put, true);
            $update = 'UPDATE albums SET name = :name, band = :band, year = :year, genre = :genre, tracks = :tracks  WHERE id = :id';
            $stmt = $connect->prepare($update);
            $stmt->bindParam(':id', $data->id);
            $stmt->bindParam(':name', $data->name);
            $stmt->bindParam(':band', $data->band);
            $stmt->bindParam(':year', $data->year);
            $stmt->bindParam(':genre', $data->genre);
            $stmt->bindParam(':tracks', $data->tracks);
            if (!$stmt->execute()) {
                throw new Exception("Album não atualizada com sucesso.", 400);
            }
            throw new Exception("Album atualizado com sucesso.", 201);
        }
    }

    public function delete()
    {
        $connect = Connect::getInstance();
        $delete = file_get_contents('php://input');
        if (isset($delete) && !empty($delete)) {
            $data = (object)json_decode($delete, true);
            $update = 'DELETE FROM albums  WHERE id = :id';
            $stmt = $connect->prepare($update);
            $stmt->bindParam(':id', $data->id);
            if (!$stmt->execute()) {
                throw new Exception("Album não deletado com sucesso.", 400);
            }

            new DeleteUnusedImg;

            throw new Exception("Album deletado com sucesso.", 201);
        }
    }
}
