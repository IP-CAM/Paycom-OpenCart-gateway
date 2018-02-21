<?php
class ControllerPaymentPayme extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/payme');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payme', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_security'] = $this->language->get('entry_security');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

  		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

 		if (isset($this->error['security'])) {
			$this->data['error_security'] = $this->error['security'];
		} else {
			$this->data['error_security'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/payme', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/payme', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['payme_merchant'])) {
			$this->data['payme_merchant'] = $this->request->post['payme_merchant'];
		} else {
			$this->data['payme_merchant'] = $this->config->get('payme_merchant');
		}

		if (isset($this->request->post['payme_security'])) {
			$this->data['payme_security'] = $this->request->post['payme_security'];
		} else {
			$this->data['payme_security'] = $this->config->get('payme_security');
		}
		
		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/payme/callback';
		
		if (isset($this->request->post['payme_total'])) {
			$this->data['payme_total'] = $this->request->post['payme_total'];
		} else {
			$this->data['payme_total'] = $this->config->get('payme_total'); 
		} 
			
		if (isset($this->request->post['payme_test'])) {
			$this->data['payme_test'] = $this->request->post['payme_test'];
		} else {
			$this->data['payme_test'] = $this->config->get('payme_test');
		}

		
		if (isset($this->request->post['payme_order_status_id'])) {
			$this->data['payme_order_status_id'] = $this->request->post['payme_order_status_id'];
		} else {
			$this->data['payme_order_status_id'] = $this->config->get('payme_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payme_geo_zone_id'])) {
			$this->data['payme_geo_zone_id'] = $this->request->post['payme_geo_zone_id'];
		} else {
			$this->data['payme_geo_zone_id'] = $this->config->get('payme_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payme_status'])) {
			$this->data['payme_status'] = $this->request->post['payme_status'];
		} else {
			$this->data['payme_status'] = $this->config->get('payme_status');
		}
		
		if (isset($this->request->post['payme_sort_order'])) {
			$this->data['payme_sort_order'] = $this->request->post['payme_sort_order'];
		} else {
			$this->data['payme_sort_order'] = $this->config->get('payme_sort_order');
		}

		$this->template = 'payment/payme.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['payme_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['payme_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
