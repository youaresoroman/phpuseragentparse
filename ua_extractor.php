<?php
class UAExtractor {
	
	public $RawUA, $Data;
	private $WorkingUA, $WorkingArray, $Replaceable, $DBUA, $DBSetting, $DBObvious;
	
	public function __construct($useragent){
		$this->RawUA = $useragent;
		$this->DBSetting = array(
		 	'software' => 1,
		 	'os_distro' => 1,
		 	'rendering_engine' => 1,
		 	'os_name' => 1,
			'ua_param' => 1,
			'device_overlay' => 1,
			'bot' => 1,
			'cpu_model' => 1,
			'device_model' => 1,
			'localization' => 0,
			'os_platform' => 0,
			'device_brand' => 0,
			'device_type' => 0
		);
		$this->DBUA['ua_param'] = array(
			'dpi' => 'dpi'
		);
		$this->Replaceable = array(
			", " => ",",
			"-" => "_",
			"mozilla/5.0 " => "",
			" (khtml,like gecko)" => "",
			" (khtml,like gecko; googleweblight)" => "",
			"instagram " => "instagram/",
			"+http" => "http",
			"compatible; " => "",
			"mobile safari" => "mobile_safari",
			" like mac os x" => "",
			"cpu iphone os" => "iphone",
			" build/" => "/build-",
			"linux " => "linux; ",
			" like android" => "",
			'kindle' => 'amazon;kindle'
		);
		$this->DBUA['localization'] = array(
			'Deutsch' => 'de_de|de',
			'French' => 'fr_fr|fr',
			'English' => 'en_us|en|us',
			'Polish' => 'pl_pl|pl',
			'Russian' => 'ru_ru|ru',
			'Catalan, Valencian' => 'en_ca',
			'Brazilian Portuguese' => 'pt_br',
			'Portuguese' => 'pt'
		);
		$this->DBUA['software'] = array(
			'Opera_old' => 'opera',
			'Opera' => 'opr',
			'Internet Explorer' => 'msie',
			'Netscape' => 'netscape',
			'Internet Explorer Mobile' => 'iemobile',
			'Mozilla Firefox' => 'firefox',
			'Google Chrome' => 'chrome',
			'Safari' => 'safari',
			'Mobile Safari' => 'mobile_safari',
			'PaleMoon' => 'palemoon',
			'Ovi' => 'ovibrowser',
			'Konqueror' => 'konqueror',
			'NetFront' => 'netfront',
			'Nintendo Browser' => 'nintendobrowser',
			'Blazer' => 'Blazer',
			'Microsoft Outlook' => 'microsoft outlook',
			'Zgrab' => 'zgrab',
			'Go-http-client' => 'go_http_client',
			'okhttp' => 'okhttp',
			'python_requests' => 'python_requests'
		);
		$this->DBUA['rendering_engine'] = array(
			'Khtml' => 'khtml',
			'Gecko' => 'gecko|gecko firefox',
			'WebKit' => 'applewebkit',
			'EdgeHTML' => 'edge',
			'Goanna' => 'goanna',
			'NetFront' => 'netfront',
			'Chromium Embedded Framework' => 'coder nut',
			'Ekiohflow' => 'ekiohflow',
			'Trident' => 'trident|.net clr ',
			'Presto' => 'presto'
		);
		$this->DBUA['bot'] = array(
			'Jobboerse' => 'jobboersebot',
			'Apple' => 'applebot',
			'Google' => 'googlebot',
			'Yandex' => 'yandexbot',
			'Plesk' => 'pleskbot',
			'ips-agent' => 'ips_agent',
			'Nimbostratus' => 'nimbostratus_bot',
			'NetcraftSurvey' => 'netcraftsurveyagent',
			'Bing' => 'bingbot',
			'WebDataStats' => 'webdatastats',
			'Panscient' => 'panscient.com',
			'Yahoo!' => 'yahoo!|slurp',
			'DuckDuck' => 'duckduckbot',
			'Sogou' => 'sogou pic spider|sogou head spider|sogou web spider|sogou orion spider|sogou_test_spider',
			'Exabot' => 'exabot',
			'ia_archiver' => 'ia_archiver',
			'Ahrefs' => 'ahrefsbot',
			'Pandalytics' => 'pandalytics',
			'Dataprovider.com' => 'dataprovider.com',
			'CheckMarkNetwork' => 'checkmarknetwork',
			'DuckDuckGo-Favicons' => 'duckduckgo_favicons_bot',
			'Ask Jeeves' => 'ask jeevesteoma',
			'NC' => 'ncbot',
			'Nutch' => 'nutch-'
		);
		$this->DBUA['os_platform'] = array(
			'ARM' => 'arm',
			'ARM v7' => 'armv7',
			'amd64' => 'amd64',
			'x64' => 'wow64|x64|irix64',
			'x86_64' => 'x86_64',
			'x86' => 'x86|i386|i486|i586|i686|i86pc'
		);
		$this->DBUA['os_name'] = array(
			'Android' => 'android',
			'Bada' => 'bada',
			'BeOS' => 'beos',
			'Blackberry Os' => 'blackberry',
			'ChromeOS' => 'cros',
			'FreeBSD' => 'freebsd',
			'Haiku' => 'haiku',
			'Hp Webos' => 'hpwos',
			'IOS' => 'darwin|ipad',
			'iPhone OS' => 'iphone',
			'Irix' => 'irix',
			'Linux' => 'linux|x11',
			'Livearea' => 'playStation vita',
			'Mac' => 'mac_powerpc',
			'Mac OS X' => 'intel mac os x',
			'OpenBSD' => 'openbsd',
			'PalmOS' => 'palmsource|palm|ppc|palmos',
			'Rim Tablet Os' => 'rim tablet os',
			'Sunos' => 'sunos',
			'Symbian' => 'symbianos',
			'Tizen' => 'tizen',
			'Webos' => 'webos',
			'Windows' => 'windows98|windows95',
			'Windows NT' => 'windows nt',
			'Windows Mobile' => 'windows mobile',
			'Windows Phone' => 'windows phone'
		);
		$this->DBUA['os_distro'] = array(
			'Ubuntu' => 'ubuntu'
		);
		$this->DBUA['device_brand'] = array(
			'Sony' => 'playstation|sony',
			'Nokia' => 'nokia|lumia',
			'Samsung' => 'sch_|samsung_gt_|gt_|sm_',
			'Nintendo' => 'nintendo',
			'ZTE' => 'zte-',
			'LG' => 'lge',
			'Phillips' => 'phillips',
			'Apple' => 'intel mac os x|iphone|ipad',
			'Meizu' => 'meizu',
			'Apple' => 'iphone|ipad',
			'ASUS' => 'asus|asus_',
			'HTC' => 'htc',
			'Sony Ericsson' => 'sony ericsson',
			'Motorola' => 'motorola|moto',
			'Siemens' => 'siemens',
			'Verizon' => 'verizon',
			'Sharp' => 'sharp',
			'Lenovo' => 'lenovo',
			'Alcatel' => 'alcatel',
			'Oppo' => 'oppo',
			'Oneplus' => 'oneplus',
			'Dell' => 'dell',
			'Xiaomi' => 'xiaomi',
			'Acer' => 'acer',
			'Archos' => 'archos',
			'Nexus' => 'nexus',
			"Amazon" => 'amazon'
		);
		$this->DBUA['device_model'] = array(
			'PlayStation Vita' => 'playStation vita',
			'PlayStation Portable' => 'psp (playstation portable)',
			'Lumia' => 'lumia',
			'Moto C' => 'moto c',
			'One' => 'motorola one',
			'Archos 50' => 'archos 50',
			'Kindle' => 'kindle'
		);
		$this->DBUA['device_type'] = array(
			'Mobile Phone' => 'mobile|android|windows mobile|windows phone|lumia',
			'TV' => 'smart-tv|netcast|webos|webostv|googletv|phillipstv|hbbtv|nettv',
			'Tablet' => 'hp-tablet|playbook|rim tablet os',
			'Game Console' => 'playstation|psp|nintendo',
			'Car' => 'tesla qtcarbrowser|tesla_|tesla',
			'E-Reader' => 'kindle',
			'Computer' => 'x86|i386|i486|i586|i686|x11|wow64|x64|irix64|x86_64|intel mac os x|macintosh|windows nt'
		);
		$this->DBUA['device_overlay'] = array(
			'Instagram' => 'instagram',
			'Facebook App' => 'facebook'
		);
		$this->DBUA['cpu_model'] = array(
			'Intel Core i7' => 'core i7_',
			"Arm v7" => 'armv7'
		);
		$this->DBUA['fb_device_version'] = 'fbdv';
		$this->DBUA['fb_major_device'] = 'fbmd';
		$this->DBUA['fb_system_name'] = 'fbsn';
		$this->DBUA['fb_system_version'] = 'fbsv';
		$this->DBUA['fb_identity_of_device'] = 'fbid';
		$this->DBUA['fb_language_code'] = 'fblc';
		$this->DBUA['fb_carrier'] = 'fbcr';
		
		$this->Data = array();
		$this->WorkingArray = array();
	}
	
