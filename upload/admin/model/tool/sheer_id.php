<?php
class ModelToolSheerID extends Model {
	public function getAffiliationTypes() {
		return array("STUDENT_FULL_TIME", "STUDENT_PART_TIME");
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
}
?>