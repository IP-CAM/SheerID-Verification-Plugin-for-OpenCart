<?php 
/* 
  #file: admin/controller/total/sheer_id.php
  #name: Fixed Payment Type Charge Free Version
  #version: v1.0 free
  #tested: opencart Version 1.5.1.3
  
  modulo creato da fabiom7 - fabiome77@hotmail.it
  copyright fabiom7 2012
*/
?>
<?php 
class ControllerTotalSheerID extends Controller {
	private $error = array(); 
	 
	public function index() {
		$this->load->language('total/sheer_id');

		$this->document->setTitle($this->language->get('heading_title'));
				
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('sheer_id', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->load->model('tool/sheer_id');
		$affiliationTypes = $this->model_tool_sheer_id->getAffiliationTypes();
		
		foreach ($affiliationTypes as $a) {
			try {
				$this->data["label_$a"] = $this->language->get("label_$a");
			} catch (Exception $e) {
				$this->data["label_$a"] = $a;
			}
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_mode_production'] = $this->language->get('text_mode_production');
		$this->data['text_mode_sandbox'] = $this->language->get('text_mode_sandbox');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_access_token'] = $this->language->get('entry_access_token');
		$this->data['entry_mode'] = $this->language->get('entry_mode');
		$this->data['entry_coupons'] = $this->language->get('entry_coupons');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/sheer_id', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/sheer_id', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['sheer_id_status'])) {
			$this->data['sheer_id_status'] = $this->request->post['sheer_id_status'];
		} else {
			$this->data['sheer_id_status'] = $this->config->get('sheer_id_status');
		}

		if (isset($this->request->post['sheer_id_sort_order'])) {
			$this->data['sheer_id_sort_order'] = $this->request->post['sheer_id_sort_order'];
		} else {
			$this->data['sheer_id_sort_order'] = $this->config->get('sheer_id_sort_order');
		}
		
		if (isset($this->request->post['sheer_id_access_token'])) {
			$this->data['sheer_id_access_token'] = $this->request->post['sheer_id_access_token'];
		} else {
			$this->data['sheer_id_access_token'] = $this->config->get('sheer_id_access_token');
		}

		if (isset($this->request->post['sheer_id_base_url'])) {
			$this->data['sheer_id_base_url'] = $this->request->post['sheer_id_base_url'];
		} else {
			$this->data['sheer_id_base_url'] = $this->config->get('sheer_id_base_url');
		}
		
		$this->load->model('sale/coupon');
		
		$query = array(
			'sort'  => 'name',
			'order' => 'ASC',
			'start' => 1,
			'limit' => 1000
		);
		
		$this->data['coupons'] = $this->model_sale_coupon->getCoupons($query);
		
		$this->data['affiliation_types'] = $affiliationTypes;
		
		$maps = array();
		$settings = $this->model_setting_setting->getSetting('sheer_id');
		
		foreach ($settings as $k => $v) {
			$matches = array();
			if (strpos($k, "affiliation_types") === 0) {
				$maps[$k] = $v;
			}
		}
		
		$this->data['affiliation_type_mappings'] = $maps;
		
		$this->template = 'total/sheer_id.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/sheer_id')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>