	public function Extract(){
		if (!is_null($this->RawUA) && $this->RawUA != ''){
			$this->CleanUA();
			$this->Cut("quotes");
			$this->Cut("blocks");
			if (array_key_exists('linux',$this->WorkingArray)){
				$value = $this->WorkingArray['linux'];
				unset($this->WorkingArray['linux']);
				$this->WorkingArray['linux'] = $value;
			}
			$this->CutOther();
			$this->PrepareObvious();
			$this->Prepare();
			$this->DoCRC();
			$this->SaveUnrecognized();
			$this->Data['raw'] = $this->RawUA;
		}
		else {
			$this->Data['controlsum'] = 'unknown';	
		}
		return $this->Data;
	}	
	
	public function CleanUA() {
		$this->WorkingUA = strtolower($this->RawUA);
		foreach ($this->Replaceable as $key => $value){
			$this->WorkingUA = str_replace($key, $value, $this->WorkingUA);
		}
	}
	
	public function Cut($type){
		if ($type == "quotes"){
			$letter = array("(",")");
		}
		if ($type == "blocks"){
			$letter = array("[","]");		
		}
		$quotes = substr_count($this->WorkingUA, $letter[1])+1;
		$b=0;
		for($i = 1; $i != $quotes; ++$i) {
			$cut = substr($this->WorkingUA, 0, strpos($this->WorkingUA, $letter[1]));
			$cut = substr($cut, strrpos($cut, $letter[0])+1, strlen($cut));
			$this->WorkingUA = str_replace($letter[0] . $cut . $letter[1], "", $this->WorkingUA);
			$cut = str_replace("; ", ";", $cut);
			$array = explode(";", $cut);
			foreach ($array as $value){
				$cutval = $value;
				$cutval1 = '';
				if (substr_count($value, " ") > 0){
					$cutval = substr($value, 0, strrpos($value, " "));
					$cutval1 = str_replace($cutval . " ", "", $value);
				}
				elseif (substr_count($value, "/") > 0 && substr_count($value, "//") == null){
					$cutval = substr($value, 0, strrpos($value, "/"));
					$cutval1 = str_replace($cutval . "/", "", $value);
				}
				if (!is_numeric($cutval) || $cutval1 != ''){
				$this->WorkingArray[$cutval] = $cutval1;
				}			
			}
		}
	}
	
