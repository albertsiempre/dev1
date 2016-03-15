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

	class subservice extends BaseController
	{
		public function index()
		{
			$data = array();
			$data['url_add'] = $this->generate_link(GROUP_CRM . '.form_subservice');
			$data['url_del'] = $this->generate_link(GROUP_CRM . '.delete_subservice');

			$rawPath = 'crm/get/list_subservices';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_subservices = new QIAPI($generatedParam);
			$dt_subservices->send();
			$dt_subservices = $dt_subservices->read();
			$data['subservices'] = $dt_subservices;

	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('actual_content', 'crm.subservice.main', $data);
		}

		public function form()
		{
			$this->layout = View::make('layout/popup', array(
				"title"	=> "Add Sub Service"
			))->nest('form_popup', 'crm.subservice.form', array(
				"url_form"		=> $this->generate_link(GROUP_CRM . ".submit_subservice")
			));
		}

		public function submit()
		{
			$extra_params = Input::all();
			$rawPath = 'crm/insert/subservice';
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

		public function del_subservice($id = null)
		{
			$status = false;
			$msg = null;
			if($id != null)
			{
				$extra_params = array(
					'id'	=> $id
				);
				$rawPath = 'crm/delete/subservice';
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