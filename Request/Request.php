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

    public function getSession()
    {
        return $this->session;
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

    public function all()
    {
        return $this;
    }
}