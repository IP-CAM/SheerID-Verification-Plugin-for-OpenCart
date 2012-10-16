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
		$offers = array();
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('sheer_id');
		
		foreach ($settings["offer"] as $k => $v) {
			$key = preg_replace("/^offer-/", "", $k);
			$v["coupon_code"] = $key;
			$offers[] = $v;
		}
		return $offers;
	}
	
	public function getCouponInfoByCode($coupon_code) {
		if (strpos(DIR_APPLICATION, "admin") === false) {
			$this->load->model('checkout/coupon');
			return $this->model_checkout_coupon->getCoupon($coupon_code);
		} else {
			$this->load->model('sale/coupon');
			return $this->model_sale_coupon->getCouponByCode($coupon_code);
		}
	}
	
	public function getOfferByCouponCode($coupon_code) {
		$this->load->model('setting/setting');
		
		$settings = $this->model_setting_setting->getSetting('sheer_id_modules');
		foreach ($settings['sheer_id_module'] as $s) {
			if ($coupon_code == $s["coupon_code"]) {
				$coupon_info = $this->getCouponInfoByCode($coupon_code);
				if ($coupon_info) {
					$s['code'] = $coupon_info['code'];
				}
				
				foreach ($this->getOffers() as $o) {
					if ($o['coupon_code'] == $coupon_code && isset($o["affiliation_types"])) {
						$s["affiliation_types"] = $o["affiliation_types"];
						return $s;
					}
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

	public function getFields($affiliation_types) {
		//TODO: use service
		$fields = array("FIRST_NAME", "LAST_NAME", "BIRTH_DATE");
		
		if (array_search("VETERAN", $affiliation_types) !== false) {
			$fields[] = "STATUS_START_DATE";
		}
		
		return $fields;
	}
	
	public function getOrganizationType($affiliation_types) {
		//TODO: improve
		if (array_search("ACTIVE_DUTY", $affiliation_types) !== false || array_search("VETERAN", $affiliation_types) !== false) {
			return "military";
		} else {
			return "university";
		}
	}

	public function verify($data, $org, $config) {
		$SheerID = $this->getService();
		return $SheerID->verify($data, $org, $config);
	}
	
	private function loadSheerIDLibrary() {
		require_once(DIR_SYSTEM . 'sheerid/library/SheerID.php');
	}
}
?>