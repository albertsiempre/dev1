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

	class report extends BaseController
	{
		function faq()
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

			$this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_CRM . '.report_list_faq'),
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
		                'periode' => array(
		                    'type' => 'dropdown',
		                    'validation' => 'required',
		                    'data' => array(
		                    	'name' => 'periode'
		                	),
		                    'label' => 'Periode',
		                    'combo_options' => array(
		                    	"data"	=> array(
		                    		'daily' => 'Daily',
		                    		'weekly' => 'Weekly',
		                    		'monthly' => 'Monthly'
		                		),
		                    	'class_name' => '_my_periode_combos'
		                	)
		                ),
		                'start_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'start_date',
		                        'class' => '_start_date',
		                        "value"	=> date("Y-m-d", strtotime("yesterday"))
		                    ),
		                    'validation' => 'required',
		                    'label' => 'Start Date'
		                ),
		                'end_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'end_date',
		                        'class' => '_end_date',
		                        "value"	=> date("Y-m-d")
		                    ),
		                    'validation' => 'required',
		                    'label' => 'End Date'
		                )
        			)
        		)
        	))->nest('actual_content', 'crm.report.faq.main', $data);
		}

		function list_faq()
		{
			$param = array();
			$rawPath = 'crm/get/report/faq_score';
			$param["limit"] = \LIMIT_PAGING;
			$inputs = Input::all();

			$page = Input::get('page');
			if($page != null) $param["page"] = $page;

			$category_id = Input::get('category_id');
			if($category_id != null && $category_id != 0) $param['service_id'] = $category_id;

			$subcategory_id = Input::get('subcategory_id');
			if($subcategory_id != null && $subcategory_id != 0) $param['subservice_id'] = $subcategory_id;

			$start_date = Input::get('start_date');
			if($start_date != null) $param['start_date'] = $start_date;

			$end_date = Input::get('end_date');
			if($end_date != null) $param['end_date'] = $end_date;

			$start_date = isset($inputs['start_date']) ? $inputs['start_date'] :'';
			$end_date = isset($inputs['end_date']) ? $inputs['end_date'] : '';
			$periode = isset($inputs['periode']) ? $inputs['periode'] : '';

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

			if($periode == "monthly")
			{
				$start_month = $inputs['start_month'];
				$start_year = $inputs['start_year'];
				$end_month = $inputs['end_month'];
				$end_year = $inputs['end_year'];

				$start_date = $start_year . "-" . $start_month . "-01";
				$end_date = $end_year . "-" . $end_month . "-01";
			}

			if($start_date != "" && $end_date != "")
			{
				$param['start_date'] = date("Y-m-d", strtotime($start_date));
				$param['end_date'] = date("Y-m-d", strtotime($end_date));
				$param['interval'] = $periode;

				$dt_report = new QIAPI($this->generateParam($param, $rawPath));
				$dt_report->send();
				$dt_report = $dt_report->read();
			} else {
				$dt_report = array();
			}

			$result = $dt_report;
			$pages = isset($dt_report['pages']) ? $dt_report['pages'] : count($dt_report);

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => 0))
							->nest('search_result', 'crm.report.faq.list', array(
								'result'	=> $result
							));

			//print_r($dt_report);
		}

		/**********************/
		/* REPORT TICKET TIME */
		/**********************/

		function ticket_time()
		{
			$data = array();

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
	        		"action"	=> $this->generate_link(GROUP_CRM . '.report_list_ticket_time'),
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
		                'periode' => array(
		                    'type' => 'dropdown',
		                    'validation' => 'required',
		                    'data' => array(
		                    	'name' => 'periode'
		                	),
		                    'label' => 'Periode',
		                    'combo_options' => array(
		                    	"data"	=> array(
		                    		'daily' => 'Daily',
		                    		'weekly' => 'Weekly',
		                    		'monthly' => 'Monthly'
		                		),
		                    	'class_name' => '_my_periode_combos'
		                	)
		                ),
		                'start_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'start_date',
		                        'class' => '_start_date',
		                        "value"	=> date("Y-m-d", strtotime("yesterday"))
		                    ),
		                    'validation' => 'required',
		                    'label' => 'Start Date'
		                ),
		                'end_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'end_date',
		                        'class' => '_end_date',
		                        "value"	=> date("Y-m-d")
		                    ),
		                    'validation' => 'required',
		                    'label' => 'End Date'
		                )
        			)
        		)
        	))->nest('actual_content', 'crm.report.ticket_time.main', $data);
		}

		function list_ticket_time()
		{
			$param = array();
			$rawPath = 'crm/get/report/ticket_time';
			$param["limit"] = \LIMIT_PAGING;
			$inputs = Input::all();

			$page = Input::get('page');
			if($page != null) $param["page"] = $page;

			$category_id = Input::get('category_id');
			if($category_id != null && $category_id != 0) $param['service_id'] = $category_id;

			$subcategory_id = Input::get('subcategory_id');
			if($subcategory_id != null && $subcategory_id != 0) $param['subservice_id'] = $subcategory_id;

			$start_date = Input::get('start_date');
			if($start_date != null) $param['start_date'] = $start_date;

			$end_date = Input::get('end_date');
			if($end_date != null) $param['end_date'] = $end_date;

			$start_date = isset($inputs['start_date']) ? $inputs['start_date'] :'';
			$end_date = isset($inputs['end_date']) ? $inputs['end_date'] : '';
			$periode = isset($inputs['periode']) ? $inputs['periode'] : '';

			if($periode == "monthly")
			{
				$start_month = $inputs['start_month'];
				$start_year = $inputs['start_year'];
				$end_month = $inputs['end_month'];
				$end_year = $inputs['end_year'];

				$start_date = $start_year . "-" . $start_month . "-01";
				$end_date = $end_year . "-" . $end_month . "-01";
			}

			if($start_date != "" && $end_date != "")
			{
				$param['start_date'] = date("Y-m-d", strtotime($start_date));
				$param['end_date'] = date("Y-m-d", strtotime($end_date));
				$param['interval'] = $periode;

				$dt_report = new QIAPI($this->generateParam($param, $rawPath));
				$dt_report->send();
				$dt_report = $dt_report->read();
			} else {
				$dt_report = array();
			}

			$result = $dt_report;
			$pages = isset($dt_report['pages']) ? $dt_report['pages'] : count($dt_report);

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => 0))
							->nest('search_result', 'crm.report.ticket_time.list', array(
								'result'	=> $result
							));

			//print_r($dt_report);
		}

		/*******************/
		/* REPORT FEEDBACK */
		/*******************/

		function feedback()
		{
			$data = array();

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

			$this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_CRM . '.report_list_feedback'),
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
		                'periode' => array(
		                    'type' => 'dropdown',
		                    'validation' => 'required',
		                    'data' => array(
		                    	'name' => 'periode'
		                	),
		                    'label' => 'Periode',
		                    'combo_options' => array(
		                    	"data"	=> array(
		                    		'daily' => 'Daily',
		                    		'weekly' => 'Weekly',
		                    		'monthly' => 'Monthly'
		                		),
		                    	'class_name' => '_my_periode_combos'
		                	)
		                ),
		                'start_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'start_date',
		                        'class' => '_start_date',
		                        "value"	=> date("Y-m-d", strtotime("yesterday"))
		                    ),
		                    'validation' => 'required',
		                    'label' => 'Start Date'
		                ),
		                'end_date' => array(
		                    'type' => 'input',
		                    'data' => array(
		                        'name' => 'end_date',
		                        'class' => '_end_date',
		                        "value"	=> date("Y-m-d")
		                    ),
		                    'validation' => 'required',
		                    'label' => 'End Date'
		                )
        			)
        		)
        	))->nest('actual_content', 'crm.report.feedback.main', $data);
		}

		function list_feedback()
		{
			$param = array();
			$rawPath = 'crm/get/report/feedback';
			$param["limit"] = \LIMIT_PAGING;
			$inputs = Input::all();

			$page = Input::get('page');
			if($page != null) $param["page"] = $page;

			$category_id = Input::get('category_id');
			if($category_id != null && $category_id != 0) $param['service_id'] = $category_id;

			$start_date = Input::get('start_date');
			if($start_date != null) $param['start_date'] = $start_date;

			$end_date = Input::get('end_date');
			if($end_date != null) $param['end_date'] = $end_date;

			$start_date = isset($inputs['start_date']) ? $inputs['start_date'] :'';
			$end_date = isset($inputs['end_date']) ? $inputs['end_date'] : '';
			$periode = isset($inputs['periode']) ? $inputs['periode'] : '';

			if($periode == "monthly")
			{
				$start_month = $inputs['start_month'];
				$start_year = $inputs['start_year'];
				$end_month = $inputs['end_month'];
				$end_year = $inputs['end_year'];

				$start_date = $start_year . "-" . $start_month . "-01";
				$end_date = $end_year . "-" . $end_month . "-01";
			}

			if($start_date != "" && $end_date != "")
			{
				$param['start_date'] = date("Y-m-d", strtotime($start_date));
				$param['end_date'] = date("Y-m-d", strtotime($end_date));
				$param['interval'] = $periode;

				$dt_report = new QIAPI($this->generateParam($param, $rawPath));
				$dt_report->send();
				$dt_report = $dt_report->read();
			} else {
				$dt_report = array();
			}

			$result = $dt_report;
			$pages = isset($dt_report['pages']) ? $dt_report['pages'] : count($dt_report);

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => 0))
							->nest('search_result', 'crm.report.feedback.list', array(
								'result'	=> $result
							));

			//print_r($dt_report);
		}
	}

?>