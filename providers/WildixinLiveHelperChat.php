<?php

namespace LiveHelperChatExtension\wildixin\providers {

    #[\AllowDynamicProperties]
    class WildixinLiveHelperChat {

        public static function getInstance() {

            if (self::$instance !== null){
                return self::$instance;
            }

            self::$instance = new self();

            return self::$instance;
        }

        public function __construct() {
            $wildixin = \erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionWildixin');
            $this->endpoint = $wildixin->settings['endpoint'];
            $this->user = $wildixin->settings['user'];
            $this->password = $wildixin->settings['password'];
        }

        public function deleteContact($id) {
            $this->getRestAPI([
                'method' => 'api/v1/Contacts/' . $id,
                'method_type' => 'delete'
            ]);
        }

        public function block($item)
        {
            $params = ['data' => [
                'name' => 'LHC Block',
                'mobile' => '+' . $item->phone,
                'phonebook_id' => $item->phonebook,
                'type' => 'blacklist',
                'document_from' => $item->document_from
            ]];

           $response = $this->getRestAPI([
                'method' => 'api/v1/Contacts/',
                'args' => $params,
                'method_post' => true
            ]);

           if (!isset($response['result']['id'])) {
               throw new \Exception('Contact could not be created!');
           }
        }

        public function phoneBooks($params = array(), $page = 0) {
            $response = $this->getRestAPI([
                'method' => 'api/v1/Phonebooks/',
                'args' => []
            ]);

            return [
                'total' => $response['result']['total'],
                'items' => $response['result']['records'],
            ];
        }

        public function searchContact($params, $page = 0) {
            $start = 0;

            if ($page > 1) {
                $start = ($page - 1)*20;
            }

            $args = [
                'count' => 20,
                'start' => $start
            ];

            if (isset($params['filter']['keyword'])) {
                $args['search'] = $params['filter']['keyword'];
            }

            $response = $this->getRestAPI([
                'method' => 'api/v1/Contacts/',
                'args' => $args
            ]);

            return [
                'total' => $response['result']['total'],
                'items' => $response['result']['records'],
            ];
        }

        public function getRestAPI($params)
        {
            $try = isset($params['try']) ? $params['try'] : 3;

            for ($i = 0; $i < $try; $i++) {

                $ch = curl_init();
                $url = rtrim($this->endpoint, '/') . '/' . $params['method'] . (isset($params['args']) ? '?' . http_build_query($params['args']) : '');

                if (!isset(self::$lastCallDebug['request_url'])) {
                    self::$lastCallDebug['request_url'] = array();
                }

                if (!isset(self::$lastCallDebug['request_url_response'])) {
                    self::$lastCallDebug['request_url_response'] = array();
                }

                self::$lastCallDebug['request_url'][] = $url;

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, self::$apiTimeout);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                if (isset($params['method_type']) && $params['method_type'] == 'delete') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                }

                if (isset($params['method_post']) && $params['method_post'] == true) {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                }

                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, $this->user . ':' . $this->password);

                $headers = array(
                    'Accept: application/json',
                );

                if (isset($params['body_json']) && !empty($params['body_json'])) {
                    curl_setopt($ch, CURLOPT_POST,1 );
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params['body_json']);
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Expect:';
                }

                if (isset($params['bearer']) && !empty($params['bearer'])) {
                    $headers[] = 'Authorization: Bearer ' . $params['bearer'];
                }

                if (isset($params['headers']) && !empty($params['headers'])) {
                    $headers = array_merge($headers, $params['headers']);
                }

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                $startTime = date('H:i:s');
                $additionalError = ' ';

                if (isset($params['test_mode']) && $params['test_mode'] == true) {
                    $content = $params['test_content'];
                    $httpcode = 200;
                } else {
                    $content = curl_exec($ch);

                    if (curl_errno($ch))
                    {
                        $additionalError = ' [ERR: '. curl_error($ch).'] ';
                    }

                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                }

                $endTime = date('H:i:s');

                if (isset($params['log_response']) && $params['log_response'] == true) {
                    self::$lastCallDebug['request_url_response'][] = '[T' . self::$apiTimeout . '] ['.$httpcode.']'.$additionalError.'['.$startTime . ' ... ' . $endTime.'] - ' . ((isset($params['body_json']) && !empty($params['body_json'])) ? $params['body_json'] : '') . ':' . $content;
                }

                if ($httpcode == 204) {
                    return array();
                }

                if ($httpcode == 404) {
                    throw new \Exception('Resource could not be found!');
                }

                if (isset($params['return_200']) && $params['return_200'] == true && $httpcode == 200) {
                    return $content;
                }

                if ($httpcode == 401) {
                    throw new \Exception('No permission to access resource!');
                }

                if ($content !== false)
                {
                    if (isset($params['raw_response']) && $params['raw_response'] == true){
                        return $content;
                    }

                    $response = json_decode($content,true);
                    if ($response === null) {
                        if ($i == 2) {
                            throw new \Exception('Invalid response was returned. Expected JSON');
                        }
                    } else {
                        if ($httpcode != 500) {
                            return $response;
                        }
                    }

                } else {
                    if ($i == 2) {
                        throw new \Exception('Invalid response was returned');
                    }
                }

                if ($httpcode == 500 && $i >= 2) {
                    throw new \Exception('Invalid response was returned');
                }

                usleep(300);
            }
        }

        private $endpoint = null;
        private $access_key = null;
        private $channel_id = null;

        private static $instance = null;
        public static $lastCallDebug = array();
        public static $apiTimeout = 40;
    }
}