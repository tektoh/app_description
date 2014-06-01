<?php

App::uses('DataSource', 'Model/Datasource');
App::uses('GooglePlayApi', 'AppDescription.Lib');
App::uses('AppStoreApi', 'AppDescription.Lib');

class AppDescriptionSource extends DataSource
{
    public $config = [
        'googleplay' => [
            'host' => 'play.google.com',
            'apiClass' => 'GooglePlayApi'
        ],
        'appstore' => [
            'host' => 'itunes.apple.com',
            'apiClass' => 'AppStoreApi'
        ]
    ];
    
    public function read(Model $model, $queryData = [], $recursive = null)
    {
        $url = $queryData[0];
        
        foreach ($this->config as $config) {
            if (strpos($url, $config['host']) != false) {
                $api = new $config['apiClass'];
                break;
            }
        }
        if (!isset($api)) {
            return false;
        }
        return $api->lookup($url);
    }
}