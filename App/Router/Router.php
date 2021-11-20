<?php

namespace App\Router;

use App\Http\Request;
use App\Http\Response;
use App\Controller\Error;
use Exception;

class Router
{
    private $engine = [];
    private $request;
    private $class;
    private $method;

    public function __construct()
    {
        $this->request = new Request;
    }

    private function uri()
    {
        return $this->request->getUri();
    }

    private function methodHTTP()
    {
        return $this->request->getMethodHTTP();
    }

    private function convert($var)
    {
        $var = explode('/', $var);
        $var = array_values(array_filter($var));
        return $var;
    }

    public function get($route, $action)
    {
        $this->engine[] = [
            'get' => $route,
            'action' => $action
        ];
    }

    public function post($route, $action)
    {
        $this->engine[] = [
            'post' => $route,
            'action' => $action
        ];
    }

    public function put($route, $action)
    {
        $this->engine[] = [
            'put' => $route,
            'action' => $action
        ];
    }

    public function delete($route, $action)
    {
        $this->engine[] = [
            'delete' => $route,
            'action' => $action
        ];
    }

    private function router()
    {
        $uri = $this->convert($this->uri());
        $key = strtolower($this->methodHTTP());
        switch ($this->methodHTTP()) {
            case 'GET':
                $this->routes($uri, $key);
                break;
            case 'POST':
                $this->routes($uri, $key);
                break;
            case 'PUT':
                $this->routes($uri, $key);
                break;
            case 'DELETE':
                $this->routes($uri, $key);
                break;
        }
        return 'App\\Controller\\' . $this->class;
    }

    private function routes($uri, $key)
    {
        foreach ($this->engine as $on) {
            if (array_key_exists($key, $on)) {
                $route = $this->convert($on[$key]);
                if ($route === $uri) {
                    $action = explode("@", $on['action']);
                    $this->action($action);
                }
            }
        }
    }

    private function action($action)
    {
        $this->class = ucfirst($action[0]);
        $this->method = isset($action[1]) ? $action[1] : 'index';
    }

    private function content()
    {
        if (class_exists($this->router())) {
            $namespace = $this->router();
            if (!method_exists($namespace, $this->method)) {
                throw new Exception("Method Not Implement!", 405);
            }
            return call_user_func([new $namespace, $this->method]);
        }
        throw new Exception("Page Not Found!", 404);
    }

    public function run()
    {
        // die($this->methodHTTP());
        try {
            $response = new Response(200, $this->content());
            return $response->response();
        } catch (\Exception $exception) {
            $error = new Error();
            return $error->error($exception->getCode(), $exception->getMessage());
        }
    }
}
