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

	class ReportDVD extends BaseController
	{
		
		public function byCity()
		{
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

			$rawPath = 'qinternal/get/game';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_games = new QIAPI($generatedParam);
			$dt_games->send();
			$dt_games = $dt_games->read();

			$games = array();
			$games[0] = "Pilih Game ..";
			if($dt_games)
			{
				foreach($dt_games as $game)
				{
					$games[$game['id']] = isset($game['name']) ? $game['name'] : '';
				}
			}

			$city = array();
			$city[0] = "Pilih Kota ..";

			$forms = array(
                'game_id' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                    	'name' => 'game_id'
                	),
                    'label' => 'Game',
                    'combo_options' => array(
                    	"data"	=> $games,
                    	'class_name' => '_my_games_combos'
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
			);

			$this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_INTERNAL . '.report.city.filter'),
	        		"form"		=> $forms,
	        		"doInit"	=> false
        		)
        	))->nest('actual_content', 'qinternal.report_dvd.by_city.main');
		}

		public function filterCity()
		{
			$inputs = Input::all();
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
				$param = array(
					"start_date"	=> date("Y-m-d", strtotime($start_date)),
					"end_date"		=> date("Y-m-d", strtotime($end_date)),
					"interval"		=> $periode
				);

				if(isset($inputs['game_id']) && $inputs['game_id'] > 0) $param['game_id'] = $inputs['game_id'];
				if(isset($inputs['province_id']) && $inputs['province_id'] > 0) $param['province_id'] = $inputs['province_id'];
				if(isset($inputs['city_id']) && $inputs['city_id'] > 0) $param['city_id'] = $inputs['city_id'];

				$rawPath = "qinternal/get/report/dvd_request/province";
				$dt_report = new QIAPI($this->generateParam($param, $rawPath));
				$dt_report->send();
				$dt_report = $dt_report->read();
			} else {
				$dt_report = array();
			}

			$this->layout = View::make('layout.search_result', array('pages' => 1, "total" => null))
							->nest('search_result', 'qinternal.report_dvd.by_city.list', array(
								'result' 	=> $dt_report,
								'inputs'	=> $inputs
							));
		}

		public function byGame()
		{
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

			$rawPath = 'qinternal/get/game';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_games = new QIAPI($generatedParam);
			$dt_games->send();
			$dt_games = $dt_games->read();

			$games = array();
			$games[0] = "Pilih Game ..";
			if($dt_games)
			{
				foreach($dt_games as $game)
				{
					$games[$game['id']] = isset($game['name']) ? $game['name'] : '';
				}
			}

			$city = array();
			$city[0] = "Pilih Kota ..";

			$forms = array(
                'game_id' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                    	'name' => 'game_id'
                	),
                    'label' => 'Game',
                    'combo_options' => array(
                    	"data"	=> $games,
                    	'class_name' => '_my_games_combos'
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
			);

			$this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_INTERNAL . '.report.game.filter'),
	        		"form"		=> $forms,
	        		"doInit"	=> false
        		)
        	))->nest('actual_content', 'qinternal.report_dvd.by_game.main');
		}

		public function filterGame()
		{
			$inputs = Input::all();
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
				$param = array(
					"start_date"	=> date("Y-m-d", strtotime($start_date)),
					"end_date"		=> date("Y-m-d", strtotime($end_date)),
					"interval"		=> $periode
				);

				if(isset($inputs['game_id']) && $inputs['game_id'] > 0) $param['game_id'] = $inputs['game_id'];
				if(isset($inputs['province_id']) && $inputs['province_id'] > 0) $param['province_id'] = $inputs['province_id'];
				if(isset($inputs['city_id']) && $inputs['city_id'] > 0) $param['city_id'] = $inputs['city_id'];

				$rawPath = "qinternal/get/report/dvd_request/game";
				$dt_report = new QIAPI($this->generateParam($param, $rawPath));
				$dt_report->send();
				$dt_report = $dt_report->read();
			} else {
				$dt_report = array();
			}

			$this->layout = View::make('layout.search_result', array('pages' => 1, "total" => null))
							->nest('search_result', 'qinternal.report_dvd.by_game.list', array(
								'result' 	=> $dt_report,
								'inputs'	=> $inputs
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
					$html = "<option value='0'>Pilih Kota ..</option>";
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


		public function userDvdRequest()
		{
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

			$rawPath = 'qinternal/get/game';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_games = new QIAPI($generatedParam);
			$dt_games->send();
			$dt_games = $dt_games->read();

			$games = array();
			$games[0] = "Pilih Game ..";
			if($dt_games)
			{
				foreach($dt_games as $game)
				{
					$games[$game['id']] = isset($game['name']) ? $game['name'] : '';
				}
			}

			$forms = array(
                'game_id' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                    	'name' => 'game_id'
                	),
                    'label' => 'Game',
                    'combo_options' => array(
                    	"data"	=> $games,
                    	'class_name' => '_my_games_combos'
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
                ),
                'start_date' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'start_date',
                        'class' => '_start_date',
                        "value"	=> date("Y-m-d", strtotime("yesterday"))
                    ),
                    'validation' => 'required',
                    'label' => 'Start Date Request'
                ),
                'end_date' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'end_date',
                        'class' => '_end_date',
                        "value"	=> date("Y-m-d")
                    ),
                    'validation' => 'required',
                    'label' => 'End Date Request'
                ),
                'start_date_act' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'start_date_act',
                        'class' => '_start_date_act',
                        "value"	=> date("Y-m-d", strtotime("yesterday"))
                    ),
                    'validation' => 'required',
                    'label' => 'Start Date Activity'
                ),
                'end_date_act' => array(
                    'type' => 'input',
                    'data' => array(
                        'name' => 'end_date_act',
                        'class' => '_end_date_act',
                        "value"	=> date("Y-m-d")
                    ),
                    'validation' => 'required',
                    'label' => 'End Date Activity'
                )
			);

			$this->layout->title = 'Qeon Interactive';
	        $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_INTERNAL . '.filter_report_user'),
	        		"form"		=> $forms,
	        		"doInit"	=> true
        		)
        	))->nest('actual_content', 'qinternal.report_dvd.user_activity.main');
		}

		public function filterUserDvdRequest()
		{
			$inputs = Input::all();
			$start_date = isset($inputs['start_date']) ? $inputs['start_date'] :'';
			$end_date = isset($inputs['end_date']) ? $inputs['end_date'] : '';
			$start_act_date = isset($inputs['start_date_act']) ? $inputs['start_date_act'] : '';
			$end_act_date = isset($inputs['end_date_act']) ? $inputs['end_date_act'] : '';
			$param = array();

			if($start_date != "" && $end_date != "")
			{
				$param = array(
					"start_date"	=> date("Y-m-d", strtotime($start_date)),
					"end_date"		=> date("Y-m-d", strtotime($end_date))
				);
			}

			if($start_act_date != "" && $end_act_date != "")
			{
				$param = array_merge($param, array(
					"start_date_act" => date("Y-m-d", strtotime($start_act_date)),
					"end_date_act"	 => date("Y-m-d", strtotime($end_act_date))	
				));
			}

			if(isset($inputs['game_id']) && $inputs['game_id'] > 0) $param['game_id'] = $inputs['game_id'];
			if(isset($inputs['province_id']) && $inputs['province_id'] > 0) $param['province_id'] = $inputs['province_id'];
			if(isset($inputs['city_id']) && $inputs['city_id'] > 0) $param['city_id'] = $inputs['city_id'];

			$rawPath = "qinternal/get/user_dvd_request";
			$dt_report = new QIAPI($this->generateParam($param, $rawPath));
			$dt_report->send();
			$dt_report = $dt_report->read();

			$this->layout = View::make('layout.search_result', array('pages' => 1, "total" => null))
							->nest('search_result', 'qinternal.report_dvd.user_activity.list', array(
								'result' 	=> $dt_report,
								'inputs'	=> $inputs
							));
		}

	}

?>