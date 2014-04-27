<?php

App::uses('DataSource', 'Model/Datasource');
App::uses('GooglePlayApi', 'AppDescription.Lib');
App::uses('AppStoreApi', 'AppDescription.Lib');

class AppDescriptionSource extends DataSource
{
    public function read(Model $model, $queryData = [], $recursive = null)
    {
        $url = $queryData[0];
        $vendor = $this->_getVendor($url);

        if ($vendor === 'googleplay') {
            $api = new GooglePlayApi();
        } else if ($vendor === 'appstore') {
            $api = new AppStoreApi();
        } else {
            return false;
        }

        return $api->lookup($url);
    }

    protected function _getVendor($url)
    {
        // TODO URLからストアを判定する処理
        //return 'googleplay';
        return 'appstore';
    }
}