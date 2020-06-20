<?php
namespace App\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Constants\Mode;
use AppBundle\Constants\WebConstant;

class CoreHTTPService
{
    private $url;
    private $cookie;
    private $session;

    
    public function __construct($url = 'https://6voho9b8s6.execute-api.us-east-1.amazonaws.com/dev', RequestStack $request)
    {
        $this->url = $url;
        $this->cookie = $request->getCurrentRequest()
                            ->cookies->get(WebConstant::API_COOKIE);
        $this->session = $request->getCurrentRequest()
                            ->getSession();
    }

    /**
     * setURL
     *
     * @param string $url
     */
    public function setURL($url)
    {
        $this->url = $url;
    }

    /**
     * setCookie
     *
     * @param string $cookie
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * setSession
     *
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }


    /**
     * GET
     *
     * @param string $path
     * @param array $params
     * @param array $options
     *
     * @return string
     */
    public function get($path, $params = array(), $options = array())
    {
        // URL

        if (count($params) > 0)
        {
            $path = "$path?" . http_build_query($params);
        }


        // initialise

        $defaults = array(
            CURLOPT_HTTPHEADER      => array(
                'accept: application/json',
                'cache-control: no-cache',
            ),
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_TIMEOUT         => 15,
            CURLOPT_URL             => $this->url.'/'.$path,
        );

        return ($this->curl(($defaults + $options)));
    }


    /**
     * POST
     *
     * @param string $path
     * @param array $params
     * @param array $options
     *
     * @return string
     */
    public function post($path, $params = array(), $options = array())
    {
        // initialise

        $defaults = array(
            CURLOPT_FRESH_CONNECT   => 1,
            CURLOPT_HTTPHEADER      => array(
                'accept: application/json',
                'cache-control: no-cache',
            ),
            CURLOPT_HEADER          => 1,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => http_build_query($params),
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_TIMEOUT         => 10,
            CURLOPT_URL             => $this->url.'/'.$path,
        );

        return ($this->curl(($defaults + $options)));
    }

/**
 * cURL query
 *
 * @param array $options
 */
private function curl($options = array())
{
    // response

    $data = array(
        'body'                  => '',
        'http_code'             => '',
        'error'                 => '',
        'twofa'                 => '',
        'cookies'               => array(),
    );

    // authorization

    // $options[CURLOPT_COOKIE] = WebConstant::API_COOKIE.'='.$this->cookie.'; Path=/; HttpOnly';

    // request

    $ch     = curl_init();
    curl_setopt_array($ch, $options);

    $body   = curl_exec($ch);
    $err    = curl_errno($ch);

    // cookies

    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $body, $matches);

    $cookies = array();

    foreach($matches[1] as $item)
    {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }

    // 2FA

    $jsonStr = array();
    $bodyArr = array();

    preg_match('/\{.*?\}/', $body, $jsonStr);

    if (array_key_exists(0, $jsonStr))
    {
        $bodyArr = json_decode($jsonStr[0], true);
    }


    // curl body

    if ($err)
    {
        $data['error']          = $err;
    }
    else
    {
        // JSON decode

        $temp = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE)
        {
            $body = $temp;
        }

        $data['body']           = $body;
        $data['http_code']      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $data['cookies']        = $cookies;

        if (is_array($bodyArr) && array_key_exists('sent', $bodyArr))
        {
            $data['twofa']      = $bodyArr['sent'];
        }


        // store http_code in session so we can force logout user for a non 200 code
        // store twofa for handling post log in verification

        $this->session->set(WebConstant::SESSION_HTTP, $data['http_code']);
        $this->session->set(WebConstant::SESSION_TWOFA, $data['twofa']);

        // store route in session

        $route = str_replace($this->url, '', $options[CURLOPT_URL]);

        $this->session->set(WebConstant::SESSION_LAST_ROUTE, $route);
    }

    curl_close($ch);

    return ($data);
    }
}