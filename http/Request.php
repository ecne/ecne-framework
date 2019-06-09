<?php
/**
 *  @class Request
 */

namespace Ecne\Http;

class Request
{
    protected $method;
    protected $uri;
    protected $httpVersion;
    protected $httpHeaders;
    protected $clientIP;
    protected $queryString = null;

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->uri = strtolower($_SERVER['REQUEST_URI']);
        $this->httpVersion = strtolower($_SERVER['SERVER_PROTOCOL']);
        $this->httpHeaders = getallheaders();
        $this->clientIP = $_SERVER['REMOTE_ADDR'];
        $this->queryString =  $_SERVER['QUERY_STRING'];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getURI()
    {
        return $this->uri;
    }

    public function setURI($uri)
    {
        $this->uri = $uri;
    }

    public function getHttpVersion()
    {
        return $this->httpVersion;
    }

    public function setHttpVersion($httpVersion)
    {
        $this->httpVersion = $httpVersion;
    }

    /**
     *  @return Array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     *  @var Array $httpHeaders
     */
    public function setHttpHeaders($httpHeaders)
    {
        $this->httpHeaders = $httpHeaders;
    }

    public function getClientIP()
    {
        return $this->clientIP;
    }

    public function setClientIP($clientIP)
    {
        $this->clientIP = $clientIP;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;
    }
}
