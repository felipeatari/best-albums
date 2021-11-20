<?php

namespace App\Controller;

use Exception;

class Api
{
    public function data()
    {
        try {
            $this->sendHeaders();
            $methodHttp = (new \App\Http\Request)->getMethodHTTP();
            $api = 'App\\Model\\Src\\Service\\Data';
            if (!method_exists($api, $methodHttp)) {
                throw new Exception("Method Not Implement!", 405);
            }
            $result = call_user_func([new $api, strtolower($methodHttp)]);
            return $this->result($result);
        } catch (Exception $exception) {
            return $this->exception(
                $exception->getCode(),
                $exception->getMessage()
            );
        }
    }

    private function result($result = [])
    {
        die(json_encode([
            'status' => 'success',
            'response' => 200,
            'total' => $result[0],
            'data' => $result[1]
        ]));
    }

    private function exception($code, $message)
    {
        $status = ($code > 199 && $code < 400) ? 'success' : 'error';
        http_response_code($code);
        die(json_encode([
            'status' => $status,
            'response' => $code,
            'total' => null,
            'data' => $message,
        ]));
    }

    private function sendHeaders()
    {
        return [
            header('Access-Control-Allow-Origin: *'),
            header('Access-Control-Allow-Headers: *'),
            header('Content-Type: application/json; charset=UTF-8'),
        ];
    }
}
