<?php
class ModelTotalSheerID extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		
		$this->load->model('checkout/coupon');
		$this->load->model('tool/sheer_id');
		
		if (isset($this->session->data['coupon'])) {
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
			$offer = $this->model_tool_sheer_id->getOfferByCouponCode($this->session->data['coupon']);

			if ($coupon_info && $offer) {
				$affiliation_types = isset($this->session->data['sheer_id_affiliation_types']) ? $this->session->data['sheer_id_affiliation_types'] : array();
				$satisfied = count(array_intersect($affiliation_types, $offer['affiliation_types']));
			
				if (!$satisfied) {
					unset($this->session->data['coupon']);
				}
			}
		}
	}
}
?>