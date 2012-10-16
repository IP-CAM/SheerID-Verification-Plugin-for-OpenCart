<?php  
class ControllerCommonSheerID extends Controller {
	public function index() {
		if (isset($this->request->post['coupon_code'])) {
			
			$this->load->model('tool/sheer_id');
			$offer = $this->model_tool_sheer_id->getOfferByCouponCode($this->request->post['coupon_code']);
			
			if ($offer) {
				$config = array("affiliationTypes" => implode(",", $offer["affiliation_types"]));
				$orgId = $this->request->post['organizationId'];
				$data = array();
				
				$fields = $this->model_tool_sheer_id->getFields($offer["affiliation_types"]);
				foreach ($fields as $f) {
					$data[$f] = $this->getPostData($f);
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
					$this->session->data['verify_error'] = $e->getMessage();
					$this->redirect($_SERVER['HTTP_REFERER']);
					return;
				}
			}
			
			if ($offer && $verified) {
				$this->session->data['coupon'] = $offer['code'];
				$this->session->data['success'] = 'Success: Your coupon discount has been applied!';
				$this->redirect($this->url->link('checkout/cart'));
			} else {
				var_dump($response);
				$this->session->data['verify_error'] = count($response->errors) ? $response->errors[0]->message : "Something bad happened";
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