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

	class ticketsource extends BaseController
	{
		public function index()
		{
			$data = array();
			$data['url_add'] = $this->generate_link(GROUP_CRM . '.form_ticket_source');
			$data['url_del'] = $this->generate_link(GROUP_CRM . '.delete_ticket_source');

			$rawPath = 'crm/get/list_ticket_sources';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_ticket_sources = new QIAPI($generatedParam);
			$dt_ticket_sources->send();
			$dt_ticket_sources = $dt_ticket_sources->read();
			$data['ticketsources'] = $dt_ticket_sources;

	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('actual_content', 'crm.ticketsource.main', $data);
		}

		public function form()
		{
			$this->layout = View::make('layout/popup', array(
				"title"	=> "Add Ticket Source"
			))->nest('form_popup', 'crm.ticketsource.form', array(
				"url_form"		=> $this->generate_link(GROUP_CRM . ".submit_ticket_source")
			));
		}

		public function submit()
		{
			$extra_params = Input::all();
			$rawPath = 'crm/insert/ticket_source';
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

		public function del_ticket_source($id = null)
		{
			$status = false;
			$msg = null;
			if($id != null)
			{
				$extra_params = array(
					'id'	=> $id
				);
				$rawPath = 'crm/delete/ticket_source';
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