	public function CutOther() {
		$i = 0;
		$strings = strtolower($this->WorkingUA);
		$array = explode(" ", $strings);
		foreach ($array as $key => $value){
		//lowercase
			$split = array ($value,'');
			if ($value != ""){
				if (strpos($value, "/") != null){
					$split = explode("/", $value);
				}
			}
			if (array_key_exists($split[0], $this->WorkingArray) && $split[1] != ''){
				$this->WorkingArray[$split[0]] = $split[1];			
			}
			elseif (!array_key_exists($split[0], $this->WorkingArray)){
				$this->WorkingArray[$split[0]] = $split[1];
			}
		}
		if (array_key_exists('',$this->WorkingArray) && $this->WorkingArray[''] == ''){
			unset($this->WorkingArray['']);
		}
	}
		
	public function PrepareObvious(){
		foreach ($this->WorkingArray as $key => $value){
			if (strpos($key, "x") != null){
				$split = explode("x", $key);
				if (is_numeric($split[0]) && is_numeric($split[1])){
					$this->Data["screen_size"] = $key;
					unset($this->WorkingArray[$key]);
				}
			}
			if ($key == 'fblc'){
				$this->WorkingArray[$value] = null;
			}
			elseif ($key == 'fbop' || $key ==  'fbss' || $key == 'fbbv'){
				unset($this->WorkingArray[$key]);		
			}
			if ($key == 'fban'){
				$this->WorkingArray['facebook'] = $value;
				unset($this->WorkingArray[$key]);
				if (array_key_exists('fbav', $this->WorkingArray)){
					$this->WorkingArray['facebook'] = $this->WorkingArray['facebook'] . '/' . $this->WorkingArray['fbav'];
					unset($this->WorkingArray['fbav']);
					/*if (array_key_exists('fbbv', $this->WorkingArray)){
						$this->WorkingArray['facebook'] = $this->WorkingArray['facebook'] . '/' . $this->WorkingArray['fbbv'];
						unset($this->WorkingArray['fbbv']);				
					}*/
				}	
			}
		}
	}
	
	public function Prepare() {
		foreach ($this->WorkingArray as $key => $value) {
			foreach (array_keys($this->DBUA) as $group) {
				if (!array_key_exists($group, $this->Data)) {
					if (is_array($this->DBUA[$group])){
						foreach ($this->DBUA[$group] as $name => $pattern) {
							if (preg_match('/\b' . $pattern . '\b/i', $key)) {
								if ($this->DBSetting[$group] == 1 && $value != null){
									$this->Data[$group] = $name;
									$this->Data[$group . '_value'] = $value;
								}
								elseif ($this->DBSetting[$group] == 0 || $value == null){
									$this->Data[$group] = $name;
								}
								if ($group != 'device_brand'){
									unset($this->WorkingArray[$key]);
								}
							}
						}
					}
					else {
						if (preg_match('/' . $this->DBUA[$group] . '/i', $key)) {
							$this->Data[$group] = $value;
							unset($this->WorkingArray[$key]);		
						}						
					}
				}
			}
		}
	}
	
	public function SaveUnrecognized() {
		$this->Data['unrecognized'] = null;
		foreach ($this->WorkingArray as $key => $value){
			if ($value != ''){
				$key = $key . '/';			
			}
			$this->Data['unrecognized'] = $this->Data['unrecognized'] . $key . $value . ';';
			unset($this->WorkingArray[$key]);
		}
	}
	
	public function DoCRC() {
		$this->Data['controlsum'] = sha1(serialize($this->Data));
	}
}

function extractUAData($ua_agent){
	$ua_data = new UAExtractor($ua_agent);
	return $ua_data->Extract();
}
?>
