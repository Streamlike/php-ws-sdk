<?php
/**
 * Streamlike webservices V2 SDK.
 *
 * @author     Antony Simonneau <support@mediatech.fr>
 * @copyright  Copyright (c) 2012,
 *
 * @see http://www.mediatech.fr Mediatech
 */
class streamlikeWs
{
    const RESULTTYPE_RAW = 1; // Raw result: xml or json
    const RESULTTYPE_ARRAY = 2; // Array result: xml or json serialized object

    const VERSION_V2 = 1;

    private $server;
    private $ws_url;
    private $version;
    private $format;
    private $allowed_services;

    /**
     * @param $server
     * @param string $format
     * @param int    $version
     *
     * @throws Exception
     */
    public function __construct($server, $format = 'json', $version = self::VERSION_V2)
    {
        if (!$server) {
            throw new InvalidArgumentException('A server url is required !');
        }

        if ('xml' !== $format && 'json' !== $format) {
            throw new InvalidArgumentException('This format is not allowed !');
        }

        $this->server = $server;
        $this->format = $format;
        $this->version = $version;

        $this->ws_url = $server.'/ws/';

        $this->allowed_services = [
            'playlists',
            'playlist',
            'media',
            'related',
            'rss',
            'qr',
            'languages',
            'countries',
            'getStreamlikeVersion',
            'manifest',
            'nowplaying',
            'podcast',
            'resume',
            'videositemap',
        ];
    }

    /**
     * Return content in the desired format
     * - raw xml
     * - raw json
     * - serialized xml or json.
     *
     * @param string $service     the name of the service called (media, playlists, tag etc.)
     * @param array  $params      key/values array
     * @param int    $result_type self::RESULTTYPE_RAW or self::RESULTTYPE_ARRAY
     *
     * @return string|object
     *
     * @throws Exception
     */
    public function getResult($service, $params, $result_type = self::RESULTTYPE_RAW)
    {
        // Construct ws url to call
        $url = $this->getQuery($service, $params);

        // Get url content
        $fh = fopen($url, 'r');
        $content = stream_get_contents($fh);
        if (!$content) {
            throw new Exception('URL cannot be accessed !');
        }
        fclose($fh);

        // Return appropriate content
        switch ($result_type) {
            case self::RESULTTYPE_RAW:
                return $content;
            case self::RESULTTYPE_ARRAY:
                switch ($this->format) {
                    case 'xml':
                        return simplexml_load_string($content);
                    case 'json':
                        return json_decode($content, true);
                }
        }

        return false; // Must not get there
    }

    /**
     * Return url encoded parameters chain.
     *
     * @param array $params
     *
     * @return string url ready parameters chain string
     */
    private function getParamValuesChain($params)
    {
        if (empty($params)) {
            $params = [];
        }

        $params['f'] = $this->format;

        return http_build_query($params);
    }

    /**
     * Vote service.
     *
     * @param array $params
     *
     * @return string
     *
     * @throws Exception
     */
    public function setVote($params)
    {
        if (!is_array($params)) {
            throw new Exception('Required params for vote. Check documentation');
        }

        $params['rand'] = mt_rand();

        // Construct ws url to call
        $url = $this->ws_url.'vote?'.$this->getParamValuesChain($params);

        // Get url content
        $res = file($url);

        return $res;
    }

    /**
     * @param $service
     * @param $params
     *
     * @return string
     *
     * @throws Exception
     */
    public function getQuery($service, $params)
    {
        // Check service
        if (!in_array($service, $this->allowed_services)) {
            throw new Exception('This service is not available !');
        }
        // Construct ws url to call
        return $this->ws_url.$service.'?'.$this->getParamValuesChain($params);
    }
}
