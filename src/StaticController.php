<?php
namespace mvc;

require_once(__DIR__.'/Controller.php');

class StaticController extends Controller {
	private $staticDir, $path, $mimetype;
	public function __construct($staticDir, $params) {
		parent::__construct($params);

		$this->staticDir = $staticDir;
		$this->path = $this->staticDir.'/'.$this->params->getUrl();
	}
	
	public function getView() {
		$contents = file_get_contents($this->path);
		return new SimpleView($contents);
	}
	
	public function render() {
		if(!headers_sent()) {
			header('content-type: '.$this->getMimetype());
		}
		readfile($this->path);
	}
	
	public static function getJSMatcher() {
		return '/\.js(\?.*)?$/i';
	}
	
	public static function getCSSMatcher() {
		return '/\.css(\?.*)?$/i';
	}
	
	public static function getImgMatcher() {
		return '/\.(png|jpe?g|gif|svg)(\?.*)?$/i';
	}
	
	public function setMimetype($type) {
		$this->mimetype = $type;
	}
	
	public function getMimetype() {
		if($this->mimetype)
			return $this->mimetype;
		
		switch(\strtolower(\pathinfo($this->path, PATHINFO_EXTENSION))) {
			case 'js':
				return 'text/javascript';
			case 'css':
				return 'text/css';
			case 'jpg':
			case 'jpeg':
				return 'image/jpeg';
			case 'png':
				return 'image/png';
			case 'gif':
				return 'image/gif';
			case 'svg':
				return 'image/svg+xml';
			default:
				return 'application/octet-stream';
		}
	}
}
?>