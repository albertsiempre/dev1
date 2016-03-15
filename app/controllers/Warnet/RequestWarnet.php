<?php

	namespace QInterface\Controllers\Warnet;

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

	class RequestWarnet extends BaseController
	{
		
		public function index()
		{
	        $data = array();
	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_WARNET . '.list_request_warnet'),
	        		"form"		=> array(
	        			'name' => array(
	                        'type' => 'input',
	                        'data' => array(
	                            'name' => 'name',
	                            'autofocus' => 'autofocus'
	                        ),
	                        'validation' => 'required',
	                        'label' => 'Nama Warnet'
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
        	))->nest('actual_content', 'warnet.request_warnet.main', $data);
		}

		public function list_request_warnet()
		{
			$param = array();
			$rawPath = 'qwarnet/get/list_pending';
			$param["limit"] = \LIMIT_PAGING;

			$page = Input::get('page');
			if($page != null)
			{
				$param["page"] = $page;
			}

			$searchInput = Input::get("name");
			if($searchInput != null)
			{
				$param["name"] = $searchInput;
			}

			$start_date = Input::get('start_date');
			if($start_date != null) $param['start_date'] = $start_date;

			$end_date = Input::get('end_date');
			if($end_date != null) $param['end_date'] = $end_date;

			$dt_request = new QIAPI($this->generateParam($param, $rawPath));

			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => count($result)))
							->nest('search_result', 'warnet.request_warnet.list', array('result' => $result));
		}

	}

?>