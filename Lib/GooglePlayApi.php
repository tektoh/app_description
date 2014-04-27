<?php

App::uses('AppDescriptionApi', 'AppDescription.Lib');

include(__DIR__."/../Vendor/android-market-api-php/proto/protocolbuffers.inc.php");
include(__DIR__."/../Vendor/android-market-api-php/proto/market.proto.php");
include(__DIR__."/../Vendor/android-market-api-php/Market/MarketSession.php");

class GooglePlayApi extends AppDescriptionApi
{
    public function lookup($url)
    {
        // TODO GooglePlayのURLから情報を取ってくる処理
        return ['googleplay'];
    }
}