<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
 *+------------------------------------------------------------------------------------------------+
 *| @package   edoger-php-src                                                                      |
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *| @version   1.0.0 Alpha                                                                         |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */
namespace Edoger\Http;

final class Agent
{
	private $_os = [
		'windows nt 10.0'	=> 'Windows 10',
		'windows nt 6.3'	=> 'Windows 8.1',
		'windows nt 6.2'	=> 'Windows 8',
		'windows nt 6.1'	=> 'Windows 7',
		'windows nt 6.0'	=> 'Windows Vista',
		'windows nt 5.2'	=> 'Windows 2003',
		'windows nt 5.1'	=> 'Windows XP',
		'windows nt 5.0'	=> 'Windows 2000',
		'windows nt 4.0'	=> 'Windows NT 4.0',
		'winnt4.0'			=> 'Windows NT 4.0',
		'winnt 4.0'			=> 'Windows NT',
		'winnt'				=> 'Windows NT',
		'windows 98'		=> 'Windows 98',
		'win98'				=> 'Windows 98',
		'windows 95'		=> 'Windows 95',
		'win95'				=> 'Windows 95',
		'windows phone'		=> 'Windows Phone',
		'windows'			=> 'Unknown Windows OS',
		'android'			=> 'Android',
		'blackberry'		=> 'BlackBerry',
		'iphone'			=> 'iOS',
		'ipad'				=> 'iOS',
		'ipod'				=> 'iOS',
		'os x'				=> 'Mac OS X',
		'ppc mac'			=> 'Power PC Mac',
		'freebsd'			=> 'FreeBSD',
		'ppc'				=> 'Macintosh',
		'linux'				=> 'Linux',
		'debian'			=> 'Debian',
		'sunos'				=> 'Sun Solaris',
		'beos'				=> 'BeOS',
		'apachebench'		=> 'ApacheBench',
		'aix'				=> 'AIX',
		'irix'				=> 'Irix',
		'osf'				=> 'DEC OSF',
		'hp-ux'				=> 'HP-UX',
		'netbsd'			=> 'NetBSD',
		'bsdi'				=> 'BSDi',
		'openbsd'			=> 'OpenBSD',
		'gnu'				=> 'GNU/Linux',
		'unix'				=> 'Unknown Unix OS',
		'symbian' 			=> 'Symbian OS'
	];
	private $_browser = [
		'OPR'				=> 'Opera',
		'Flock'				=> 'Flock',
		'Edge'				=> 'Spartan',
		'Chrome'			=> 'Chrome',
		'Opera.*?Version'	=> 'Opera',
		'Opera'				=> 'Opera',
		'MSIE'				=> 'Internet Explorer',
		'Internet Explorer'	=> 'Internet Explorer',
		'Trident.* rv'		=> 'Internet Explorer',
		'Shiira'			=> 'Shiira',
		'Firefox'			=> 'Firefox',
		'Chimera'			=> 'Chimera',
		'Phoenix'			=> 'Phoenix',
		'Firebird'			=> 'Firebird',
		'Camino'			=> 'Camino',
		'Netscape'			=> 'Netscape',
		'OmniWeb'			=> 'OmniWeb',
		'Safari'			=> 'Safari',
		'Mozilla'			=> 'Mozilla',
		'Konqueror'			=> 'Konqueror',
		'icab'				=> 'iCab',
		'Lynx'				=> 'Lynx',
		'Links'				=> 'Links',
		'hotjava'			=> 'HotJava',
		'amaya'				=> 'Amaya',
		'IBrowse'			=> 'IBrowse',
		'Maxthon'			=> 'Maxthon',
		'Ubuntu'			=> 'Ubuntu Web Browser'
	];
	private $_mobile = [
		'mobileexplorer'		=> 'Mobile Explorer',
		'palmsource'			=> 'Palm',
		'palmscape'				=> 'Palmscape',
		'motorola'				=> 'Motorola',
		'nokia'					=> 'Nokia',
		'palm'					=> 'Palm',
		'iphone'				=> 'Apple iPhone',
		'ipad'					=> 'iPad',
		'ipod'					=> 'Apple iPod Touch',
		'sony'					=> 'Sony Ericsson',
		'ericsson'				=> 'Sony Ericsson',
		'blackberry'			=> 'BlackBerry',
		'cocoon'				=> 'O2 Cocoon',
		'blazer'				=> 'Treo',
		'lg'					=> 'LG',
		'amoi'					=> 'Amoi',
		'xda'					=> 'XDA',
		'mda'					=> 'MDA',
		'vario'					=> 'Vario',
		'htc'					=> 'HTC',
		'samsung'				=> 'Samsung',
		'sharp'					=> 'Sharp',
		'sie-'					=> 'Siemens',
		'alcatel'				=> 'Alcatel',
		'benq'					=> 'BenQ',
		'ipaq'					=> 'HP iPaq',
		'mot-'					=> 'Motorola',
		'playstation portable'	=> 'PlayStation Portable',
		'playstation 3'			=> 'PlayStation 3',
		'playstation vita'		=> 'PlayStation Vita',
		'hiptop'				=> 'Danger Hiptop',
		'nec-'					=> 'NEC',
		'panasonic'				=> 'Panasonic',
		'philips'				=> 'Philips',
		'sagem'					=> 'Sagem',
		'sanyo'					=> 'Sanyo',
		'spv'					=> 'SPV',
		'zte'					=> 'ZTE',
		'sendo'					=> 'Sendo',
		'nintendo dsi'			=> 'Nintendo DSi',
		'nintendo ds'			=> 'Nintendo DS',
		'nintendo 3ds'			=> 'Nintendo 3DS',
		'wii'					=> 'Nintendo Wii',
		'open web'				=> 'Open Web',
		'openweb'				=> 'OpenWeb',
		'android'				=> 'Android',
		'symbian'				=> 'Symbian',
		'SymbianOS'				=> 'SymbianOS',
		'elaine'				=> 'Palm',
		'series60'				=> 'Symbian S60',
		'windows ce'			=> 'Windows CE',
		'obigo'					=> 'Obigo',
		'netfront'				=> 'Netfront Browser',
		'openwave'				=> 'Openwave Browser',
		'mobilexplorer'			=> 'Mobile Explorer',
		'operamini'				=> 'Opera Mini',
		'opera mini'			=> 'Opera Mini',
		'opera mobi'			=> 'Opera Mobile',
		'fennec'				=> 'Firefox Mobile',
		'digital paths'			=> 'Digital Paths',
		'avantgo'				=> 'AvantGo',
		'xiino'					=> 'Xiino',
		'novarra'				=> 'Novarra Transcoder',
		'vodafone'				=> 'Vodafone',
		'docomo'				=> 'NTT DoCoMo',
		'o2'					=> 'O2',
		'mobile'				=> 'Generic Mobile',
		'wireless'				=> 'Generic Mobile',
		'j2me'					=> 'Generic Mobile',
		'midp'					=> 'Generic Mobile',
		'cldc'					=> 'Generic Mobile',
		'up.link'				=> 'Generic Mobile',
		'up.browser'			=> 'Generic Mobile',
		'smartphone'			=> 'Generic Mobile',
		'cellphone'				=> 'Generic Mobile'
	];
	private $_robot = [
		'googlebot'				=> 'Googlebot',
		'msnbot'				=> 'MSNBot',
		'baiduspider'			=> 'Baiduspider',
		'bingbot'				=> 'Bing',
		'slurp'					=> 'Inktomi Slurp',
		'yahoo'					=> 'Yahoo',
		'ask jeeves'			=> 'Ask Jeeves',
		'fastcrawler'			=> 'FastCrawler',
		'infoseek'				=> 'InfoSeek Robot 1.0',
		'lycos'					=> 'Lycos',
		'yandex'				=> 'YandexBot',
		'mediapartners-google'	=> 'MediaPartners Google',
		'CRAZYWEBCRAWLER'		=> 'Crazy Webcrawler',
		'adsbot-google'			=> 'AdsBot Google',
		'feedfetcher-google'	=> 'Feedfetcher Google',
		'curious george'		=> 'Curious George',
		'ia_archiver'			=> 'Alexa Crawler',
		'MJ12bot'				=> 'Majestic-12',
		'Uptimebot'				=> 'Uptimebot'
	];

