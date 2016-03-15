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

	class homeController extends BaseController
	{
		function dashboard()
		{
			$this->layout->title = "Qeon Interactive";
			$this->layout->content = View::make('layout.content')->nest('actual_content', 'crm.dashboard.home');
		}

		public function index()
		{
	        $data = array();
	        $method = "formAdd";
	        $privilegeChecker = new \QInterface\Libs\PrivilegeChecker(null, null, null, $method);
	        $isSales = $privilegeChecker->isSales();
	        $data["isSales"] = $isSales;

	        if($privilegeChecker->isAuthorized())
	        {
	        	$data['add'] = true;
	        	$data['url_add'] = $this->generate_link(GROUP_WARNET . '.add');
	        }

	        $rawPath = 'qinternal/get/province';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_province = new QIAPI($generatedParam);
			$dt_province->send();
			$dt_province = $dt_province->read();

			$provinces = array();
			$provinces[0] = "Pilih Provinsi ..";
			if($dt_province)
			{
				foreach($dt_province as $prof)
				{
					$provinces[$prof['id']] = isset($prof['name']) ? $prof['name'] : '';
				}
			}

			// $rawPath = 'qinternal/get/city';
			// $generatedParam = $this->generateParam(array(
			// 	"province_id" => $dt_province[0]['id']
			// ), $rawPath);
			// $dt_city = new QIAPI($generatedParam);
			// $dt_city->send();
			// $dt_city = $dt_city->read();

			$city = array();
			$city[0] = "Pilih Kota ..";
			// if($dt_city)
			// {
			// 	foreach($dt_city as $ct)
			// 	{
			// 		$city[$ct['id']] = isset($ct['name']) ? $ct['name'] : '';
			// 	}
			// }

			$forms = array(
    			'name' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'name',
                        'autofocus' => 'autofocus'
                    ),
                    'validation' => 'required',
                    'label' => 'Nama Warnet'
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
                    	'data_url' => $this->generate_link(GROUP_WARNET . '.get_city'),
                    	'data_target' => '_my_city_combos'
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
                    	'disable' => true
                	)
                )
			);

			if(!$isSales)
			{
				$forms = array_merge($forms, array(
	                'is_qash' => array(
	                	'type'	=> 'checkbox',
	                	'data' => array(
	                		'name'	=> 'is_qash',
	                		'value'	=> '1'
	            		),
	            		'label'	=> 'Flag Qash'
	            	),
	            	'is_dvd' => array(
	            		'type'	=> 'checkbox',
	            		'data'	=> array(
	            			'name'	=> 'is_dvd',
	            			'value'	=> '1'
	        			),
	        			'label'	=> 'Flag Free DVD'
	        		),
	        		'is_play_bonus' => array(
	        			'type'	=> 'checkbox',
	        			'data'	=> array(
	        				'name'	=> 'is_play_bonus',
	        				'value'	=> '1'
	    				),
	    				'label'	=> 'Flag Play Bonus'
	    			))
    			);
			}

	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_WARNET . '.list_warnet'),
	        		"form"		=> $forms,
	        		"doInit"	=> $isSales ? false : true
        		)
        	))->nest('actual_content', 'warnet.main', $data);
		}

		public function edit_form($warnet_id = null)
		{
			$privilegeChecker = new \QInterface\Libs\PrivilegeChecker();
	        $isSales = $privilegeChecker->isSales();
			$param = array();
			$rawPath = "qwarnet/get/warnet_single";
			$param['warnet_id'] = $warnet_id != null ? $warnet_id : '';

			$dt_single = new QIAPI($this->generateParam($param, $rawPath));
			$dt_single->send();
			$dt_single = $dt_single->read();

			$rawPath = 'qinternal/get/city';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_city = new QIAPI($generatedParam);
			$dt_city->send();
			$data["city"] = $dt_city->read();

			$can_add_flag_qash = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_flag_qash");
			if($can_add_flag_qash->isAuthorized())
			{
				$data["can_add_flag_qash"] = true;
			}

			$can_add_flag_dvd = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_flag_dvd");
			if($can_add_flag_dvd->isAuthorized())
			{
				$data["can_add_flag_dvd"] = true;
			}

			$can_add_flag_play_bonus = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_flag_play_bonus");
			if($can_add_flag_play_bonus->isAuthorized())
			{
				$data["can_add_flag_play_bonus"] = true;
			}

			$can_add_phone = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_phone");
			if($can_add_phone->isAuthorized())
			{
				$data['can_add_phone'] = true;
			}

			if($can_add_flag_dvd->isAuthorized() || $can_add_flag_play_bonus->isAuthorized())
			{
				$rawPath = 'qinternal/get/game';
				$generatedParam = $this->generateParam(null, $rawPath);
				$dt_games = new QIAPI($generatedParam);
				$dt_games->send();
				$data["games"] = $dt_games->read();
			}

			$can_set_image = new \QInterface\Libs\PrivilegeChecker(null, null, null, "set_warnet_image");
			if($can_set_image->isAuthorized())
			{
				$data["can_set_image"] = true;
			}

			$can_set_warnet_type = new \QInterface\Libs\PrivilegeChecker(null, null, null, "set_warnet_type");
			if($can_set_warnet_type->isAuthorized())
			{
				$rawPath = "qwarnet/get/list_type";
				$generatedParam = $this->generateParam(null, $rawPath);
				$dt_type = new QIAPI($generatedParam);
				$dt_type->send();
				$data["can_set_warnet_type"] = true;
				$data["type"] = $dt_type->read();
			}

			$data['warnet'] = $dt_single;

			$this->layout = View::make('layout/popup', array(
				"title"	=> "Edit Warnet"
			))->nest('form_popup', 'warnet.form', array(
				"data"			=> $data,
				"url_form"		=> $this->generate_link(GROUP_WARNET . ".addWarnet"),
				"isSales"		=> $isSales
			));
		}

		public function del_warnet($warnet_id = null)
		{
			$status = false;
			$msg = null;
			if($warnet_id != null)
			{
				$extra_params = array(
					'warnet_id'	=> $warnet_id,
					'status_id'	=> 4
				);
				$rawPath = "qwarnet/edit/warnet_approval";
	    		$generatedParam = $this->generateParam($extra_params, $rawPath);
	    		$del = new QIAPI($generatedParam);
	    		$del->send();

				$status = $del->getStatus();
				$msg = $del->getMessage();
			} else {
				$status = false;
				$msg = "ID Tidak ditemukan.";
			}

			$this->layout = View::make('layout/blank', array(
				'content'	=> json_encode(array(
					'status'	=> $status,
					'message'	=> $msg
				))
			));
		}

		public function formAdd()
		{
			$data = array();

			$rawPath = 'qinternal/get/city';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_city = new QIAPI($generatedParam);
			$dt_city->send();
			$data["city"] = $dt_city->read();

			$can_add_flag_qash = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_flag_qash");
			if($can_add_flag_qash->isAuthorized())
			{
				$data["can_add_flag_qash"] = true;
			}

			$can_add_flag_dvd = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_flag_dvd");
			if($can_add_flag_dvd->isAuthorized())
			{
				$data["can_add_flag_dvd"] = true;
			}

			$can_add_flag_play_bonus = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_flag_play_bonus");
			if($can_add_flag_play_bonus->isAuthorized())
			{
				$data["can_add_flag_play_bonus"] = true;
			}

			$can_add_phone = new \QInterface\Libs\PrivilegeChecker(null, null, null, "add_phone");
			if($can_add_phone->isAuthorized())
			{
				$data['can_add_phone'] = true;
			}

			if($can_add_flag_dvd->isAuthorized() || $can_add_flag_play_bonus->isAuthorized())
			{
				$rawPath = 'qinternal/get/game';
				$generatedParam = $this->generateParam(null, $rawPath);
				$dt_games = new QIAPI($generatedParam);
				$dt_games->send();
				$data["games"] = $dt_games->read();
			}

			$can_set_image = new \QInterface\Libs\PrivilegeChecker(null, null, null, "set_warnet_image");
			if($can_set_image->isAuthorized())
			{
				$data["can_set_image"] = true;
			}

			$can_set_warnet_type = new \QInterface\Libs\PrivilegeChecker(null, null, null, "set_warnet_type");
			if($can_set_warnet_type->isAuthorized())
			{
				$rawPath = "qwarnet/get/list_type";
				$generatedParam = $this->generateParam(null, $rawPath);
				$dt_type = new QIAPI($generatedParam);
				$dt_type->send();
				$data["can_set_warnet_type"] = true;
				$data["type"] = $dt_type->read();
			}

			$this->layout = View::make('layout/popup', array(
				"title"	=> "Add Warnet"
			))->nest('form_popup', 'warnet.form', array(
				"data"		=> $data,
				"url_form"		=> $this->generate_link(GROUP_WARNET . ".addWarnet")
			));
		}

		public function list_warnet()
		{
			$privilegeChecker = new \QInterface\Libs\PrivilegeChecker();
	        $isSales = $privilegeChecker->isSales();
			$param = array();
			$rawPath = 'qwarnet/get/warnet_internal/approved';
			$param["limit"] = \LIMIT_PAGING;

			$page = Input::get('page');
			if($page != null) $param["page"] = $page;

			$searchInput = Input::get("name");
			if($searchInput != null)
			{
				if($isSales)
				{
					$param["name_exact"] = $searchInput;
				} else {
					$param["name"] = $searchInput;
				}
			}

			$province_id = Input::get('province_id');
			if($province_id != null && $province_id != 0) $param['province_id'] = $province_id;

			$city_id = Input::get('city_id');
			if($city_id != null && $city_id != 0) $param['city_id'] = $city_id;

			$is_qash = Input::get('is_qash');
			if($is_qash != null) $param['is_qash'] = $is_qash;

			$is_dvd = Input::get('is_dvd');
			if($is_dvd != null) $param["is_dvd"] = $is_dvd;

			$is_play_bonus = Input::get('is_play_bonus');
			if($is_play_bonus != null) $param['is_play_bonus'] = $is_play_bonus;

			$dt_request = new QIAPI($this->generateParam($param, $rawPath));
			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => isset($pages['total_data']) ? $pages['total_data'] : count($result)))
							->nest('search_result', 'warnet.list', array(
								'result' 	=> $result, 
								"url_edit" 	=> $this->generate_link(GROUP_WARNET . ".editWarnet"),
								'url_del'	=> $this->generate_link(GROUP_WARNET . '.delWarnet'),
								"isSales"	=> $isSales
							));
		}

		public function submitForm()
		{
			$result = new stdClass;
			$session = Session::get('qeon_session');
			$rules = array(
                'nama'     =>  "required",
            );

			$validator = Validator::make(Input::all(), $rules);
            if($validator->fails())
            {
                $result->status = false;
                $result->msg    = implode("<br/>", $validator->messages()->all());
                $result->data   = Input::all();
            } else {
            	$inputs	= Input::all();
            	$extra_params = array();

            	if(isset($inputs["image"]) && $inputs["image"] != null) $extra_params["image"] = $inputs["image"];
            	if(isset($inputs["nama"])) $extra_params["name"] = $inputs["nama"];
            	if(isset($inputs["email"])) $extra_params["email"] = $inputs["email"];
            	if(isset($inputs["alamat"])) $extra_params["address"] = $inputs["alamat"];
            	if(isset($inputs["owner_name"])) $extra_params["owner_name"] = $inputs["owner_name"];
            	if(isset($inputs["phone"])) $extra_params["phone"] = $inputs["phone"];
            	if(isset($inputs["type_id"])) $extra_params["type_id"] = $inputs["type_id"];
            	if(isset($session["_auth"]["admin"])) $extra_params["insert_by"] = $session["_auth"]["admin"];
            	if(isset($inputs["kota"])) $extra_params["city_id"] = $inputs["kota"];
            	if(isset($inputs["is_qash"])) $extra_params["is_qash"] = $inputs["is_qash"];
            	if(isset($inputs["is_dvd"])) $extra_params["is_dvd"] = $inputs["is_dvd"];
            	if(isset($inputs["is_game"])) $extra_params["is_play_bonus"] = $inputs["is_game"];

            	$warnet_id = isset($inputs['warnet_id']) ? $inputs['warnet_id'] : null;

            	if($warnet_id != null)
            	{
            		$extra_params['warnet_id'] = $warnet_id;
            		$rawPath = "qwarnet/edit/warnet";
            	} else {
            		$rawPath = "qwarnet/insert/warnet";
            	}
            	$generatedParam = $this->generateParam($extra_params, $rawPath);
            	$insert = new QIAPI($generatedParam);
            	$insert->send();

            	$result->status = $insert->getStatus();
        		$result->data = $insert->read();
            }

            $this->layout = View::make('layout/blank', array(
				"content"	=> json_encode(array("result" => $result))
			));
		}

		public function getCity($province_id = null)
		{
			$result = new stdClass;
			if($province_id != null)
			{
				$rawPath = 'qinternal/get/city';
				$extra_params = array(
	            	'province_id'	=> $province_id
            	);

            	$generatedParam = $this->generateParam($extra_params, $rawPath);

				$dt_city = new QIAPI($generatedParam);
				$dt_city->send();
				$dt_city = $dt_city->read();

				if($dt_city)
				{
					$html = "<option value=''>Pilih Kota ..</option>";
					foreach($dt_city as $city)
					{
						$html .= "<option value='" . $city['id'] . "'>" . $city['name'] . "</option>";
					}

					$result->status = true;
					$result->data_city = $html;
				} else {
					$result->status = false;
					$result->msg = "Province ID Not Found.";
				}
			} else {
				$result->status = false;
				$result->msg = "Province ID Not Found.";
			}

			$this->layout = View::make('layout/blank', array(
				"content"	=> json_encode($result)
			));
		}

		public function test()
	    {
	        $session = Session::get('qeon_session');
	        $api = new QIAPI(array(
	            'user_agent'   => Useragent::agent_string(),
	            'ip_address'   => Request::getClientIp(),
	            'session'      => $session['_auth']['session'],
	            'symkey'       => $session['_auth']['symkey'],
	            'rawPath'	   => 'qinternal/get/city'
	        ));

	        $group = new \QInterface\Libs\PrivilegeChecker(null, null, null, "formAdd");

	        $this->layout = View::make('layout/blank', array(
				"content"	=> $session
			));
	    }
	}

?>