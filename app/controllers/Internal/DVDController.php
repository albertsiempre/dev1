<?php

	namespace QInterface\Controllers\Internal;

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

	class DVDController extends BaseController
	{
		public function Home()
		{			
			$this->layout->title = "Qeon Interactive";
			$this->layout->content = View::make('layout.content')->nest('actual_content', 'qinternal.home');
		}
                
		public function FreeDVD()
		{
			$data 			= array();
			$param_get		= Input::all();
			$val_username	= isset($param_get['name']) ? $param_get['name'] : '';
			$val_game		= isset($param_get['category_id']) ? $param_get['category_id'] : '';
			$val_prov		= isset($param_get['province_id']) ? $param_get['province_id'] : '';
			$val_city		= isset($param_get['city_id']) ? $param_get['city_id'] : '';
			$val_start_date = isset($param_get['start_date']) ? $param_get['start_date'] : '';
			$val_end_date   = isset($param_get['end_date']) ? $param_get['end_date'] : '';
			$val_limit   	= isset($param_get['limit']) ? $param_get['limit'] : '';
			
			$url_checkout 	= $this->generate_link(GROUP_INTERNAL . '.checkout');
			$data['url_checkout'] = $url_checkout;

			$rawPath = 'qinternal/get/province';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_province = new QIAPI($generatedParam);
			$dt_province->send();
			$dt_province = $dt_province->read();

			$provinces = array();
			$provinces[''] = "Pilih Provinsi ..";
			if($dt_province)
			{
				foreach($dt_province as $prof)
				{
					$provinces[$prof['id']] = isset($prof['name']) ? $prof['name'] : '';
				}
			}

			$city = array();
			if($val_city != '' && $val_prov != ''){
				$rawPath = 'qinternal/get/city';
				$extra_params = array(
	            	'province_id'	=> $val_prov
	        	);
	        	$generatedParam = $this->generateParam($extra_params, $rawPath);
				$dt_city = new QIAPI($generatedParam);
				$dt_city->send();
				$dt_city = $dt_city->read();

				$city[''] = "Pilih Kota ..";
				if($dt_city)
				{
					foreach($dt_city as $val)
					{
						$city[$val['id']] = isset($val['name']) ? $val['name'] : '';
					}
				}
			}else{
				$city[''] = "Pilih Kota ..";
			}

			$rawPath = 'qinternal/get/game';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_games = new QIAPI($generatedParam);
			$dt_games->send();
			$dt_games = $dt_games->read();

			$games = array();
			$games[''] = "Pilih Game ..";
			if($dt_games)
			{
				foreach($dt_games as $game)
				{
					$games[$game['id']] = isset($game['name']) ? $game['name'] : '';
				}
			}			

			$limit = array();
			$limit['30'] 	= "30";
			$limit['50'] 	= "50";
			$limit['100'] 	= "100";
			
			$this->layout->title = "Qeon Interactive";

			$forms = array(
    			'name' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'name',
                        'autofocus' => 'autofocus',
                        'value' => $val_username
                    ),
                    'validation' => 'required',
                    'label' => 'Username / Nama Lengkap'
                ),
                'category_id' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                    	'name' => 'category_id'
                	),
                    'label' => 'Game',
                    'combo_options' => array(
                    	"data"	=> $games,
                    	'class_name' => '_my_games_combos',
                    	'selected' => $val_game

                	)
                ),
                'province_id' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                    	'name' => 'province_id'
                	),
                    'label' => 'Provinsi',
                    'combo_options' => array(
                    	"data"	=> $provinces,
                    	'class_name' => '_my_province_combos',
                    	'data_url' => $this->generate_link(GROUP_INTERNAL . '.get_city'),
                    	'data_target' => '_my_city_combos',
                    	'selected' => $val_prov
                	)
                ),
                'city_id' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                    	'name' => 'city_id'
                	),
                    'label' => 'Kota',
                    'combo_options' => array(
                    	"data"	=> $city,
                    	'class_name' => '_my_city_combos',
                    	'disable' => true,
                    	'selected' => $val_city
                	)
                ),
                'start_date' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'start_date',
                        'class' => '_start_date',
                        'value' => $val_start_date
                    ),
                    'validation' => 'required',
                    'label' => 'Start Date'
                ),
                'end_date' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'end_date',
                        'class' => '_end_date',
                        'value' => $val_end_date
                    ),
                    'validation' => 'required',
                    'label' => 'End Date'
                ),
                'limit' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                    	'name' => 'limit'
                	),
                    'label' => 'Limit',
                    'combo_options' => array(
                    	"data"	=> $limit,
                    	'class_name' => '_my_limit_combos',
                    	'selected' => $val_limit
                	)
                )
			);

			$this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_INTERNAL . '.list_dvd'),
	        		"form"		=> $forms
        		)
        	))->nest('actual_content', 'qinternal.free_dvd.main', $data);
		}

		public function FreeDVDList()
		{
			$param = array();
			$rawPath = 'qinternal/get/all_dvd_request';
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
			
			$searchInputGame = Input::get("category_id");
			if($searchInputGame != null && $searchInputGame != 0)
			{
				$param["category_id"] = $searchInputGame;
			}

			$searchInputprovince_id = Input::get("province_id");
			if($searchInputprovince_id != null && $searchInputprovince_id != 0)
			{
				$param["province_id"] = $searchInputprovince_id;
			}

			$searchInputcity_id = Input::get("city_id");
			if($searchInputcity_id != null && $searchInputcity_id != 0)
			{
				$param["city_id"] = $searchInputcity_id;
			}
			
			$searchInputstart_date = Input::get("start_date");
			if($searchInputstart_date != null)
			{
				$param["start_date"] = $searchInputstart_date;
			}

			$searchInputend_date = Input::get("end_date");
			if($searchInputend_date != null)
			{
				$param["end_date"] = $searchInputend_date;
			}			

			$searchInputLimit = Input::get("limit");
			if($searchInputLimit != null)
			{
				$param["limit"] = $searchInputLimit;
			}

			$generatedParam = $this->generateParam($param, $rawPath);
			$dt_request = new QIAPI($generatedParam);
			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;
			$total_checkout = isset($dt_request['total_checkout']) ? $dt_request['total_checkout'] : '0';

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => $pages['total_data']))
							->nest('search_result', 'qinternal.free_dvd.list', array('result' => $result, 'total_checkout' => $total_checkout));
		}

		public function detailRequest()
		{
			$param = array(
				"user_id"	=> Input::get("id") != null ? Input::get("id") : "",
				"is_active"	=> FALSE
			);

			$rawPath = 'qinternal/get/dvd_request';
			$generatedParam = $this->generateParam($param, $rawPath);
			$dt_detail = new QIAPI($generatedParam);
			$dt_detail->send();
			$dt_detail = $dt_detail->getData();
			$this->layout = View::make('qinternal.free_dvd.detail', array(
				"result" => $dt_detail,
				"url"	 => $this->generate_link(GROUP_INTERNAL . ".update_note")
			));
		}

		public function updateNote()
		{ 
			$req_id = Input::get("req_id");
			$note = Input::get("note");

			$extra_params = array(
				"id"	=> $req_id,
				"note"	=> $note
			);

			$rawPath = 'qinternal/update/note';
			$generatedParam = $this->generateParam($extra_params, $rawPath);
			$dt_update = new QIAPI($generatedParam);
			$dt_update->send();
			$this->layout = View::make('layout/blank', array(
				"content"	=> json_encode(array(
					"status"	=> $dt_update->getStatus(),
					"message"	=> $dt_update->read()
				))
			));
		}

		public function sendRequest()
		{
			$extra_params = array(
				"user_id"	=> Input::get("id") != null ? Input::get("id") : "",
				"is_active"	=> TRUE
			);

			$rawPath = 'qinternal/get/dvd_request';
			$generatedParam = $this->generateParam($extra_params, $rawPath);
			$dt_detail = new QIAPI($generatedParam);
			$dt_detail->send();
			$dt_detail = $dt_detail->read();
			$this->layout = View::make('qinternal.free_dvd.send', array(
				"result" => $dt_detail,
				"url_action"	=> $this->generate_link(GROUP_INTERNAL . '.submit_request')
			));
		}

		public function submitRequest()
		{
			$user_id = Input::get("user_id");
			$detail_id = Input::get("detail_id");
			$note = Input::get("note");

			$extra_params = array(
				"user_id"	=> $user_id,
				"detail_id"	=> $detail_id,
				"note"		=> $note
			);

			$rawPath = 'qinternal/insert/dvd_request';
			$generatedParam = $this->generateParam($extra_params, $rawPath);
			$dt_update = new QIAPI($generatedParam);
			$dt_update->send();

			$this->layout = View::make('layout/blank', array(
				"content"	=> json_encode(array(
					"status"	=> $dt_update->getStatus(),
					"message"	=> $dt_update->read()
				))
			));
		}

		public function checkout()
		{
			$rawPath = 'qinternal/insert/checkout';
			$generatedParam = $this->generateParam(array(), $rawPath);
			$dt_checkout = new QIAPI($generatedParam);
			$dt_checkout->send();

			$url_checkout = $this->generate_link(GROUP_INTERNAL . ".submit_checkout");
			$url_print_checkout = $this->generate_link(GROUP_INTERNAL . ".print_checkout");

			$this->layout = VIew::make('qinternal.free_dvd.checkout', array(
				'html'	=> $dt_checkout->read(),
				'url_checkout' => $url_checkout,
				'url_print_checkout' => $url_print_checkout
			));
		}

		public function print_checkout($is_box = null)
		{
			$data = array();
			if(isset($is_box) && $is_box != null){
				$data['is_box']	= 1;
			}			
			$rawPath = 'qinternal/insert/checkout';
			$generatedParam = $this->generateParam($data, $rawPath);
			$dt_checkout = new QIAPI($generatedParam);
			$dt_checkout->send();

			$url_checkout = $this->generate_link(GROUP_INTERNAL . ".submit_checkout");

			$this->layout = VIew::make('qinternal.free_dvd.print_checkout', array(
				'html'	=> $dt_checkout->read(),
				'url_checkout' => $url_checkout
			));
		}

		public function submit_checkout()
		{
			$rawPath = 'qinternal/insert/checkout_confirm';
			$generatedParam = $this->generateParam(array(), $rawPath);
			$dt_checkout = new QIAPI($generatedParam);
			$dt_checkout->send();

			$this->layout = View::make('layout/blank', array(
				"content"	=> json_encode(array(
					"status"	=> $dt_checkout->getStatus(),
					"message"	=> $dt_checkout->read()
				))
			));
		}
	}

?>