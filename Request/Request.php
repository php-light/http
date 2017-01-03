<?php
/**
 * Created by iKNSA.
 * Author: Khalid Sookia <khalidsookia@gmail.com>
 * Date: 30/11/16
 * Time: 19:43
 */

namespace Romenys\Http\Request;

use BNPPARIBAS\REFOG\AuthenticatorBundle\Entity\User;
use Romenys\Framework\Components\Config;
use Romenys\Helpers\UploadFile;

class Request
{
    const REQUEST_METHOD_POST = "POST";
    const REQUEST_METHOD_GET = "GET";

    private $get = [];

    private $post = [];

    private $cookie = [];

    private $files = [];

    private $env = [];

    private $session = [];

    private $server = [];

    private $url = [];

    private $uploadedFiles = [];

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
        $this->url["scheme"] = isset($this->getServer()["REQUEST_SCHEME"]) ? $this->getServer()["REQUEST_SCHEME"] . "://" : "http://";
        $this->url["host"] = $this->getServer()["HTTP_HOST"];
        $this->url["port"] = $this->getServer()["REMOTE_PORT"];
        $this->url["params"] = $this->getServer()["QUERY_STRING"];
        $this->url["uri"] = $this->getServer()["REQUEST_URI"];
        $this->url["script"] = $this->getServer()["SCRIPT_NAME"];
        $this->url["full"] = $this->url["scheme"] . $this->url["host"] . $this->url["uri"];
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
        $_SESSION[$key] = $value;

        return $this;
    }

    public function removeSession($key)
    {
        if (isset($_SESSION[$key])) unset($_SESSION[$key]);

        return $this;
    }

    public function getSession()
    {
        return $_SESSION;
    }

    public function clearSession()
    {
        $this->setSession([]);

        return $this;
    }

    public function isAuthenticated()
    {
        $isAuthenticated = false;

        if (isset($this->getSession()["security"]["isAuthenticated"]) && $this->getSession()["security"]["isAuthenticated"] === true)
            $isAuthenticated = true;

        return $isAuthenticated;
    }

    /**
     * @throws \Exception
     * @return User|string
     */
    public function getUser()
    {
        $userArray = $this->isAuthenticated() ? $this->getSession()["security"]["user"] : false;

        if ($userArray) {
            $config = new Config();
            $userModel = empty($config->getConfig()["user_class"]) ? false : $config->getConfig()["user_class"];

            if (!$userModel) throw new \Exception("You must provide a user class in your config");

            $user = new $userModel($userArray);

            return $user;
        } else {
            return "No user is connected";
        }
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

    public function uploadFiles()
    {
        $upload = new UploadFile($this->getFiles(), null, true);

        $this->uploadedFiles = $upload->getFiles();

        return $this;
    }

    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    public function getMethod()
    {
        return empty($this->getServer()["REQUEST_METHOD"]) ? false : strtoupper($this->getServer()["REQUEST_METHOD"]);
    }

    public function removeFile($file)
    {
        if (is_file($file)) {
            if (unlink($file)) {
                $done = true;

                $uploadedFiles = [];

                foreach ($this->getUploadedFiles() as $uploadedFile) {
                    if ($uploadedFile["uploaded_file"] !== $file) {
                        $uploadedFiles[] = $uploadedFile;
                    }
                }

                $this->uploadedFiles = $uploadedFiles;
            } else {
                $done = false;
            }
        } else {
            $done = false;
        }

        return $done;
    }

    public function all()
    {
        return $this;
    }
}
