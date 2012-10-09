<?php  
class ControllerCommonSheerID extends Controller {
	public function index() {
		
		if (isset($this->request->post['verified'])) {
			$verified = $this->request->post['verified'];
			if ($verified) {
				$aff_types = array("STUDENT_FULL_TIME");
				$requestId = "fafa0";
			} else {
				$aff_types = array();
				$requestId = "fafa0";
			}
			
			$this->session->data['sheer_id_affiliation_types'] = $aff_types;
			$this->session->data['sheer_id_request_id'] = $requestId;
			
			// Coupon
			if ($verified && isset($this->request->post['coupon_id'])) {
				
				$coupon_code = 'students'; //TODO: look up !!
				
				$this->session->data['coupon'] = $coupon_code;
				$this->session->data['success'] = 'worked';//$this->language->get('text_coupon');
				$this->redirect($this->url->link('checkout/cart'));
			} else {
				$this->session->data['verify_error'] = "Fed up";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}     
	}
}