<?php

namespace QInterface\Libs;

use Curl;
use Str;
use Exception;
use Config;
use Session;
use Useragent;
use Request;

class QIAPI
{
    /**
     * System's target ID.
     */
    const TARGET_ID = 1;

    /**
     * Response returned from the API
     *
     * @var array
     */
    private $response;

    /**
     * Curl object
     *
     * @var Curl
     */
    protected $curl;

    /**
     * The API's base url
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Valid API's endpoints
     *
     * @var array
     */
    protected $urls = array(
        'gen_otp'       => '/at/user/generate_otp',
        'user_info'     => '/at/user/info',
        'get_province'  => 'qinternal/get/province'
    );

    /**
     * Currently logged in user's User Agent
     *
     * @var string
     */
    public $userAgent;

    /**
     * Currently logged in user's IP address
     *
     * @var string
     */
    public $ipAddress;

    /**
     * Session key
     *
     * @var string
     */
    public $session;

    /**
     * Symmetric key
     *
     * @var string
     */
    public $symkey;

    /**
     * The index of the selected API's endpoint
     *
     * @var string
     */
    public $urlTarget;

    /**
     * Extra parameters to be sent to the API
     *
     * @var array
     */
    public $extraParams = array();

    /**
     * Custom API Url Path
     *
     * @var String
     */
    public $rawPath;

    /*
     * Class constructor.
     * Accepts an array that overrides the default properties except the curl
     * and baseUrl properties.
     *
     * @param $params array
     */
    public function __construct($params = array())
    {
        foreach ($params as $key => $value) {
            $this->{Str::camel($key)} = $value;
        }

        $this->curl = new Curl;
        $this->baseUrl = Config::get('api.qeon.base_url');
    }

    /**
     * Send the request to the API
     *
     * @return QIAPI
     */
    public function send()
    {
        if ( ! isset($this->urls[$this->urlTarget]) && ! $this->rawPath) {
            throw new Exception('Undefined target URL');
        }

        $url = $this->rawPath ? $this->baseUrl . $this->rawPath
                              : $this->baseUrl . $this->urls[$this->urlTarget];
        $params = array_merge(array(
            'ua'         => $this->userAgent,
            'ip_address' => $this->ipAddress,
            'session'    => $this->session,
            'symkey'     => $this->symkey,
            'target_id'  => static::TARGET_ID,
        ), $this->extraParams);

        $response = $this->curl->simple_post($url, $params);
        $this->response = null;
        if ($response) {
            $this->response = json_decode($response, true);
        }
        // echo "\nurl\n";
        // print_r($url);
        // echo "\nparams\n";
        // print_r($params);
        // echo "\nresponse\n";
        // print_r($response);
        // echo "\nend\n";

        return $this;
    }

    public function test()
    {
        if ( ! isset($this->urls[$this->urlTarget]) && ! $this->rawPath) {
            throw new Exception('Undefined target URL');
        }

        $url = $this->rawPath ? $this->baseUrl . $this->rawPath
                              : $this->baseUrl . $this->urls[$this->urlTarget];
        $params = array_merge(array(
            'ua'         => $this->userAgent,
            'ip_address' => $this->ipAddress,
            'session'    => $this->session,
            'symkey'     => $this->symkey,
            'target_id'  => static::TARGET_ID,
        ), $this->extraParams);

        $response = $this->curl->simple_post("http://jason.qeon.server/test", $params);
        echo "\nparams\n";
        print_r($params);
        echo "\nresponse\n";
        print_r($response);
        echo "\nend\n";
        die();
    }

    /**
     * Return the response from the API
     *
     * @return mixed
     */
    public function read()
    {
        if ($this->response) 
        {
            if($this->getStatus())
            {
                if(isset($this->response["data"]))
                {
                    return $this->response['data'];
                } else {
                    return $this->getMessage();
                }
            } else {
                return $this->getMessage();
            }
        }

        return null;
    }

    public function getData()
    {
        if($this->response)
        {
            return isset($this->response['data']) ? $this->response['data'] : null;
        }

        return null;
    }

    public function getStatus()
    {
        if($this->response)
        {
            return $this->response['success'];
        }

        return false;
    }

    public function getMessage()
    {
        if($this->response)
        {
            if(isset($this->response["message"]))
            {
                return $this->response["message"];
            } else {
                return null;
            }
        }

        return null;
    }

    public function getResponse()
    {
        if($this->response)
        {
            return $this->response;
        } else {
            return null;
        }
    }
}