<?php

namespace Bankopen\OpenMicroserviceSdk\Utility;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

/**
 * class GuzzleRequestUtility
 */
class RemoteRequest
{
    /**
     * variable to set the options for http rquest
     *
     * @var array
     */
    protected $baseOptions;
    /**
     * set the response array
     *
     * @var null
     */
    protected $response;

    /**
     * @param  null  $url
     * @param  null  $params
     * @param  null  $headers
     */
    public function __construct($url = null, $params = null, $headers = [])
    {
        $this->url = $url;
        $this->params = $params;
        $this->headers = $headers;

        $this->defaultBaseOptions();
    }

    /**
     * method for get request
     */
    public function get()
    {
        $this->clientRequest('get');

        return $this;
    }

    /**
     * method for post request
     */
    public function post()
    {
        $this->clientRequest('post');

        return $this;
    }

    /**
     * method for put request
     */
    public function put()
    {
        $this->clientRequest('put');

        return $this;
    }

    /**
     * set headers in base options
     *
     * @param $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->baseOptions['headers'] = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * set the auth key and password
     *
     * @param $key
     * @return $this
     */
    public function setAuth($key)
    {
        $this->baseOptions['auth'] = $key;

        return $this;
    }

    /**
     * set multipart array to the base option
     *
     * @param $multipartArr
     * @return $this
     */
    public function setMultipart($multipartArr)
    {
        $this->baseOptions['multipart'] = $multipartArr;

        return $this;
    }

    /**
     * set the required timeout for api request
     *
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->baseOptions['timeout'] = $timeout;

        return $this;
    }

    /**
     * set the required connect timeout for api request
     *
     * @param $timeout
     * @return $this
     */
    public function setConnectTimeout($timeout)
    {
        $this->baseOptions['connect_timeout'] = $timeout;

        return $this;
    }

    /**
     * set the default base options
     */
    protected function defaultBaseOptions()
    {
        $this->baseOptions = [
            'body'            => $this->params,
            'allow_redirects' => false,
            'timeout'         => 20,
            'connect_timeout' => 10,
            'http_errors'     => true
        ];
    }

    /**
     * generic method for client request
     *
     * @param $method
     * @return mixed
     * @throws \Exception
     */
    private function clientRequest($method): mixed
    {
        $client = new Client();

        try {
            $this->response = $client->$method($this->url, $this->baseOptions);
        } catch (ConnectException|RequestException $e) {
            throw new \Exception($e->getMessage());
        }

        $this->response = $this->response->getBody();
    }
}
