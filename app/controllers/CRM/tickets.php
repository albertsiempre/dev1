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

	class tickets extends BaseController
	{
		public function index()
		{
			$data = array();
			$data['url_add'] = $this->generate_link(GROUP_CRM . '.form_ticket');

			$rawPath = 'crm/get/list_services';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_services = new QIAPI($generatedParam);
			$dt_services->send();
			$dt_services = $dt_services->read();

			$services = array();
			$services[0] = "Pilih Service ..";
			if($dt_services)
			{
				foreach($dt_services as $srv)
				{
					$services[$srv['id']] = isset($srv['name']) ? $srv['name'] : '';
				}
			}

			$rawPath = 'crm/get/list_subservices';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_subservices = new QIAPI($generatedParam);
			$dt_subservices->send();
			$dt_subservices = $dt_subservices->read();

			$subservices = array();
			$subservices[0] = "Pilih Sub Service ..";
			if($dt_subservices)
			{
				foreach($dt_subservices as $subsrv)
				{
					$subservices[$subsrv['id']] = isset($subsrv['name']) ? $subsrv['name'] : '';
				}
			}

			$rawPath = "crm/get/list_status_ticket_filter";
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_filterStatus = new QIAPI($generatedParam);
			$dt_filterStatus->send();
			$dt_filterStatus = $dt_filterStatus->read();

			$filterStatus = array();
			$filterStatus['-'] = 'All';
			if($dt_filterStatus)
			{
				foreach($dt_filterStatus as $name => $index)
				{
					$filterStatus[$index] = $name;
				}
			}

	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_CRM . '.list_ticket'),
	        		"form"		=> array(
	                    'category_id' => array(
	                        'type' => 'dropdown',
	                        'validation' => 'required',
	                        'data' => array(
	                        	'name' => 'category_id'
                        	),
	                        'label' => 'Service',
	                        'combo_options' => array(
	                        	"data"	=> $services,
	                        	'class_name' => '_my_category_combos'
                        	)
	                    ),
	                    'subcategory_id' => array(
	                        'type' => 'dropdown',
	                        'validation' => 'required',
	                        'data' => array(
	                        	'name' => 'subcategory_id'
                        	),
	                        'label' => 'Sub Service',
	                        'combo_options' => array(
	                        	"data"	=> $subservices,
	                        	'class_name' => '_my_subcategory_combos',
                        	)
	                    ),
	                    'ticket_status' => array(
	                    	'type'	=> 'dropdown',
	                    	'data'	=> array(
	                    		'name'	=> 'ticket_status'
                    		),
                    		'label'	=> "Tiket Status",
                    		'combo_options' => array(
                    			'data'	=> $filterStatus
                			)
                    	),
                    	'search_by' => array(
	                    	'type'	=> 'dropdown',
	                    	'data'	=> array(
	                    		'name'	=> 'search_by'
                    		),
                    		'label'	=> "Search By",
                    		'combo_options' => array(
                    			'data'	=> array(
                    				'user_name'	=> 'Username',
                    				'email'		=> 'Email',
                    				'name'		=> 'Full name'
                				)
                			)
                    	),
                    	'name' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'key',
		                        'autofocus' => 'autofocus'
		                    ),
		                    'validation' => 'required',
		                    'label' => 'Keyword'
		                ),
		                'start_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'start_date',
		                        'class' => '_start_date',
		                        "value"	=> ''
		                    ),
		                    'validation' => 'required',
		                    'label' => 'Start Date'
		                ),
		                'end_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'end_date',
		                        'class' => '_end_date',
		                        "value"	=> ''
		                    ),
		                    'validation' => 'required',
		                    'label' => 'End Date'
		                )
        			)
        		)
        	))->nest('actual_content', 'crm.ticket.main', $data);
		}

		function list_ticket()
		{
			$param = array();
			$rawPath = 'crm/get/list_tickets';
			$param["limit"] = \LIMIT_PAGING;

			$page = Input::get('page');
			if($page != null) $param["page"] = $page;

			$category_id = Input::get('category_id');
			if($category_id != null && $category_id != 0) $param['service_id'] = $category_id;

			$subcategory_id = Input::get('subcategory_id');
			if($subcategory_id != null && $subcategory_id != 0) $param['subservice_id'] = $subcategory_id;

			$ticket_status = Input::get('ticket_status');
			if($ticket_status != null && $ticket_status != '-')
			{
				$param['ticket_status_id'] = $ticket_status;
			}

			$search_by = Input::get('search_by');
			if($search_by != null) $param['search_by'] = $search_by;

			$keyword = Input::get('key');
			if($keyword != null) $param['keyword'] = $keyword;

			$start_date = Input::get('start_date');
			if($start_date != null) $param['start_date'] = $start_date;

			$end_date = Input::get('end_date');
			if($end_date != null) $param['end_date'] = $end_date;

			$dt_faq = new QIAPI($this->generateParam($param, $rawPath));
			$dt_faq->send();
			$dt_faq = $dt_faq->read();

			$result = isset($dt_faq['result']) ? $dt_faq['result'] : null;
			$pages = isset($dt_faq['pages']) ? $dt_faq['pages'] : null;

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => isset($pages['total_data']) ? $pages['total_data'] : count($result)))
							->nest('search_result', 'crm.ticket.list', array(
								'result'	=> $result,
								'url_edit'	=> $this->generate_link(GROUP_CRM . '.edit_ticket'),
								'url_del'	=> $this->generate_link(GROUP_CRM . '.del_ticket')
							));
		}

		function form()
		{
			$dt_sources = new QIAPI($this->generateParam(null, 'crm/get/list_ticket_sources'));
			$dt_sources->send();

			$dt_services = new QIAPI($this->generateParam(null, 'crm/get/list_services'));
			$dt_services->send();

			$dt_subservices = new QIAPI($this->generateParam(null, 'crm/get/list_subservices'));
			$dt_subservices->send();

			$this->layout = View::make('layout/popup', array(
				"title"	=> "Create Ticket"
			))->nest('form_popup', 'crm.ticket.form', array(
				"url_form"		=> $this->generate_link(GROUP_CRM . ".submit_ticket"),
				'sources'		=> $dt_sources->getStatus() ? $dt_sources->read() : null,
				'services'		=> $dt_services->getStatus() ? $dt_services->read() : null,
				'subservices'	=> $dt_subservices->getStatus() ? $dt_subservices->read() : null
			));
		}

		function submit()
		{
			$session = Session::get('qeon_session');
			$params = array();

			$source_id = Input::get('ticket_source_id');
			if($source_id != null && $source_id != "0") $params['ticket_source_id'] = $source_id;

			$phone = Input::get('phone');
			if($phone != null) $params['phone'] = $phone;

			$uname = Input::get('username');
			if($uname != null) $params['username'] = $uname;

			$email = Input::get('email');
			if($email != null) $params['email'] = $email;

			$service_id = Input::get('service_id');
			if($service_id != null && $service_id != "0") $params['service_id'] = $service_id;

			$subservice_id = Input::get('subservice_id');
			if($subservice_id != null && $subservice_id != "0") $params['subservice_id'] = $subservice_id;

			$admin_id = isset($session["_auth"]["admin"]) ? $session["_auth"]["admin"] : null;
			if($admin_id != null) $params['admin_id'] = $admin_id;

			$description = Input::get('description');
			if($description != null) $params['description'] = $description;

			// $fullname = isset($session['_name']['user']['full']) ? $session['_name']['user']['full'] : null;
			// if($fullname != null) $params['name'] = $fullname;

			// $username = isset($session['_name']['user']['name']) ? $session['_name']['user']['name'] : null;
			// if($username != null) $params['username'] = $username;

			// $params['user_agent'] = Useragent::agent_string();
	        // $params['ip'] = Request::getClientIp();

			$rawPath = "crm/insert/ticket";
			$insert_ticket = new QIAPI($this->generateParam($params, $rawPath));
			$insert_ticket->send();

			$this->layout = View::make('layout/blank', array(
				'content'	=> json_encode(array(
					'status'	=> $insert_ticket->getStatus(),
					'message'	=> $insert_ticket->read(),
					'params'	=> $params
				))
			));
		}

		function edit_form($id = null)
		{
			$dt_tickets = new QIAPI($this->generateParam(array(
				"ticket_id"	=> $id != null ? $id : 0
			), 'crm/get/ticket_detail'));
			$dt_tickets->send();

			$this->layout = View::make('crm.ticket.answer_form', array(
				"url_form"		=> $this->generate_link(GROUP_CRM . ".submit_answer"),
				'tickets'		=> $dt_tickets->getStatus() ? $dt_tickets->read() : null
			));
		}

		function submit_answer()
		{
			$session = Session::get('qeon_session');
			$params = array();

			$status_id = Input::get("status_id");
			if($status_id != null && $status_id != "-") $params['statusticket_id'] = $status_id;

			$description = Input::get('description');
			if($description != null) $params['message'] = $description;

			$is_visible = Input::get('is_visible');
			if($is_visible != null) $params['is_visible'] = $is_visible;

			$attachment = Input::get('attachment');
			if($attachment != null) $params['attachment'] = $attachment;

			$attachment_ext = Input::get('attachment_ext');
			if($attachment_ext != null) $params['attachment_ext'] = $attachment_ext;

			$ticket_id = Input::get('ticket_id');
			if($ticket_id != null) $params['ticket_id'] = $ticket_id;

			$admin_id = isset($session["_auth"]["admin"]) ? $session["_auth"]["admin"] : null;
			if($admin_id != null) $params['admin_id'] = $admin_id;

			$username = isset($session['_name']['user']['name']) ? $session['_name']['user']['name'] : null;
			if($username != null) $params['username'] = $username;

			$symkey = isset($session['_auth']['symkey']) ? $session['_auth']['symkey'] : null;
			if($symkey != null) $params['symkey'] = $symkey;

			$id_session = isset($session['_auth']['session']) ? $session['_auth']['session'] : null;
			if($symkey != null) $params['session'] = $id_session;

			// $rawAttachment = Input::file('rawAttachment');
			// if($rawAttachment != null)
			// {
			// 	$params['attachment'] = file_get_contents($rawAttachment);
			// 	$params['attachment_ext'] = $rawAttachment->getClientOriginalExtension();
			// }

			$rawPath = "crm/insert/reply";
			$insert_reply = new QIAPI($this->generateParam($params, $rawPath));
			$insert_reply->send();

			$this->layout = View::make('layout/blank', array(
				'content'	=> json_encode(array(
					'status'	=> $insert_reply->getStatus(),
					'message'	=> $insert_reply->read(),
					'params'	=> $params,
					"getMessage"=> $insert_reply->getMessage(),
					"getData"	=> $insert_reply->getData(),
					"getResponse" => $insert_reply->getResponse(),
					"getStatus"	=> $insert_reply->getStatus()
				))
			));
		}
	}

?>