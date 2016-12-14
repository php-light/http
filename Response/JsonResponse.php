<?php
/**
 * Created by iKNSA.
 * Author: Khalid Sookia <khalidsookia@gmail.com>
 * Date: 02/12/16
 * Time: 00:57
 */

namespace Romenys\Http\Response;

class JsonResponse extends Response
{
    private $data;

    private $options;

    public function __construct(array $data, $options = [], $status = 200, $headers = [], $json = false)
    {
        $this->setData($data, $json);
        $this->sendResponse();
        $this->setOptions($options);
    }

    private function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    private function setData($data, $json)
    {
        $json ? $this->data = $data : $this->data = json_encode($data, JSON_UNESCAPED_SLASHES, 512);

        return $this;
    }

    private function getData()
    {
        return $this->data;
    }

    private function sendResponse()
    {
        echo $this->getData();
    }
}
