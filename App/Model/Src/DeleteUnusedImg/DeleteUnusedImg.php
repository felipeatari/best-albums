<?php

namespace App\Model\Src\DeleteUnusedImg;

class DeleteUnusedImg
{
    public function __construct()
    {
        $nameImg = [];
        $connect = \App\Model\DataBase\Connect::getInstance();
        $selectAll = "SELECT * FROM albums ORDER BY id DESC";
        $stmt = $connect->prepare($selectAll);
        $stmt->execute();
        if ($stmt->rowCount()) {
            foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $data) {
                $nameImg[] = $data['name_img'];
            }
        }
        $listFiles = [];
        $type = ['png', 'jpg', 'jpeg', 'ico'];
        if ($handle = opendir('uploads/images/')) {
            while ($entry = readdir($handle)) {
                $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                if (in_array($extension, $type)) {
                    $listFiles[] = $entry;
                }
            }
            closedir();
        }
        $result = array_diff($listFiles, $nameImg);
        return $result = array_map(function ($file) {
            if (isset($file) && !empty($file)) {
                if (file_exists('uploads/images/' . $file)) {
                    unlink('uploads/images/' . $file);
                }
            }
        }, $result);
    }
}