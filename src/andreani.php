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

    const API_V1 = 1;
    const API_V2 = 2;

    private $version = '0.4.0';

    private $debug = true;
    private $http = null;
    private $user = null;
    private $password = null;
    private $cliente = null;
    private $response = null;
    private $token = null;

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
        $this->token = null;

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
        if (!is_null($this->token)) {
            return $this->token;
        }

        $response = $this->login();

        if ($response->code == 200) {
            $token = $response->headers['X-Authorization-token'];

            return $token;
        }

        return false;
    }

    public function getSucursales($version = self::API_V2)
    {
        $endpoint = $version == self::API_V1 ? '/v1' : '/v2';
        $uri = $this->getBaseUrl($endpoint . '/sucursales');

        return $this->makeRequest($uri, 'get');
    }

    /**
     * Devuelve las sucursales recomendadas por Andreani según el Código Postal.
     *
     * @param int $codigoPostal
     * @param string $canal 'B2C' o 'B2B'
     *
     * @return array
     */
    public function getSucursalByCodigoPostal($codigoPostal, $canal = 'B2C')
    {
        $uri = $this->getBaseUrl('/v2/sucursales');
        
        $canales = ['B2C', 'B2B'];

        $params = [
            'codigoPostal' => $codigoPostal,
            'canal' => in_array($canal, $canales) ? $canal : 'B2C',
        ];

        $uri .= '?' . http_build_query($params);

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
    public function getSucursalByCodigoPostalLegacy($codigoPostal)
    {
        trigger_error('Esta funcion será deprecada por usar la versión vieja de Andreani (SOAP). Usar getSucursalByCoditoPostal($coditoPostal)', E_USER_NOTICE);

        $request = new ConsultarSucursales();
        $request->setCodigoPostal($codigoPostal);

        $andreani = new AndreaniLegacy($this->user, $this->password, $this->debug ? 'test' : 'prod');
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

    /**
     * Devuelve la latitud y longitud de una dirección.
     * Sirve para geolocalizar ubicaciones en un mapa por ejemplo.
     */
    public function getDirecciones($provincia, $localidad, $codigoPostal, $calle, $numero, $piso = '', $dpto = '')
    {
        $uri = $this->getBaseUrl('/v1/direcciones');

        $params = [
            'provincia' => $provincia,
            'localidad' => $localidad,
            'codigopostal' => $codigoPostal,
            'calle' => $calle,
            'numero' => $numero,
            'piso' => $piso,
            'dpto' => $dpto,
            'geolocalizar' => true
        ];

        $uri .= '?' . http_build_query($params);

        return $this->makeRequest($uri, 'get');
    }

    public function getRegiones()
    {
        return $this->getProvincias();
    }

    /**
     * @since 0.2.2 $apiVersion no se usa más, ahora se usa siempre la v2.
     *
     * Crea una nueva orden
     * Una órden de envío es un pedido de envío que se le hace a Andreani.
     * De esta forma Andreani puede planificar la entrega sin tener la carga todavía en su poder.
     *
     * @param array $data
     * @param int   $apiVersion @deprecated usar addOrden($data).
     *
     * @return void
     */
    public function addOrden($data, $apiVersion = self::API_V1)
    {
        $uri = $this->getBaseUrl('/v2/ordenes-de-envio');

        return $this->makeRequest($uri, 'post', $data);
    }

    public function removeOrden($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v1/ordenesDeEnvio/'.$numeroAndreani);

        return $this->makeRequest($uri, 'delete');
    }

    public function getOrden($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v2/ordenes-de-envio/'.$numeroAndreani);

        return $this->makeRequest($uri, 'get');
    }

    public function getEtiqueta($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v2/ordenes-de-envio/'.$numeroAndreani.'/etiquetas');

        $this->makeRequest($uri, 'get');
        
        $response = $this->getResponse();

        if ($response->code == 200) {
            return (object)array(
                'pdf' => $response->body
            );
        }
        
        return json_decode($response->body);
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

    /**
     * Devuelve la tarifa de un envio a partir de parametros como el destino, el peso, el volumen, el valor declarado del producto.
     *
     * $bultos es un array con el siguiente formato:
     *      bultos[0][largoCm] 	double 	Largo del bulto en cm. Opcional. Sirve para calcular el volumen.
     *      bultos[0][anchoCm] 	double 	Ancho del bulto en cm. Opcional. Sirve para calcular el volumen.
     *      bultos[0][altoCm] 	double 	Alto del bulto en cm. Opcional. Sirve para calcular el volumen.
     *      bultos[0][volumen] 	double 	Volumen del bulto en cm3. Es obligatorio ingresar volumen, peso o peso aforado.
     *      bultos[0][kilos] 	double 	Peso del bulto en kilos. Es obligatorio ingresar volumen, peso o peso aforado.
     *      bultos[0][pesoAforado] 	double 	Peso aforado del bulto en kilos. Es obligatorio ingresar volumen, peso o peso aforado.
     *      bultos[0][valorDeclarado] 	integer 	Valor declarado del contenido del bulto. Sirve para el seguro. Obligatorio.
     *      bultos[0][categoria] 	string 	Categoria del bulto. Solo aplica a ciertos contratos. Invalida a los otros campos del bulto. Opcional.
     *
     * @param int    $cpDestino      Codigo postal del destino del envio
     * @param string $contrato       Numero de contrato con Andreani
     * @param array  $bultos         informacion de cada bulto a enviar
     * @param string $cliente        Numero de cliente con Andreani. Si no se pasa como parametro se toma el del constructor.
     * @param string $sucursalOrigen Codigo de sucursal donde se impone el envio. En caso de no ingresarla, se toma la configurada por contrato.
     * @param string $pais           pais destino del envío
     *
     * @return void
     */
    public function cotizarEnvio($cpDestino, $contrato, $bultos, $cliente = null, $sucursalOrigen = null, $pais = null)
    {
        $cliente = is_null($cliente) ? $this->cliente : $cliente;

        $params = array(
            'cpDestino' => $cpDestino,
            'contrato' => $contrato,
            'bultos' => $bultos,
            'cliente' => $cliente,
        );

        if (!is_null($sucursalOrigen)) {
            $params['sucursalOrigen'] = $sucursalOrigen;
        }

        if (!is_null($pais)) {
            $params['pais'] = $pais;
        }

        $uri = $this->getBaseUrl('/v1/tarifas').'?'.http_build_query($params);

        return $this->makeRequest($uri, 'get');
    }

    protected function makeRequest($uri, $method = 'get', $data = null)
    {
        if (!is_null($data) && !is_string($data)) {
            $data = json_encode($data);
        }

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

        // Si es una petición satisfactoria devuelvo el body.
        if ($response->code >= 200 && $response->code <= 299) {
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
