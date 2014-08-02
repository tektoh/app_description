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
        if (!isset($queryData['conditions'])) {
            return [];
        }
        
        if (!isset($queryData['conditions']['url'])) {
            return [];
        }
        
        $url = $queryData['conditions']['url'];
        
        foreach ($this->config as $config) {
            if (!isset($config['host'])) {
                continue;
            }
            if (strpos($url, $config['host']) != false) {
                $api = new $config['apiClass'];
                break;
            }
        }
        if (!isset($api)) {
            throw new CakeException("There is no class that corresponds to {$url}");
        }
        return [$api->lookup($url)];
    }
}