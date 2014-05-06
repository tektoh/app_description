<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('AppDescriptionApi', 'AppDescription.Lib');
App::import('Vendor', 'AppDescription.simplehtmldom/simple_html_dom');

class GooglePlayApi extends AppDescriptionApi
{
    public function lookup($url)
    {
        $HttpSocket = new HttpSocket();
		$results = $HttpSocket->get($url);
		
		if (!$results->isOk()) {
			$this->log("GooglePlayApi::lookup: Error: {$url} {$results->reasonPhrase} {$results->body}");
			return false;
		}
		
		$results = $this->parseHtml($results->body);
		
		debug($results);
        
        return $results;
    }
	
	/**
	 * HTMLをパースします
	 * 
	 * @params string $text
	 * @return array
	 */
	public function parseHtml($text)
	{
		$html = str_get_html($text);
		$results = [];
		$results['screenshotUrls'] = $this->getScreenshotUrls($html);
		
		return $results;
	}
	
	protected function getScreenshotUrls($html)
	{
		$results = [];
		foreach($html->find('img.full-screenshot') as $element) {
			$results []= $element->src;
		}
		return $results;
	}
}