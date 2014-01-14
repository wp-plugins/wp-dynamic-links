<?php

class PMLC_Destination_Record extends PMLC_Model_Record {
	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'destinations');
	}
	
	public function getUrl() {
		if (isset($this->url)) {
			return $this->url;
		} else {
			return NULL;
		}
	}
	
	/**
	 * Redirect to urls defined by the current destination
	 * NOTES: function relies on `__a` get parameter to track redirect methods (dev note: codes below 100 are inital referer loosing operation, codes above 100 is operation of loosing referer after tracking code is output) 
	 * @param string $type Redirection type
	 */
	public function redirect($type) {
		$url = $this->url;
		
		if ( ! headers_sent()) {
			header('Cache-Control: no-cache');
	  		header('Pragma: no-cache');
		}
		$rule = $this->getRelated('PMLC_Rule_Record', array('id' => 'rule_id'));
		$link = $rule->getRelated('PMLC_Link_Record', array('id' => 'link_id'));


		if (PMLC_Plugin::getInstance()->getOption('forward_url_params') and $link->forward_url_params) { // forward query params
			foreach ($_GET as $key => $val) {
				! in_array($key, array('subid', 'cloaked', '__a')) and $url = add_query_arg($key, $val, $url);
			}
		}
			
		if (is_numeric($type)) {
			header("Location: " . $url, true, intval($type));
		} else if ('META_REFRESH' == $type) {
			echo  '<html><head><title>' . $link->name . '</title><meta http-equiv="refresh" content="' . PMLC_Plugin::getInstance()->getOption('meta_redirect_delay') . '; URL=' . $url . '" />' . $link->getTrackingCode('header') . '</head><body>' . $link->getTrackingCode('footer') . '</body></html>';
		} else if ('JAVASCRIPT' == $type) {
			echo '<html><head><title>' . $link->name . '</title>' . $link->getTrackingCode('header') . '</head><body><script type="text/javascript">window.onload=function(){window.top.location.replace(\'' . $url . '\');};</script>' . $link->getTrackingCode('footer') . '</body></html>';
		} else {
			throw new Exception('Unsupported redirection type specified');
		}
			
		// [log redirect to stats]
		$input = new PMLC_Input();
		$geoip = new PMLC_GeoIPCountry_Record();
			
		// detect referrer
		$referer = '';

		isset($_SERVER['HTTP_REFERER']) and $referer = $_SERVER['HTTP_REFERER'];

		unset($_SESSION[PMLC_Plugin::PREFIX . 'referer']);
			
		// create stat object
		$stat = new PMLC_Stat_Record(array(
			'link_id' => $link->id,
			'sub_id' => isset($_GET['subid']) ? $_GET['subid'] : '',
			'registered_on' => date('Y-m-d H:i:s'),
			'rule_type' => $rule->type,
			'destination_url' => $url,
			'ip' => $input->server('REMOTE_ADDR', ''),
			'ip_num' => sprintf('%u', ip2long($input->server('REMOTE_ADDR', '0.0.0.0'))),
			'country' => ! $geoip->getByIp($input->server('REMOTE_ADDR', ''))->isEmpty() ? $geoip->country : '',
			'host' => $input->server('REMOTE_HOST', ''),
			'user_agent' => $input->server('HTTP_USER_AGENT', ''),
			'accept_language' => $input->server('HTTP_ACCEPT_LANGUAGE', ''),
			'referer' => $referer,
		));
		$stat->insert();
		// [/log redirect to stats]

		die();
	}

}