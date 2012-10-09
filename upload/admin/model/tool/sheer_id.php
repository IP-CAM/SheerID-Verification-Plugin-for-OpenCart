<?php
class ModelToolSheerID extends Model {
	public function getAffiliationTypes() {
		try {
			return $this->getService()->listAffiliationTypes();
		} catch (Exception $e) {
			return array();
		}
	}
	
	public function getOffers() {
		return array(
			array("coupon_id" => 7, "affiliation_types" => $this->getAffiliationTypes())
		);
	}
	
	public function getDiscount($coupon_id) {
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('sheer_id_modules');
		foreach ($settings['sheer_id_module'] as $s) {
			if ($coupon_id == $s["coupon_id"]) {
				
				$settings = $this->model_setting_setting->getSetting('sheer_id');
				
				if (isset($settings["affiliation_types-$coupon_id"])) {
					$s["affiliation_types"] = $settings["affiliation_types-$coupon_id"];
					return $s;
				}
			}
		}
		return null;
	}
	
	public function getService() {	
		$accessToken = $this->config->get('sheer_id_access_token');
		$baseUrl = $this->config->get('sheer_id_base_url');
		$this->loadSheerIDLibrary();
		return new SheerID($accessToken, $baseUrl);
	}

	public function verify() {
		$SheerID = $this->getService();
		return json_decode("{\"result\":true}");
	}
	
	private function loadSheerIDLibrary() {
		require_once(DIR_SYSTEM . 'sheerid/library/SheerID.php');
	}
}
?>