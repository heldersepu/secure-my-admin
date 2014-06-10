<?php
class ControllerModuleSecureurl extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('module/secureurl');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST')   && $this->validate() ) {

			
			$this->model_setting_setting->editSetting('secureurl', $this->request->post);		
			
			//$this->cache->delete('product');
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			//$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');

		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		/*
		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = array();
		}
		*/
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/secureurl', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/secureurl', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		//$this->data['modules'] = array();
		$this->data['modules'] = $this->model_setting_setting->getSetting('secureurl');
		$t = $this->model_setting_setting->getSetting('secureurl');
	//var_dump($t);
		if (isset($this->request->post['secureurl_module'])) {
			$this->data['modules'] = $t ; //$this->request->post['secureurl'];
		} elseif ($this->config->get('secureurl_module')) { 
			$this->data['modules'] = $t;
		}	

			
				
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/secureurl.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/secureurl')) {	
			
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// validate it only when the status is enabled.		
		if ($this->request->post['secure_status'] == 1 ) {
		

			if (isset($this->request->post['secure_key'])) { 
				
				if(strlen(trim($this->request->post['secure_key'])) <= 0 ) {
					$this->error['warning'] = " The Secure Key is empty ";
				}else{
					
					if(preg_match('/[^a-zA-Z0-9]/', $this->request->post['secure_key'])){
						
						$this->error['warning'] = " The Secure Key cant contain symbols";
					}
				}

				
			}


			if (isset($this->request->post['secure_value'])) { 
				if(strlen(trim($this->request->post['secure_value'])) <= 0 ) {
					$this->error['warning'] = " The Secure value is empty " ;
				}else{
					
					if(preg_match('/[^a-zA-Z0-9]/', $this->request->post['secure_value'])){
						
						$this->error['warning'] = " The Secure value cant contain symbols";
					}
				}
			}

		}
		
			
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>