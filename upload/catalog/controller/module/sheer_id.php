<?php  
class ControllerModuleSheerID extends Controller {
	protected function index($setting) {
		
		$this->load->model('tool/sheer_id');
		$coupon_id = $setting['coupon_id'];
		$config = $this->model_tool_sheer_id->getDiscount($coupon_id);
		
		if (!$config) {
			return;
		}
		
		$this->data['config'] = $config;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/sheer_id.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/sheer_id.tpl';
		} else {
			$this->template = 'default/template/module/sheer_id.tpl';
		}
		
		$this->render();
	}
}
?>