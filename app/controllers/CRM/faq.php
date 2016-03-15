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

	class faq extends BaseController
	{
		public function __construct()
	    {
	        parent::__construct();
	        $this->addToBreadcrumb('FAQ', __CLASS__ . '@index');
	    }

		public function index()
		{
			$session = Session::get('qeon_session');
			$data = array();
			$data['url_add'] = $this->generate_link(GROUP_CRM . '.form_faq');

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

	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_CRM . '.list_faq'),
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
	                    'is_public' => array(
	                    	'type'	=> 'dropdown',
	                    	'data'	=> array(
	                    		'name'	=> 'is_public'
                    		),
                    		'label'	=> "Is Public",
                    		'combo_options' => array(
                    			'data'	=> array(
                    				'-'	=> 'All',
                    				'0'	=> 'Ya',
                    				'1'	=> 'Tidak'
                				)
                			)
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
		                ),
		                'name' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'key',
		                        'autofocus' => 'autofocus'
		                    ),
		                    'validation' => 'required',
		                    'label' => 'Keyword'
		                )
        			)
        		)
        	))->nest('actual_content', 'crm.faq.main', $data);
		}

		function form()
		{
			$data = array();
			$data['url_form'] = $this->generate_link(GROUP_CRM . ".addFAQ");

			$rawPath = 'crm/get/list_services';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_services = new QIAPI($generatedParam);
			$dt_services->send();
			$dt_services = $dt_services->read();

			$data['services'] = $dt_services;

			$rawPath = 'crm/get/list_subservices';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_subservices = new QIAPI($generatedParam);
			$dt_subservices->send();
			$dt_subservices = $dt_subservices->read();

			$data['subservices'] = $dt_subservices;

			$this->layout = View::make('layout/popup', array(
				"title"	=> "Add FAQ"
			))->nest('form_popup', 'crm.faq.form', $data);
		}

		function score_faq($fid = null, $score = null)
		{
			$session = Session::get('qeon_session');
			$params = array();

			$admin_id = isset($session['_auth']['admin']) ? $session['_auth']['admin'] : null;
			if($admin_id != null) $params['admin_id'] = $admin_id;

			if($fid != null) $params['faq_id'] = $fid;
			if($score != null) $params['score'] = $score;

			$rawPath = 'crm/insert/faq_score';

			$insert_score = new QIAPI($this->generateParam($params, $rawPath));
			$insert_score->send();

			$this->layout = View::make('layout/blank', array(
				'content'	=> json_encode(array(
					'status'	=> $insert_score->getStatus(),
					'message'	=> $insert_score->read()
				))
			));
		}

		function list_faq()
		{
			$session = Session::get('qeon_session');
			$param = array();
			$rawPath = 'crm/get/list_faqs';
			$param["limit"] = \LIMIT_PAGING;

			$admin_id = isset($session["_auth"]["admin"]) ? $session["_auth"]["admin"] : null;
			if($admin_id != null) $param['admin_id'] = $admin_id;

			$page = Input::get('page');
			if($page != null) $param["page"] = $page;

			$category_id = Input::get('category_id');
			if($category_id != null && $category_id != 0) $param['service_id'] = $category_id;

			$subcategory_id = Input::get('subcategory_id');
			if($subcategory_id != null && $subcategory_id != 0) $param['subservice_id'] = $subcategory_id;

			$is_public = Input::get('is_public');
			if($is_public != null && $is_public != '-')
			{
				switch($is_public)
				{
					case '0':
						$param['is_public'] = true;
						break;
					case '1':
						$param['is_public'] = false;
				}
			}

			$keyword = Input::get('key');
			if($keyword != null) $param['answer'] = $keyword;

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
							->nest('search_result', 'crm.faq.list', array(
								'result'	=> $result,
								'url_add'	=> $this->generate_link(GROUP_CRM . '.form_faq'),
								'url_del'	=> $this->generate_link(GROUP_CRM . '.del_faq'),
								'url_score'	=> $this->generate_link(GROUP_CRM . '.faq_score')
							));
		}

		function del_faq($id = null)
		{
			$status = false;
			$msg = null;
			if($id != null)
			{
				$extra_params = array(
					'id'	=> $id
				);
				$rawPath = 'crm/delete/faq';
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

		function submit_faq()
		{
			$session = Session::get('qeon_session');
			$param = array();
			$rawPath = 'crm/insert/faq';
			$param['limit'] = \LIMIT_PAGING;

			$service_id = Input::get('category_id');
			if($service_id != null) $param['service_id'] = $service_id;

			$subservice_id = Input::get('subcategory_id');
			if($subservice_id != null) $param['subservice_id'] = $subservice_id;

			$question = Input::get('question');
			if($question != null) $param['question'] = $question;

			$is_public = Input::get('is_public');
			$param['is_public'] = $is_public != null ? true : false;

			$answer = Input::get('description');
			if($answer != null) $param['answer'] = $answer;

			if(isset($session["_auth"]["admin"])) $param["user_id"] = $session["_auth"]["admin"];

			$order = Input::get('order');
			if($order != null) $param['order'] = $order;

			$faq_id = Input::get('faq_id');
			if($faq_id != null) $param['id'] = $faq_id;

			$insert_faq = new QIAPI($this->generateParam($param, $rawPath));
			$insert_faq->send();

			$this->layout = View::make('layout/blank', array(
				'content'	=> json_encode(array(
					'status'	=> $insert_faq->getStatus(),
					'message'	=> $insert_faq->read()
				))
			));
		}
	}

?>