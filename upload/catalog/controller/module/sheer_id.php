<?php  
class ControllerModuleSheerID extends Controller {
	protected function index($setting) {
		
		$this->load->model('tool/sheer_id');
		$coupon_code = $setting['coupon_code'];
		$config = $this->model_tool_sheer_id->getOfferByCouponCode($coupon_code);
		
		if (!$config || $this->request->get['information_id'] != $config['information_id']) {
			return;
		}

		$this->data['config'] = $config;
		
		$org_type = strtolower($this->model_tool_sheer_id->getOrganizationType($config['affiliation_types']));

		$this->data['org_type'] = $org_type == 'university' ? $org_type : null;
		
		$this->load->language('module/sheer_id');
		
		$this->data["label_organization"] = $this->language->get("label_organization_$org_type");
		
		$fields = array("EMAIL", "FIRST_NAME", "MIDDLE_NAME", "LAST_NAME", "FULL_NAME", "BIRTH_DATE", "ID_NUMBER", "USERNAME", "POSTAL_CODE", "SSN", "SSN_LAST4", "STATUS_START_DATE");
		foreach ($fields as $f) {
			$this->data["field_$f"] = $this->language->get("field_$f");
		}
		
		$this->data["fields"] = $this->model_tool_sheer_id->getFields($config['affiliation_types']);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/sheer_id.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/sheer_id.tpl';
		} else {
			$this->template = 'default/template/module/sheer_id.tpl';
		}
		
		$this->response->setOutput($this->render());
	}
}
?>