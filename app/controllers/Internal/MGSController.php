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
	define('LIMIT_PAGING', '10');

	class MGSController extends BaseController
	{
                //EVENT
		public function Event()
		{
			$data = array();
			$url_new_event          = $this->generate_link(GROUP_INTERNAL . '.form.event');
			$data['url_new_event']  = $url_new_event;
			
			$finish = array();
			$finish[''] = "Pilih ..";
			$finish[0] = "Ongoing";
			$finish[1] = "Finished";                           
                        
			$this->layout->title    = "Qeon Interactive";
			$this->layout->content  = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"    => $this->generate_link(GROUP_INTERNAL . '.list_event'),
	        		"form"      => array(
                                            'name' => array(
                                                'type' => 'input',
                                                'data' => array(
                                                    'name'      => 'name',
                                                    'autofocus' => 'autofocus'
                                                ),
                                            'validation' => 'required',
                                            'label' => 'Event name'
                                        ),
                                        'is_finished' => array(
                                            'type'          => 'dropdown',
                                            'validation'    => 'required',
                                            'data' => array(
                                                    'name' => 'is_finished'
                                            ),
                                            'label' => 'Finish',
                                            'combo_options' => array(
                                                    "data"	=> $finish,
                                                    'class_name' => '_my_finish_combos'
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
                                        )
        			)
        		)
        	))->nest('actual_content', 'qinternal.mgs.event.main', $data);
		}

		public function EventList()
		{
			$param = array();
			$rawPath = 'mgs/get/event/all';
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
                        
			$param_is_finished = Input::get("is_finished");
			if($param_is_finished != null)
			{
				$param["is_finished"] = $param_is_finished;
			}
                        
			$param_start_date = Input::get("start_date");
			if($param_start_date != null && !empty($param_start_date))
			{
				$param["start_date"] = $param_start_date;
			}
                        
			$param_end_date = Input::get("end_date");                        
			if($param_is_finished != null && !empty($param_end_date))
			{
				$param["end_date"] = $param_end_date;
			}

			$generatedParam = $this->generateParam($param, $rawPath);
			$dt_request = new QIAPI($generatedParam);
			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;
//			$total_checkout = isset($dt_request['total_checkout']) ? $dt_request['total_checkout'] : '0';

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => count($result)))
							->nest('search_result', 'qinternal.mgs.event.list', array('result' => $result));
		}
                
		public function FormEvent($event_id = null)
		{
                        $act_form   = $this->generate_link(GROUP_INTERNAL . '.submit.event');
                        $data       = array();
                        
                        if($event_id != null){
                            //CODE GET EVENT FOR EDIT
                            $param              = array();
                            $rawPath            = 'mgs/get/event/single';
                            $param["event_id"]  = $event_id;

                            $generatedParam = $this->generateParam($param, $rawPath);
                            $dt_request     = new QIAPI($generatedParam);
                            $dt_request->send();
                            $dt_request     = $dt_request->read();
                            $data['event']  = $dt_request;
                        }

			$this->layout = View::make('qinternal.mgs.event.form', array(
				'act_form' => $act_form,
                                'data'     => $data
			));
                }
                
                function SubmitEvent()
                {
                    $result = new \stdClass();
                    $params = Input::all();
                    $rules = array(
                        'event_name'     => 'required|min:1',
                        'subtitle'       => 'required|min:1',
                        'start_date'     => 'required|min:1',
                        'end_date'       => 'required|min:1'
                    );

                    if($params['event_id'] == 0) $rules['event_image'] = 'required|image';

                    $validator = Validator::make(Input::all(), $rules);
                    if($validator->fails())
                    {
                        $result->status = false;
                        $result->message = "<br/>".implode("<br/>", $validator->messages()->all());
                    } else {
                        
                        $event_image_file           = Input::file("event_image");
                        $bracket_image_file         = Input::file("bracket_image");
                        $participating_image_file   = Input::file("participating_image");
			$event_name                 = Input::get("event_name");
			$subtitle                   = Input::get("subtitle");
			$link                       = Input::get("link");
			$start_date                 = Input::get("start_date");
			$end_date                   = Input::get("end_date");
			$is_finished                = Input::get("is_finished") == true ? 1 : 0;
			$base_64_image              = Input::get("image");
			$base_64_bracket            = Input::get("image_bracket");
			$base_64_participating      = Input::get("image_participating");
                        $rule                       = Input::get("rule");
                        
                        $default_params = array(
                                "name"          => $event_name,
                                "subtitle"      => $subtitle,
                                "link"          => $link,
                                "start_date"    => $start_date,
                                "end_date"      => $end_date,
                                "is_finished"   => $is_finished,
                                "rule"          => $rule
                        );                        
                        
                        if($params['event_id'] == 0){
                            //CODE INSERT EVENT
                            $image_name         = $event_image_file->getClientOriginalName();
                            $extra_params       = array_merge($default_params, array("image_name"    => $image_name, 
                                                                                        "image_logo"    => $base_64_image));
                            if(!empty($base_64_bracket) && $base_64_bracket != null){
                                $bracket_image_name     = $bracket_image_file->getClientOriginalName();
                                $extra_params           = array_merge($extra_params, array("bracket_name"    => $bracket_image_name, 
                                                                                            "bracket_image"    => $base_64_bracket));                                
                            }
                            if(!empty($base_64_participating) && $base_64_participating != null){
                                $participating_image_name     = $participating_image_file->getClientOriginalName();
                                $extra_params                 = array_merge($extra_params, array("participating_name"    => $participating_image_name, 
                                                                                                    "participating_image"    => $base_64_participating));                                
                            }
                        }else{
                            //CODE EDIT EVENT
                            if(!empty($event_image_file) && $event_image_file != null){
                                $image_name         = $event_image_file->getClientOriginalName();
                                $extra_params       = array_merge($default_params, array("image_name"    => $image_name, 
                                                                                            "image_logo"    => $base_64_image,
                                                                                            "event_id"      => $params['event_id']));
                            }else{
                                $extra_params       = array_merge($default_params, array("event_id" => $params['event_id']));                                  
                            }
                            
                            if(!empty($base_64_bracket) && $base_64_bracket != null){
                                $bracket_image_name     = $bracket_image_file->getClientOriginalName();
                                $extra_params           = array_merge($extra_params, array("bracket_name"    => $bracket_image_name, 
                                                                                            "bracket_image"    => $base_64_bracket));                                
                            }
                            
                            if(!empty($base_64_participating) && $base_64_participating != null){
                                $participating_image_name     = $participating_image_file->getClientOriginalName();
                                $extra_params                 = array_merge($extra_params, array("participating_name"    => $participating_image_name, 
                                                                                                    "participating_image"    => $base_64_participating));                                
                            }
                        }
                       
                        $rawPath        = 'mgs/insert/event';
                        $generatedParam = $this->generateParam($extra_params, $rawPath);
                        $dt_update      = new QIAPI($generatedParam);
                        $dt_update->send();

                        $result->status  = $dt_update->getStatus();
                        $result->message = $dt_update->read();
                        $result->param   = $generatedParam;                          
                    }

                    $this->layout = View::make('layout.blank', array(
                                "content"	=> json_encode(array(
                                "result"        => $result
                                ))                        
                    ));
                } 
                //END EVENT
                
                //TEAM
		public function Team()
		{
			$data                   = array();
			$url_new_team           = $this->generate_link(GROUP_INTERNAL . '.form.team');
			$data['url_new_team']   = $url_new_team;
			
            $rawPath    = 'mgs/get/event/all';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_event   = new QIAPI($generatedParam);
			$dt_event->send();
			$dt_event   = $dt_event->read();
            $result     = isset($dt_event["result"]) ? $dt_event["result"] : null;

			$event = array();
			$event[''] = "Pilih Event ..";
			if($dt_event)
			{
				foreach($result as $val)
				{
					$event[$val['id']] = isset($val['full_name']) ? $val['full_name'] : '';
				}
			}                         
                        
			$this->layout->title = "Qeon Interactive";
			$this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_INTERNAL . '.list_team'),
	        		"form"		=> array(
	        			'name' => array(
	                        'type' => 'input',
	                        'data' => array(
	                            'name' => 'name',
	                            'autofocus' => 'autofocus'
	                        ),
	                        'validation' => 'required',
	                        'label' => 'Name'
	                    ),                                    
                            'event_id' => array(
                                'type'          => 'dropdown',
                                'validation'    => 'required',
                                'data' => array(
                                        'name' => 'event_id'
                                ),
                                'label' => 'Event',
                                'combo_options' => array(
                                        "data"	=> $event,
                                        'class_name' => '_my_winner_combos'
                                )
                            )
        			)
        		)
        	))->nest('actual_content', 'qinternal.mgs.team.main', $data);
		}
                
		public function TeamList()
		{
			$param = array();
			$rawPath = 'mgs/get/team/all';
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
                        
			$param_event_id = Input::get("event_id");
			if($param_event_id != null && !empty($param_event_id))
			{
				$param["event_id"] = $param_event_id;
			}

			$generatedParam = $this->generateParam($param, $rawPath);
			$dt_request = new QIAPI($generatedParam);
			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => count($result)))
							->nest('search_result', 'qinternal.mgs.team.list', array('result' => $result));
		}
                
		public function FormTeam($team_id = null)
		{
                        $act_form   = $this->generate_link(GROUP_INTERNAL . '.submit.team');
                        $data       = array();
                        
                        $param["is_finished"]   = 0;
                        $rawPath                = 'mgs/get/event/all';

                        $generatedParam = $this->generateParam($param, $rawPath);
                        $dt_event       = new QIAPI($generatedParam);
                        $dt_event->send();
                        $dt_event       = $dt_event->read();
                        $data['event']  = isset($dt_event["result"]) ? $dt_event["result"] : null;                       
                        
                        if($team_id != null){
                            //CODE GET EVENT FOR EDIT
                            $param              = array();
                            $rawPath            = 'mgs/get/team/single';
                            $param["team_id"]   = $team_id;

                            $generatedParam = $this->generateParam($param, $rawPath);
                            $dt_request     = new QIAPI($generatedParam);
                            $dt_request->send();
                            $dt_request     = $dt_request->read();
                            $data['team']   = $dt_request;
                        }   
                        
			$this->layout = View::make('qinternal.mgs.team.form', array(
				'act_form' => $act_form,
                                'data'     => $data
			));
                }
                
                function SubmitTeam()
                {
                    $result = new \stdClass();
                    $params = Input::all();
                    $rules  = array(
                        'team_name'      => 'required|min:1',
                        'order'          => 'required|min:1|numeric',
                        'event_id'       => 'required|min:1'
                    );

                    $validator = Validator::make(Input::all(), $rules);
                    if($validator->fails())
                    {
                        $result->status = false;
                        $result->message = "<br/>".implode("<br/>", $validator->messages()->all());
                    } else {
                        $team_logo          = Input::file('team_logo');
			$team_name          = Input::get("team_name");
			$order              = Input::get("order");
			$event_id           = Input::get("event_id");
                        $base_64_image      = Input::get("image");
                        
                        $default_params = array(
                                "name"          => $team_name,
                                "event_id"      => $event_id,
                                "order"         => $order
                        );                        
                        
                        if($params['team_id'] == 0){
                            //CODE INSERT TEAM
                            if(isset($team_logo) && !empty($team_logo) && $team_logo != null){
                                $image_name         = $team_logo->getClientOriginalName();
                                $extra_params       = array_merge($default_params, array("image_name"    => $image_name, 
                                                                                            "image_logo"    => $base_64_image));
                            }else{
                                $extra_params       = $default_params;
                            }
                        }else{
                            //CODE EDIT TEAM
                            if(isset($team_logo) && !empty($team_logo) && $team_logo != null){
                                $image_name         = $team_logo->getClientOriginalName();
                                $extra_params       = array_merge($default_params, array("image_name"    => $image_name, 
                                                                                            "image_logo"    => $base_64_image,
                                                                                            "team_id"      => $params['team_id']));                                
                            }else{
                                $extra_params       = array_merge($default_params, array("team_id" => $params['team_id']));                                  
                            }
                        }
                       
                        $rawPath        = 'mgs/insert/team';
                        $generatedParam = $this->generateParam($extra_params, $rawPath);
                        $dt_update      = new QIAPI($generatedParam);
                        $dt_update->send();

                        $result->status  = $dt_update->getStatus();
                        $result->message = $dt_update->read();
                        $result->param   = $generatedParam;
                    }

                    $this->layout = View::make('layout.blank', array(
                                "content"	=> json_encode(array(
                                "result"        => $result
                                ))                        
                    ));
                }
                //END TEAM
                
                //WINNER
		public function Winner()
		{
			$data                   = array();
			$url_new_winner         = $this->generate_link(GROUP_INTERNAL . '.form.winner');
			$data['url_new_winner'] = $url_new_winner;
                        
                        $rawPath    = 'mgs/get/event/all';
			$generatedParam = $this->generateParam(null, $rawPath);
			$dt_event   = new QIAPI($generatedParam);
			$dt_event->send();
			$dt_event   = $dt_event->read();
                        $result     = isset($dt_event["result"]) ? $dt_event["result"] : null;

			$event = array();
			$event[''] = "Pilih Event ..";
			if($dt_event)
			{
				foreach($result as $val)
				{
					$event[$val['id']] = isset($val['full_name']) ? $val['full_name'] : '';
				}
			}                        
			
			$this->layout->title = "Qeon Interactive";
			$this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_INTERNAL . '.list_winner'),
	        		"form"		=> array(
                                    'event_id' => array(
                                        'type'          => 'dropdown',
                                        'validation'    => 'required',
                                        'data' => array(
                                                'name' => 'event_id'
                                        ),
                                        'label' => 'Event',
                                        'combo_options' => array(
                                                "data"	=> $event,
                                                'class_name' => '_my_winner_combos'
                                        )
                                    )
                            )
        		)
        	))->nest('actual_content', 'qinternal.mgs.winner.main', $data);
		}
                
		public function WinnerList()
		{
			$param = array();
			$rawPath = 'mgs/get/winner/all';
			$param["limit"] = \LIMIT_PAGING;

			$page = Input::get('page');
			if($page != null)
			{
				$param["page"] = $page;
			}

			$event_idInput = Input::get("event_id");
			if($event_idInput != null)
			{
				$param["event_id"] = $event_idInput;
			}

			$generatedParam = $this->generateParam($param, $rawPath);
			$dt_request = new QIAPI($generatedParam);
			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => count($result)))
							->nest('search_result', 'qinternal.mgs.winner.list', array('result' => $result));
		}
                
		public function FormWinner($winner_id = null)
		{
                        $act_form   = $this->generate_link(GROUP_INTERNAL . '.submit.winner');
                        $data       = array();
                        
                        $paraTeam["is_finished"]   = 0;
                        $rawPath                   = 'mgs/get/team/all';

                        $generatedParamTeam = $this->generateParam($paraTeam, $rawPath);
                        $dt_team            = new QIAPI($generatedParamTeam);
                        $dt_team->send();
                        $dt_team            = $dt_team->read();
                        $data['team']       = isset($dt_team["result"]) ? $dt_team["result"] : null;
                        
                        $paramevent["is_finished"]   = 0;
                        $rawPathEvent                = 'mgs/get/event/all';

                        $generatedParamEvent    = $this->generateParam($paramevent, $rawPathEvent);
                        $dt_event               = new QIAPI($generatedParamEvent);
                        $dt_event->send();
                        $dt_event               = $dt_event->read();
                        $data['event']          = isset($dt_event["result"]) ? $dt_event["result"] : null;                       
                        
                        if($winner_id != null){
                            //CODE GET EVENT FOR EDIT
                            $param              = array();
                            $rawPath            = 'mgs/get/winner/single';
                            $param["winner_id"] = $winner_id;

                            $generatedParam = $this->generateParam($param, $rawPath);
                            $dt_request     = new QIAPI($generatedParam);
                            $dt_request->send();
                            $dt_request     = $dt_request->read();
                            $data['winner'] = $dt_request;
                        }   
                        
			$this->layout = View::make('qinternal.mgs.winner.form', array(
				'act_form' => $act_form,
                                'data'     => $data
			));
                }
                
                function SubmitWinner()
                {
                    $result = new \stdClass();
                    $params = Input::all();
                    $rules = array(
                        'event_id'       => 'required|min:1',
                        'winner_name'    => 'required|min:1',
                        'winner_value'   => 'required|min:1',
                        'order'          => 'required|min:1|numeric',
                    );

                    $validator = Validator::make(Input::all(), $rules);
                    if($validator->fails())
                    {
                        $result->status = false;
                        $result->message = "<br/>".implode("<br/>", $validator->messages()->all());
                    } else {
			$winner_name        = Input::get("winner_name");
			$winner_value       = Input::get("winner_value");
			$detail             = Input::get("detail");
			$order              = Input::get("order");
			$event_id           = Input::get("event_id");
                        $team_id            = Input::get("team_id");
                        
                        $default_params = array(
                                "name"          => $winner_name,
                                "value"         => $winner_value,
                                "detail"        => $detail,
                                "order"         => $order,
                                "event_id"      => $event_id,
                                "team_id"       => $team_id
                        );                        
                        
                        if($params['winner_id'] == 0){
                            //CODE INSERT WINNER
                            $extra_params       = $default_params;
                        }else{
                            //CODE EDIT WINNER
                            $extra_params       = array_merge($default_params, array("winner_id" => $params['winner_id']));                                  
                        }
                       
                        $rawPath        = 'mgs/insert/winner';
                        $generatedParam = $this->generateParam($extra_params, $rawPath);
                        $dt_update      = new QIAPI($generatedParam);
                        $dt_update->send();

                        $result->status  = $dt_update->getStatus();
                        $result->message = $dt_update->read();
                        $result->param   = $generatedParam;
                    }

                    $this->layout = View::make('layout.blank', array(
                                "content"	=> json_encode(array(
                                "result"        => $result
                                ))                        
                    ));
                }
                //END WINNER
                
                //VERSUS
		public function TeamVersus()
		{
			$data                       = array();
			$url_new_teamversus         = $this->generate_link(GROUP_INTERNAL . '.form.teamversus');
			$data['url_new_teamversus'] = $url_new_teamversus;                    
			
			$this->layout->title = "Qeon Interactive";
			$this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
	        	"data_form"	=> array(
	        		"action"	=> $this->generate_link(GROUP_INTERNAL . '.list_teamversus'),
	        		"form"		=> array(
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
        	))->nest('actual_content', 'qinternal.mgs.teamversus.main', $data);
		}
                
		public function TeamVersusList()
		{
			$param = array();
			$rawPath = 'mgs/get/versus/all';
			$param["limit"] = \LIMIT_PAGING;

			$page = Input::get('page');
			if($page != null)
			{
				$param["page"] = $page;
			}

			$param_start_date = Input::get("start_date");
			if($param_start_date != null && !empty($param_start_date))
			{
				$param["start_date"] = $param_start_date;
			}
                        
			$param_end_date = Input::get("end_date");
			if($param_end_date != null && !empty($param_end_date))
			{
				$param["end_date"] = $param_end_date;
			}

			$generatedParam = $this->generateParam($param, $rawPath);
			$dt_request = new QIAPI($generatedParam);
			$dt_request->send();
			$dt_request = $dt_request->read();
			$result = isset($dt_request["result"]) ? $dt_request["result"] : null;
			$pages = isset($dt_request["pages"]) ? $dt_request["pages"] : null;

			$this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => count($result)))
							->nest('search_result', 'qinternal.mgs.teamversus.list', array('result' => $result));
		}
                
		public function FormTeamVersus($teamversus_id = null)
		{
                        $act_form   = $this->generate_link(GROUP_INTERNAL . '.submit.teamversus');
                        $data       = array();
                        
                        $paramVersus["is_finished"]     = 0;
                        $rawPath                        = 'mgs/get/team/all';

                        $generatedParam = $this->generateParam($paramVersus, $rawPath);
                        $dt_team        = new QIAPI($generatedParam);
                        $dt_team->send();
                        $dt_team        = $dt_team->read();
                        $data['team']   = isset($dt_team["result"]) ? $dt_team["result"] : null;                     
                        
                        if($teamversus_id != null){
                            //CODE GET EVENT FOR EDIT
                            $param                  = array();
                            $rawPath                = 'mgs/get/versus/single';
                            $param["team_versus_id"] = $teamversus_id;

                            $generatedParam     = $this->generateParam($param, $rawPath);
                            $dt_request         = new QIAPI($generatedParam);
                            $dt_request->send();
                            $dt_request         = $dt_request->read();
                            $data['teamversus'] = $dt_request;
                        }   
                        
			$this->layout = View::make('qinternal.mgs.teamversus.form', array(
				'act_form' => $act_form,
                                'data'     => $data
			));
                }
                
                function SubmitTeamVersus()
                {
                    $result = new \stdClass();
                    $params = Input::all();
                    $rules = array(
                        'team_id'       => 'required|min:1',
                        'opponent_id'   => 'required|min:1',
                        'start_date'    => 'required|min:1',
                        'end_date'      => 'required|min:1',
                    );

//                    if($params['team_id'] == 0) $rules['team_logo'] = 'required|image';

                    $validator = Validator::make(Input::all(), $rules);
                    if($validator->fails())
                    {
                        $result->status = false;
                        $result->message = "<br/>".implode("<br/>", $validator->messages()->all());
                    } else {
			$team_id        = Input::get("team_id");
			$opponent_id    = Input::get("opponent_id");
			$link           = Input::get("link");
			$start_date     = Input::get("start_date");
			$end_date       = Input::get("end_date");
                        
                        $default_params = array(
                                "team_id"          => $team_id,
                                "opponent_id"      => $opponent_id,
                                "link"              => $link,
                                "start_date"       => $start_date,
                                "end_date"         => $end_date
                        );                        
                        
                        if($params['team_versus_id'] == 0){
                            //CODE INSERT TEAM VERSUS
                            $extra_params       = $default_params;
                        }else{
                            //CODE EDIT TEAM VERSUS
                            $extra_params       = array_merge($default_params, array("team_versus_id" => $params['team_versus_id']));                                  
                        }
                       
                        $rawPath        = 'mgs/insert/team_versus';
                        $generatedParam = $this->generateParam($extra_params, $rawPath);
                        $dt_update      = new QIAPI($generatedParam);
                        $dt_update->send();

                        $result->status  = $dt_update->getStatus();
                        $result->message = $dt_update->read();
                        $result->param   = $generatedParam;
                    }

                    $this->layout = View::make('layout.blank', array(
                                "content"	=> json_encode(array(
                                "result"        => $result
                                ))                        
                    ));
                }
                //END VERSUS
                
                ///////////////////////////////////////////////////////////////////////////////
	}

?>