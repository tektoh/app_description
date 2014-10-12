<?php
App::uses('HttpSocket', 'Network/Http');
App::uses('AppDescriptionApi', 'AppDescription.Lib');

class AppStoreApi extends AppDescriptionApi
{
	public $http;

	public function __construct()
	{
		$this->http = new HttpSocket();
	}

	public function lookup($url)
	{
		$_url = parse_url($url);

		if ($_url == false) {
			CakeLog::error("AppStoreApi::lookup error: url={$url}");
			return false;
		}

		$path = explode('/', $_url['path']);

		foreach ($path as $p) {
			if (preg_match('/id([0-9]+)/', $p, $m)) {
				$id = $m[1];
				break;
			}
		}

		$res = $this->http->get('https://itunes.apple.com/jp/lookup', compact('id'));

		if (!$res->isOk()) {
			CakeLog::error("AppStoreApi::lookup error: url={$url} code={$res->code}");
			return false;
		}

		$result = json_decode($res->body, true);

		return $result['results'][0];
	}
}
