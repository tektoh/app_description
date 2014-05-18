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
        $results['trackName'] = $this->getTrackName($html);
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
        return $html->find('.document-title')[0]->plaintext;
    }
    
    protected function getDescription($html)
    {
        return $html->find('.id-app-orig-desc')[0]->plaintext;
    }
    
    protected function getArtworkUrl($html, $size = 300)
    {
        $src = $html->find('.cover-image')[0]->src;
        return preg_replace('/=w[0-9]+/', "=w{$size}", $src);
    }
}