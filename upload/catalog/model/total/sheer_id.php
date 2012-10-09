<?php
class ModelTotalSheerID extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		
		$this->load->model('checkout/coupon');
		
		if (isset($this->session->data['coupon'])) {
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon_info) {
				$key = sprintf("Coupon(%s)", $coupon_info["code"]);
				$aff_type = "STUDENT_FULL_TIME";
				$affiliation_types = isset($this->session->data['sheer_id_affiliation_types']) ? $this->session->data['sheer_id_affiliation_types'] : array();
				$satisfied = false !== array_search($aff_type, $affiliation_types);
			
				if (!$satisfied) {
					unset($this->session->data['coupon']);
				}
			}
		}
	}
	
	public function getAffiliationTypes() {
		return array("STUDENT_FULL_TIME", "STUDENT_PART_TIME");
	}
}
?>