	private $_osName			= 'Unknown';
	private $_browserName		= 'Unknown';
	private $_browserVersion	= '';
	private $_isBrowser			= false;
	private $_mobileName		= 'Unknown';
	private $_isMobile			= false;
	private $_robotName			= 'Unknown';
	private $_isRobot			= false;

	public function __construct(string $agent)
	{
		$this->parseOs($agent);
		$this->parseBrowser($agent);
		$this->parseMobile($agent);
		$this->parseRobot($agent);
	}

	private function parseOs(string $agent)
	{
		foreach ($this->_os as $key => $value) {
			if (preg_match('/'.preg_quote($key).'/i', $agent)) {
				$this->_osName = $value;
				break;
			}
		}
	}

	private function parseBrowser(string $agent)
	{
		foreach ($this->_browser as $key => $value) {
			if (preg_match('/'.$key.'.*?([0-9\.]+)/i', $agent, $m)) {
				$this->_browserName		= $value;
				$this->_browserVersion	= $m[1];
				$this->_isBrowser		= true;
				break;
			}
		}
	}

	private function parseMobile(string $agent)
	{
		foreach ($this->_mobile as $key => $value) {
			if (stripos($agent, $key) !== false) {
				$this->_mobileName	= $value;
				$this->_isMobile	= true;
				break;
			}
		}
	}

	private function parseRobot(string $agent)
	{
		foreach ($this->_robot as $key => $value) {
			if (preg_match('/'.preg_quote($key).'/i', $agent)) {
				$this->_robotName	= $value;
				$this->_isRobot		= true;
				break;
			}
		}
	}

	public function os()
	{
		return $this->_osName;
	}

	public function isBrowser()
	{
		return $this->_isBrowser;
	}

	public function browserName()
	{
		return $this->_browserName;
	}

	public function browserVersion()
	{
		return $this->_browserVersion;
	}

	public function isMobile()
	{
		return $this->_isMobile;
	}

	public function mobileName()
	{
		return $this->_mobileName;
	}

	public function isRobot()
	{
		return $this->_isRobot;
	}

	public function robotName()
	{
		return $this->_robotName;
	}
}