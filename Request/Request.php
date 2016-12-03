<?php
/**
 * Created by iKNSA.
 * Author: Khalid Sookia <khalidsookia@gmail.com>
 * Date: 30/11/16
 * Time: 19:43
 */

namespace Romenys\Http\Request;

class Request
{
    private $get = [];

    private $post = [];

    private $cookie = [];

    private $files = [];

    private $env = [];

    private $session = [];

    private $server = [];

    private $url = [];

    public function __construct(array $get, array $post, array $cookie, array $files, array $env, array $session, array $server)
    {
        if (!empty($get)) $this->setGet($get);
        if (!empty($post)) $this->setPost($post);
        if (!empty($cookie)) $this->setCookie($cookie);
        if (!empty($env)) $this->setEnv($env);
        if (!empty($session)) $this->setSession($session);
        if (!empty($server)) $this->setServer($server); $this->setUrl();
        if (!empty($files)) $this->setFiles($files);
    }

    private function setUrl()
    {
        $this->url["scheme"] = $this->getServer()["REQUEST_SCHEME"];
        $this->url["host"] = $this->getServer()["HTTP_HOST"];
        $this->url["port"] = $this->getServer()["REMOTE_PORT"];
        $this->url["params"] = $this->getServer()["QUERY_STRING"];
        $this->url["uri"] = $this->getServer()["REQUEST_URI"];
        $this->url["script"] = $this->getServer()["SCRIPT_NAME"];
        $this->url["full"] = $this->url["scheme"] . "://" . $this->url["host"] . $this->url["uri"];
    }

    public function getUrl()
    {
        return $this->url;
    }

    private function setGet($get)
    {
        $this->get = filter_input_array(INPUT_GET, $get, true);

        return $this;
    }

    public function getGet()
    {
        return $this->get;
    }

    private function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    public function getPost()
    {
        return $this->post;
    }

    private function setCookie($cookie)
    {
        $this->cookie = filter_input_array(INPUT_COOKIE, $cookie, true);

        return $this;
    }

    public function getCookie()
    {
        return $this->cookie;
    }

    private function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    public function addSession($key, $value)
    {
        $this->session[$key] = $value;

        return $this;
    }

    public function removeSession($key)
    {
        if (isset($this->session[$key])) unset($this->session[$key]);

        return $this;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function clearSession()
    {
        $this->setSession([]);

        return $this;
    }

    private function setServer($server)
    {
        $this->server = filter_input_array(INPUT_SERVER, $server, true);

        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    private function setEnv($env)
    {
        $this->env = filter_input_array(INPUT_SERVER, $env, true);

        return $this;
    }

    public function getEnv()
    {
        return $this->env;
    }

    private function setFiles($files)
    {
        $filesArray = [];

        foreach ($files as $formName => $filesContainer) {
            foreach ($filesContainer as $key => $info) {
                foreach ($info as $fieldKey => $fieldValue) {
                    $filesArray[$formName][$fieldKey][$key] = $fieldValue;
                }
            }
        }

        $this->files = $filesArray;

        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getOneFile($form, $name)
    {
        return isset($this->files[$form][$name]) ? $this->files[$form][$name] : ["error" => "File Not Found"];
    }

    public function all()
    {
        return $this;
    }
}