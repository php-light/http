<?php
/**
 * Created by iKNSA.
 * Author: Khalid Sookia <khalidsookia@gmail.com>
 * Date: 02/12/16
 * Time: 00:57
 */

namespace PhpLight\Http\Response;

class JsonResponse extends Response
{
    private $data;

    private $options;

    private $status;
    
    public function __construct(array $data, $options = [], $status = 200, $headers = [], $json = false)
    {
        $this->setOptions($options);
        $this->setStatus($status);
        $this->setData($data, $json);
        $this->sendResponse();
    }

    private function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }
    private function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    private function setData($data, $json)
    {
        $json ? $this->data = $data : $this->data = json_encode($data, JSON_UNESCAPED_SLASHES, 512);

        if (json_last_error()) {
            $this->data = json_encode(json_last_error_msg());
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
        }

        return $this;
    }

    private function getData()
    {
        return $this->data;
    }

    private function sendResponse()
    {
        header("HTTP/1.1 ". $this->status);
        echo $this->getData();
    }
}
