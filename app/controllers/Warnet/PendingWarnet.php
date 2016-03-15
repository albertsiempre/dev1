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

	class PendingWarnet extends BaseController
	{
		public function index()
		{
	        $data = array();
	        $method = "formAdd";
	        $privilegeChecker = new \QInterface\Libs\PrivilegeChecker(null, null, null, $method);

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

			$city = array();
			$city[0] = "Pilih Kota ..";

	        $this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_WARNET . '.list_pending_warnet'),
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
        			)
        		)
        	))->nest('actual_content', 'warnet.pending_warnet.main', $data);
		}

		public function list_pending_warnet()
		{
			$param = array();
			$rawPath = 'qwarnet/get/warnet_internal/pending';
			$param["limit"] = \LIMIT_PAGING;

			$page = Input::get('page');
			if($page != null) $param["page"] = $page;

			$searchInput = Input::get("name");
			if($searchInput != null) $param["name"] = $searchInput;

			$province_id = Input::get('province_id');
			if($province_id != null && $province_id != 0) $param['province_id'] = $province_id;

			$city_id = Input::get('city_id');
			if($city_id != null && $city_id != 0) $param['city_id'] = $city_id;

			$dt_request = new QIAPI($this->generateParam($param, $rawPath));
			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;
			$url_single = $this->generate_link(GROUP_WARNET . ".single_pending_warnet");

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => isset($pages['total_data']) ? $pages['total_data'] : count($result)))
							->nest('search_result', 'warnet.pending_warnet.list', array('result' => $result, 'url_single' => $url_single));
		}

		public function single_pending_warnet($warnet_id = null)
		{
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

			if(isset($dt_single['warnet_edit']))
			{
				$this->layout = View::make("warnet.form_edit", array(
					"data"			=> $data,
					"url_form"		=> $this->generate_link(GROUP_WARNET . ".process_warnet"),
					"title"			=> "View Warnet"
				));
			} else {
				$this->layout = View::make('layout/popup', array(
					"title"	=> "View Warnet"
				))->nest('form_popup', "warnet.pending_warnet.single", array(
					"data"			=> $data,
					"url_form"		=> $this->generate_link(GROUP_WARNET . ".process_warnet")
				));
			}
		}

		public function proccess_warnet()
		{
			$result = new stdClass;
			$session = Session::get('qeon_session');
			$inputs	= Input::all();
	    	$extra_params = array();

	    	if(isset($inputs["warnet_id"])) $extra_params["warnet_id"] = $inputs["warnet_id"];
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
	    	if(isset($inputs['status_id'])) $extra_params['status_id'] = $inputs['status_id'];

	    	$extra_params['is_new_image'] = isset($inputs['is_new_image']) ? $inputs['is_new_image'] : false;
	    	
	    	$rawPath = "qwarnet/edit/warnet_approval";
	    	$generatedParam = $this->generateParam($extra_params, $rawPath);
	    	$insert = new QIAPI($generatedParam);
	    	$insert->send();

	    	$result->status = $insert->getStatus();
			$result->data = $insert->read();
			$result->message = $insert->getMessage();

			$this->layout = View::make('layout/blank', array(
				"content"	=> json_encode(array("result" => $result))
			));
		}
	}

?>