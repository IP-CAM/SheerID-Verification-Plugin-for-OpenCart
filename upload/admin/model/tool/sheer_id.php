<?php
class ModelToolSheerID extends Model {
	
	var $NOTIFIER_TAG = "opencart";
	
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
		$my_fields = array();
		foreach ($this->getService()->getFields($affiliation_types) as $field) {
			$my_fields[] = "*$field";
		}
		
		if ($this->model_tool_sheer_id->allowEmail()) {
			$my_fields[] = "*EMAIL";
		}
		
		return array_unique($my_fields);
	}
	
	public function getOrganizationType($affiliation_types) {
	 	return $this->getService()->getOrganizationType($affiliation_types);
	}

	public function verify($data, $org, $config) {
		$SheerID = $this->getService();
		return $SheerID->verify($data, $org, $config);
	}
	
	public function getSiteBaseUrl($controller) {
		if (isset($controller->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($controller->request->server['HTTPS'] == '1'))) {
			return $controller->config->get('config_ssl');
		} else {
			return $controller->config->get('config_url');
		}
	}
	
	public function getClaimOfferUrl($controller, $requestId) {
		$baseUrl = $this->getSiteBaseUrl($controller);
		$url_helper = new Url($baseUrl);
		return $url_helper->link('common/sheer_id/claim') . '&requestId=' . $requestId;
	}
	
	public function getEmailNotifier() {
		$SheerID = $this->getService();
		if ($SheerID) {
			$notifiers = $SheerID->getJson('/notifier', array("tag" => $this->NOTIFIER_TAG));
			if (count($notifiers)) {
				return $notifiers[0];
			}
		}
	}

	public function updateEmailNotifier($notifierId, $config) {
		$SheerID = $this->getService();
		if ($SheerID) {
			$config["tag"] = $this->NOTIFIER_TAG;
			$resp = $SheerID->post("/notifier/$notifierId", $config);
			return json_decode($resp["responseText"]);
		}
	}
	
	public function addEmailNotifier($config) {
		$SheerID = $this->getService();
		if ($SheerID) {
			$config["tag"] = $this->NOTIFIER_TAG;
			$config["type"] = "EMAIL";
			$resp = $SheerID->post('/notifier', $config);
			return json_decode($resp["responseText"]);
		}
	}
	
	public function removeEmailNotifier() {
		$notifier = $this->getEmailNotifier();
		if ($notifier) {
			$notifierId = $notifier->id;
			$SheerID = $this->getService();
			if ($SheerID) {
				$SheerID->delete("/notifier/$notifierId");
			}
		}
	}
	
	public function getEmailDefaults() {
		$this->load->language('total/sheer_id');
		return array(
			"emailFromAddress" => $this->language->get('emailFromAddress'),
			"emailFromName" => $this->config->get('config_name'),
			"successEmailSubject" => $this->language->get('successEmailSubject'),
			"successEmail" => $this->language->get('successEmail'),
			"failureEmailSubject" => $this->language->get('failureEmailSubject'),
			"failureEmail" => $this->language->get('failureEmail')
		);
		
	}
		
	public function allowUploads() {
		return !!$this->config->get('sheer_id_allow_uploads');
	}
	
	public function allowEmail() {
		return $this->allowUploads() && $this->config->get('sheer_id_send_email');
	}
	
	private function loadSheerIDLibrary() {
		require_once(DIR_SYSTEM . 'sheerid/library/SheerID.php');
	}
}
?>