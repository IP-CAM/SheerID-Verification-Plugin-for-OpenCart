<?php  
class ControllerCommonSheerID extends Controller {
	public function index() {
		if (isset($this->request->post['coupon_code'])) {
			
			$this->load->model('tool/sheer_id');
			$offer = $this->model_tool_sheer_id->getOfferByCouponCode($this->request->post['coupon_code']);
			
			$this->load->language('common/sheer_id');
			
			if ($offer) {
				$invalid = false;
				$config = array("affiliationTypes" => implode(",", $offer["affiliation_types"]));
				$orgId = $this->request->post['organizationId'];
				$data = array();
				
				$fields = $this->model_tool_sheer_id->getFields($offer["affiliation_types"]);
				foreach ($fields as $f) {
					$val = $this->getPostData($f);
					if ($val) {
						$data[$f] = $val;
					} else {
						$invalid = true;
					}
				}
				
				if ($invalid) {
					$this->session->data['verify_error'] = $this->language->get("error_invalid");
					$this->redirect($_SERVER['HTTP_REFERER']);
					return;
				}

				try {
					$response = $this->model_tool_sheer_id->verify($data, $orgId, $config);

					$verified = $response->result;
					$requestId = $response->requestId;
					$aff_types = array();
				
					if ($verified) {
						foreach ($response->affiliations as $aff) {
							$aff_types[] = $aff->type;
						}
					}

					$this->session->data['sheer_id_affiliation_types'] = $aff_types;
					$this->session->data['sheer_id_request_id'] = $requestId;
				} catch (Exception $e) {
					$this->session->data['verify_error'] = $this->language->get("error");
					$this->redirect($_SERVER['HTTP_REFERER']);
					return;
				}
			}
			
			if ($offer && $verified) {
				$this->session->data['coupon'] = $offer['code'];
				$this->session->data['success'] = $this->language->get("success");
				$this->redirect($this->url->link('checkout/cart'));
			} else {
				$this->session->data['verify_error'] = $this->language->get("error");
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}     
	}
	
	private function getPostData($key) {
		if (strpos($key, "_DATE") === false) {
			return $this->request->post[$key];
		} else {
			$m = $this->request->post["${key}_month"];
			$d = $this->request->post["${key}_day"];
			$y = $this->request->post["${key}_year"];
			
			if ($m && $d && $y) {
				return date("Y-m-d", mktime(0, 0, 0, $m, $d, $y));
			}
		}
	}
}