<?php
namespace app\components;
use yii\web\UrlManager;
use Yii;
class TUrlManager extends UrlManager
{
	public $enablePrettyUrl = true;
	public $showScriptName = false;
	
    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param \yii\web\Request $request the request component
     * @return array|boolean the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
	
	public static function cleanText($text=""){
		$text = preg_replace('/[^A-Z0-9]+/i', '-', $text);
		$text = strtolower(trim($text, '-'));
	
		return $text;
	}
	public function parseRequest ( $request ){
	
		return parent::parseRequest($request);
	}
    /**
     * Creates a URL according to the given route and parameters.
     * @param \yii\web\UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl( $params)
    {	
    	$route = preg_replace_callback('/(?<![A-Z])[A-Z]/', function($matches) {
    		return '-' . lcfirst($matches[0]);
    	}, $params[0]);
    	
    	if ( isset( $params['title' ] )) $params['title'] = self::cleanText($params['title']);
    	
        return parent::createUrl($params);
    }
   
}