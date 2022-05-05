<?php

namespace AlejoASotelo;

use Joomla\Http\Transport\Curl as curlTransport;
use Joomla\Http\Http;
use Andreani\Andreani as AndreaniLegacy;
use Andreani\Requests\ConsultarSucursales;

class Andreani
{
    const BASE_URL_DEV = 'https://apisqa.andreani.com';
    const BASE_URL_PROD = 'https://apis.andreani.com';
    
    const API_V1 = 1;
    const API_V2 = 2;

    const ETIQUETA_ESTANDAR = '';
    const ETIQUETA_DOCUMENTO_DE_CAMBIO = 'documentoDeCambio';

    private $version = '0.8.2';

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

    public function getVersion() {
        return $this->version;
    }

    private function getBaseUrl($endpoint = 'login')
    {
        $base = $this->debug ? self::BASE_URL_DEV : self::BASE_URL_PROD;

        return $base . $endpoint;
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
            $token = isset($response->headers['x-authorization-token']) ? $response->headers['x-authorization-token'] : $response->headers['X-Authorization-token'];
            return $token;
        }

        return false;
    }

    /**
     * Devuelve todas las sucursales usando la api v2 por defecto.
     * 
     * @since 0.6.1 Se cambió la firma de getSucursales($version) a getSucursales($params, $version)
     *
     * @param array $params
     * @param int $version
     * @return object
     */
    public function getSucursales($params = null, $version = self::API_V2)
    {
        $endpoint = $version == self::API_V1 ? '/v1' : '/v2';
        $uri = $this->getBaseUrl($endpoint . '/sucursales');

        if (!is_null($params)) {
            $uri .= '?' . http_build_query($params);
        }

        return $this->makeRequest($uri, 'get');
    }

    /**
     * Devuelve las sucursales recomendadas por Andreani según el Código Postal.
     * Es una shortcut de getSucursales
     *
     * @param int $codigoPostal
     * @param string $canal 'B2C' o 'B2B'
     *
     * @return array
     */
    public function getSucursalByCodigoPostal($codigoPostal, $canal = 'B2C')
    {
        $canales = ['B2C', 'B2B'];

        $params = [
            'codigoPostal' => $codigoPostal,
            'canal' => in_array($canal, $canales) ? $canal : 'B2C',
        ];

        return $this->getSucursales($params, self::API_V2);
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
     * @return object
     */
    public function addOrden($data, $apiVersion = self::API_V1)
    {
        $uri = $this->getBaseUrl('/v2/ordenes-de-envio');

        return $this->makeRequest($uri, 'post', $data);
    }

    /**
     *
     * Devuelve una orden de envío
     * Una órden de envío es un pedido de envío que se le hace a Andreani.
     * De esta forma Andreani puede planificar la entrega sin tener la carga todavía en su poder.
     *
     * @param string $numeroAndreani
     *
     * @return object
     */
    public function getOrden($numeroAndreani)
    {
        $uri = $this->getBaseUrl('/v2/ordenes-de-envio/'.$numeroAndreani);

        return $this->makeRequest($uri, 'get');
    }

    /**
     * Devuelve una etiqueta pdf listo para guardar en un archivo.
     * 
     * @since 0.6.0 Se agregó el campo $tipo para poder imprimir otro tipo de etiquetas.
     *
     * @param string $numeroAndreani
     * @param string $tipo Andreani::ETIQUETA_ESTANDAR o Andreani::ETIQUETA_DOCUMENTO_DE_CAMBIO
     * @return object
     */
    public function getEtiqueta($numeroAndreani, $tipo = self::ETIQUETA_ESTANDAR)
    {
        $uri = $this->getBaseUrl('/v2/ordenes-de-envio/'.$numeroAndreani.'/etiquetas');

        if (!empty($tipo)) {
            $uri .= '?tipo=' . $tipo;
        }

        $this->makeRequest($uri, 'get');
        
        $response = $this->getResponse();

        if ($response->code == 200) {
            return (object)array(
                'pdf' => $response->body
            );
        }
        
        return json_decode($response->body);
    }

    /**
     * Devuelve un envio.
     * Se utiliza la api v1 por defecto por compatibilidad, pero se recomienda utilizar la v2.
     * 
     * @since 0.8.2 Se agregó el parámetro $apiVersion para poder utilizar v1 o v2.
     *
     * @param string $numeroAndreani
     * @param string $apiVersion 
     * @return object
     */
    public function getEnvio($numeroAndreani, $apiVersion = self::API_V1)
    {
        $endpoint = $apiVersion == self::API_V1 ? '/v1' : '/v2';
        $uri = $this->getBaseUrl($endpoint . '/envios/'.$numeroAndreani);

        return $this->makeRequest($uri, 'get');
    }

    /**
     * Busca envios según los parámetros indicados.
     *
     * @param string $codigoCliente
     * @param string $idProducto
     * @param string $dniDestinatario
     * @param datetime $fechaCreacionDesde Ej: 2019-07-10T14:00:55
     * @param datetime $fechaCreacionHasta Ej: 2019-07-10T14:00:55
     * @param int $apiVersion
     * @return object
     */
    public function searchEnvio($codigoCliente = null, $idProducto = null, $dniDestinatario = null, $fechaCreacionDesde = null, $fechaCreacionHasta = null, $apiVersion = self::API_V1)
    {
        $codigoCliente = is_null($codigoCliente) ? $this->cliente : $codigoCliente;

        $params = array(
            'codigoCliente' => $codigoCliente,
            'idDeProducto' => $idProducto,
            'numeroDeDocumentoDestinatario' => $dniDestinatario,
            'fechaCreacionDesde' => $fechaCreacionDesde,
            'fechaCreacionHasta' => $fechaCreacionHasta,
        );

        $endpoint = $apiVersion == self::API_V1 ? '/v1' : '/v2';
        $uri = $this->getBaseUrl($endpoint . '/envios').'?'.http_build_query($params);

        return $this->makeRequest($uri, 'get');
    }

    /**
     * Devuelve las trazas de un envío. 
     * 
     * En la v2 no devuelve el estado "entregado".
     *
     * @param string $numeroAndreani
     * @param int $apiVersion
     * @return object
     */
    public function getTrazabilidad($numeroAndreani, $apiVersion = self::API_V1)
    {
        $endpoint = $apiVersion == self::API_V1 ? '/v1' : '/v2';
        $uri = $this->getBaseUrl($endpoint . '/envios/'.$numeroAndreani.'/trazas');

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

    public function getCodigoQR($informacion)
    {        
        $uri = $this->getBaseUrl('/v1/codigos-qr/') . urlencode($informacion);
        
        return $this->makeRequest($uri, 'get', null, false);
    }

    protected function makeRequest($uri, $method = 'get', $data = null, $decodeBody = true)
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
            return $decodeBody ? json_decode($response->body) : $response->body;
        } else {
            return null;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
