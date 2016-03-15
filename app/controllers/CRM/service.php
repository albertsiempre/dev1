<?php

	namespace QInterface\Controllers\CRM;

	use View;
	use Curl;
	use Session;
	use Request;
	use Useragent;
	use QInterface\Libs\QIAPI;
	use QInterface\Controllers\BaseController;
	use QInterface\Libs\PrivilegeChecker;
	use stdClass;
	use Validator;
	use Input;
	use Route;
	define('LIMIT_PAGING', '30');

	class service extends BaseController
	{
		public function index()
		{
			$data = array();
			$data['url_add'] = $this->generate_link(GROUP_CRM . '.form_service');
			$data['url_del'] = $this->generate_link(GROUP_CRM . '.delete_service');

			$rawPath = 'crm/get/list_services';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_services = new QIAPI($generatedParam);
			$dt_services->send();
			$dt_services = $dt_services->read();
			$data['services'] = $dt_services;

	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('actual_content', 'crm.service.main', $data);
		}

		public function form()
		{
			$rawPath = "crm/get/list_service_groups";
			$dt_group = new QIAPI($this->generateParam(null, $rawPath));
			$dt_group->send();

			$dt_games = new QIAPI($this->generateParam(null, "qinternal/get/game"));
			$dt_games->send();

			$this->layout = View::make('layout/popup', array(
				"title"	=> "Add Service"
			))->nest('form_popup', 'crm.service.form', array(
				"url_form"		=> $this->generate_link(GROUP_CRM . ".submit_service"),
				'groups'		=> $dt_group->getStatus() == true ? $dt_group->read() : null,
				"games"			=> $dt_games->getStatus() == true ? $dt_games->read() : null
			));
		}

		public function submit()
		{
			$extra_params = Input::all();
			$rawPath = 'crm/insert/service';
			$generate_param = $this->generateParam($extra_params, $rawPath);
			$insert = new QIAPI($generate_param);
			$insert->send();

			$this->layout = View::make('layout/blank', array(
				'content'	=> json_encode(array(
					'status'	=> $insert->getStatus(),
					'message'	=> $insert->read()
				))
			));
		}

		public function del_service($id = null)
		{
			$status = false;
			$msg = null;
			if($id != null)
			{
				$extra_params = array(
					'id'	=> $id
				);
				$rawPath = 'crm/delete/service';
				$generate_param = $this->generateParam($extra_params, $rawPath);
				$del = new QIAPI($generate_param);
				$del->send();

				$status = $del->getStatus();
				$msg = $del->read();
			} else {
				$status = false;
				$msg = "ID Tidak ditemukan";
			}

			$this->layout = View::make('layout/blank', array(
				'content'	=> json_encode(array(
					'status'	=> $status,
					'message'	=> $msg
				))
			));
		}
	}

?>