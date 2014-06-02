<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('AppDescriptionApi', 'AppDescription.Lib');
App::import('Vendor', 'AppDescription.simplehtmldom/simple_html_dom');

class GooglePlayApi extends AppDescriptionApi
{
    public $http;
    
    public function __construct()
    {
        $this->http = new HttpSocket();
    }
    
    public function lookup($url)
    {
		$results = $this->http->get($url);
		
		if (!$results->isOk()) {
			CakeLog::error("GooglePlayApi::lookup: Error: {$url} {$results->reasonPhrase} {$results->body}");
			return false;
		}
		
		$results = $this->parseHtml($results->body);
        
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
        $results['trackName'] = trim($this->getTrackName($html));
        $results['description'] = $this->getDescription($html);
        $results['artworkUrl512'] = $this->getArtworkUrl($html, 512);

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
    
    protected function getTrackName($html)
    {
        $e = $html->find('.document-title');
        
        if (!isset($e[0])) {
            return '';
        }
        
        return $e[0]->plaintext;
    }
    
    protected function getDescription($html)
    {
        $e = $html->find('.id-app-orig-desc');
        
        if (!isset($e[0])) {
            return '';
        }
        
        return $e[0]->plaintext;
    }
    
    protected function getArtworkUrl($html, $size = 300)
    {
        $e = $html->find('.cover-image');
        
        if (!isset($e[0])) {
            return '';
        }
        
        $src = $e[0]->src;
        return preg_replace('/=w[0-9]+/', "=w{$size}", $src);
    }
}