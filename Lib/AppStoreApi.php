<?php

App::uses('AppDescriptionApi', 'AppDescription.Lib');

class AppStoreApi extends AppDescriptionApi
{
    public function lookup($url)
    {
        // TODO AppStoreのURLから情報を取ってくる処理
        return ['app store'];
    }
}