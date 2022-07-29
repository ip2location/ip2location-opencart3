<?php

class ControllerStartupIP2LocationRedirector extends Controller
{
	public function index()
	{
		try {
			$this->load->model('tool/ip2location_redirector');
		} catch (Exception $e) {
			$this->log->write($e->getMessage());
			return;
		}

		if ($this->config->get('module_ip2location_redirector_status') != '1') {
			return;
		}

		if ($rule = $this->model_tool_ip2location_redirector->getRule($this->request->server['REQUEST_URI'])) {
			if ($rule['code'] == '404') {
				$this->request->get['route'] = 'error/not_found';
			} else {
				$this->response->redirect($rule['to'], $rule['code']);
			}
		}
	}
}
