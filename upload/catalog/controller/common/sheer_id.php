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
				
				$data[":coupon_code"] = $this->request->post['coupon_code'];

				try {
					$response = null;
					$cfgRep = $this->getRequestConfigRepresentation($orgId, $config);
					$SheerID = $this->model_tool_sheer_id->getService();
					
					// try to resubmit if possible -- TODO: revisit business logic for request updates
					if ($SheerID && isset($this->session->data['sheer_id_request_id']) && isset($this->session->data['sheer_id_request_config']) && $cfgRep == $this->session->data['sheer_id_request_config']) {
						try {
							$response = $SheerID->updateVerification($this->session->data['sheer_id_request_id'], $data);
						} catch (Exception $e) {}
					}
					
					if (!$response) {
						$response = $this->model_tool_sheer_id->verify($data, $orgId, $config);
					}
					
					$verified = $response->result;
					$this->applyResponseToSession($response, $cfgRep);
				} catch (Exception $e) {
					$this->session->data['verify_error'] = $this->language->get("error");
					$this->redirect($_SERVER['HTTP_REFERER']);
					return;
				}
			}
			
			if ($offer && $verified) {
				$this->applyCoupon($offer['coupon_code']);
				$this->redirectToCart($this->language->get("success"), "success");
				$this->redirect($this->url->link('checkout/cart'));
			} else {
				$this->session->data['verify_error'] = $this->language->get("error");
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}     
	}
	
	public function claim() {
		if (array_key_exists("requestId", $this->request->get)) {
			$requestId = $this->request->get['requestId'];
			
			$this->load->language('common/sheer_id');
			
			$this->load->model('tool/sheer_id');
			$SheerID = $this->model_tool_sheer_id->getService();
			
			if (!$SheerID) {
				$this->redirectToHomePage();
				return;
			}
			
			$resp = null;
			try {
				$resp = $SheerID->inquire($requestId);
			} catch (Exception $e) {
				return $this->redirectToCart($this->language->get("error_invalid_link"), "warning");
			}
			
			if ($resp->status == 'PENDING') {
				return $this->redirectToCart($this->language->get("notice_pending"), "attention");
			} else if ('COMPLETE' == $resp->status && !$resp->result) {
				return $this->redirectToCart($this->language->get("error_failure"), "warning");
			} else {
				$md = $resp->request->metadata;
				if (property_exists($md, "coupon_code") && !property_exists($md, "orderId")) {
					$this->applyResponseToSession($resp);
					$this->applyCoupon($md->coupon_code);
					return $this->redirectToCart($this->language->get("success"), "success");
				} else {
					return $this->redirectToCart($this->language->get("error_invalid_link"), "warning");
				}
			}
		} else {
			$this->redirectToHomePage();
			return;
		}
	}
	
	private function redirectToCart($message = null, $message_type = "attention") {
		if ($message) {
			$this->session->data["sheerid_message"] = array("message" => $message, "type" => $message_type);
		}
		return $this->redirect($this->url->link('checkout/cart'));
	}
	
	private function applyCoupon($coupon_code) {
		$this->session->data['coupon'] = $coupon_code;
	}
	
	private function applyResponseToSession($response, $cfgRep = null) {
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
		
		// will only be eligible to resubmit if no result last time
		if ($cfgRep != null) {
			$this->session->data['sheer_id_request_config'] = isset($response->result) ? null : $cfgRep;
		}
		
		return $verified;
	}
	
	private function redirectToHomePage() {
		$this->load->model('tool/sheer_id');
		$baseUrl = $this->model_tool_sheer_id->getSiteBaseUrl($this);
		$this->redirect($baseUrl);
	}
	
	private function getRequestConfigRepresentation($orgId, $config) {
		return serialize($config) . $orgId;
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