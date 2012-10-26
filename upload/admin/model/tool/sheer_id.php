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
		
		if (!isset($settings["offer"])) {
			return array();
		}

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
			if (method_exists($this->model_sale_coupon, "getCouponByCode")) {
				return $this->model_sale_coupon->getCouponByCode($coupon_code);
			} else {
				$coupons = $this->model_sale_coupon->getCoupons();
				foreach ($coupons as $c) {
					if ($c['code'] == $coupon_code) {
						return $c;
					}
				}
			}
		}
	}
	
	public function getOfferByCouponCode($coupon_code) {
		$this->load->model('setting/setting');
		
		$settings = $this->model_setting_setting->getSetting('sheer_id_modules');
		if (!isset($settings['sheer_id_module'])) {
			return null;
		}
		
		foreach ($settings['sheer_id_module'] as $s) {
			if (array_key_exists("coupon_code", $s) && $coupon_code == $s["coupon_code"]) {
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
		return $this->getService()->getFields($affiliation_types);
	}
	
	public function getOrganizationType($affiliation_types) {
	 	return $this->getService()->getOrganizationType($affiliation_types);
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