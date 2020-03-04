<?php

namespace AlejoASotelo;

use Joomla\Http\Transport\Curl as curlTransport;
use Joomla\Http\Http;
use Andreani\Andreani as AndreaniLegacy;
use Andreani\Requests\ConsultarSucursales;

class Andreani
{
    const BASE_URL_DEV = 'https://api.qa.andreani.com';
    const BASE_URL_PROD = 'https://api.andreani.com';

    private $version = '0.0.1';

    private $debug = true;
    private $http = null;
    private $user = null;
    private $password = null;
    private $cliente = null;
    private $response = null;

    public function __construct($user, $password, $cliente, $debug = true)
    {
        if (empty($user) || empty($password)) {
            throw new Exception('Faltan las credenciales');
        }

        $this->user = $user;
        $this->password = $password;
        $this->cliente = $cliente;
        $this->debug = $debug;
        $this->response = null;

        $options = array(
            'curl.certpath' => __DIR__.'/vendor/joomla/http/src/Transport/cacert.pem',
            'transport.curl' => array(
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ),
        );

        $transport = new CurlTransport($options);

        // Create a 'stream' transport.
        $this->http = new Http($options, $transport);
    }

    private function getBaseUrl($endpoint = 'login')
    {
        $base = $this->debug ? self::BASE_URL_DEV : self::BASE_URL_PROD;

        return $base.$endpoint;
    }

    public function login($user = null, $password = null)
    {
        $user = !is_null($user) ? $user : $this->user;
        $password = !is_null($password) ? $password : $this->password;

        $hash = $user.':'.$password;
        // Set up custom headers for a single request.
        $headers = array(
            'Authorization' => 'Basic '.base64_encode($hash),
            'Content-Type' => 'application/json',
        );

        // In this case, the Accept header in $headers will override the options header.
        $uri = $this->getBaseUrl('/login');

        return $this->http->get($uri, $headers);
    }

    public function getToken()
    {
        $response = $this->login();

        if ($response->code == 200) {
            $token = $response->headers['X-Authorization-token'];

            return $token;
        }

        return false;
    }

    public function getSucursales()
    {
        $uri = $this->getBaseUrl('/v1/sucursales');

        return $this->makeRequest($uri, 'get');
    }

    /**
     * Devuelve las sucursales recomendadas por Andreani
     * según el Código Postal. Utiliza el webservice viejo (SOAP).
     *
     * @param int $codigoPostal
     *
     * @return array
     */
    public function getSucursalByCodigoPostal($codigoPostal)
    {
        $request = new ConsultarSucursales();
        $request->setCodigoPostal($codigoPostal);

        $andreani = new AndreaniLegacy($this->user, $this->password, $this->debug ? 'dev' : 'test');
        $response = $andreani->call($request);

        if ($response->isValid()) {
            $result = $response->getMessage()->ConsultarSucursalesResult;

            return $result;
        } else {
            return null;
        }
    }

    public function getProvincias()
    {
        $uri = $this->getBaseUrl('/v1/regiones');

        return $this->makeRequest($uri, 'get');
    }

    public function getRegiones()
    {
        return $this->getProvincias();
    }

    public function addOrden($data)
    {
        $uri = $this->getBaseUrl('/v1/ordenesDeEnvio');

        return $this->makeRequest($uri, 'post', $data);
    }

    public function removeOrden($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v1/ordenesDeEnvio/'.$numeroAndreani);

        return $this->makeRequest($uri, 'delete');
    }

    public function getOrden($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v1/ordenesDeEnvio/'.$numeroAndreani);

        return $this->makeRequest($uri, 'get');
    }

    public function getEtiqueta($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v1/etiquetas/'.$numeroAndreani);

        return $this->makeRequest($uri, 'get');
    }

    public function getEnvio($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v1/envios/'.$numeroAndreani);

        return $this->makeRequest($uri, 'get');
    }

    public function searchEnvio($codigoCliente = null, $idProducto = null, $dniDestinatario = null, $fechaCreacionDesde = null, $fechaCreacionHasta = null)
    {
        $codigoCliente = is_null($codigoCliente) ? $this->cliente : $codigoCliente;

        $params = array(
            'codigoCliente' => $codigoCliente,
            'idDeProducto' => $idProducto,
            'numeroDeDocumentoDestinatario' => $dniDestinatario,
            'fechaCreacionDesde' => $fechaCreacionDesde,
            'fechaCreacionHasta' => $fechaCreacionHasta,
        );

        $uri = $this->getBaseUrl('/v1/envios').'?'.http_build_query($params);

        return $this->makeRequest($uri, 'get');
    }

    public function getTrazabilidad($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v1/envios/'.$numeroAndreani.'/trazas');

        return $this->makeRequest($uri, 'get');
    }

    protected function makeRequest($uri, $method = 'get', $data = null)
    {
        $availableMethods = array(
            'get',
            'post',
            'request',
            'delete',
            'put',
        );

        $method = strtolower($method);

        if (!in_array($method, $availableMethods)) {
            $method = 'get';
        }

        $headers = array(
            'Content-Type' => 'application/json',
            'x-authorization-token' => $this->getToken(),
        );

        if ($method == 'post') {
            $response = $this->http->{$method}($uri, $data, $headers);
        } else {
            $response = $this->http->{$method}($uri, $headers);
        }

        $this->response = $response;

        if ($response->code == 200) {
            return json_decode($response->body);
        } else {
            return null;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
