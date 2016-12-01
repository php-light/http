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


    public function __construct(array $get, array $post, array $cookie, array $files, array $env, array $session, array $server)
    {
        if (!empty($get)) $this->setGet($get);
        if (!empty($get)) $this->setPost($post);
        if (!empty($get)) $this->setCookie($cookie);
        if (!empty($get)) $this->setEnv($env);
        if (!empty($get)) $this->setSession($session);
        if (!empty($get)) $this->setServer($server);
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
        $this->post = filter_input_array(INPUT_POST, $post, true);

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
        return serialize($this);
    }
}