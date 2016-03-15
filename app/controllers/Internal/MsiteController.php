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

	class MsiteController extends BaseController
	{
        //BANNER
		public function Banner()
		{
            $data                   = array();
            $url_new_team           = $this->generate_link(GROUP_INTERNAL . '.form.banner');
            $data['url_new_team']   = $url_new_team;
            
            $rawPath        = 'qinternal/get/game';
            $generatedParam = $this->generateParam(null, $rawPath);
            $dt_game        = new QIAPI($generatedParam);
            $dt_game->send();
            $dt_game        = $dt_game->read();
            //$result         = isset($dt_game["result"]) ? $dt_game["result"] : null;

            $games      = array();
            $games['']  = "Pilih Game ..";

            if($dt_game)
            {
                foreach($dt_game as $val)
                {
                    $games[$val['id']] = isset($val['name']) ? $val['name'] : '';
                }
            }                         
                        
            $this->layout->title = "Qeon Interactive";
            $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
                "data_form" => array(
                    "action"    => $this->generate_link(GROUP_INTERNAL . '.list_banner'),
                    "form"      => array(                                  
                        'category_id' => array(
                            'type'          => 'dropdown',
                            'validation'    => 'required',
                            'data' => array(
                                    'name' => 'category_id'
                            ),
                            'label' => 'Games',
                            'combo_options' => array(
                                    "data"  => $games,
                                    'class_name' => '_my_winner_combos'
                            )
                        ),
                        'start_date' => array(
                            'type' => 'input',
                            'data' => array(
                                'name' => 'start_date',
                                'class' => '_start_date',
                                "value" => ''
                            ),
                            'validation' => 'required',
                            'label' => 'Start Date'
                        ),
                        'end_date' => array(
                            'type' => 'input',
                            'data' => array(
                                'name' => 'end_date',
                                'class' => '_end_date',
                                "value" => ''
                            ),
                            'validation' => 'required',
                            'label' => 'End Date'
                        )
                    )
                )
            ))->nest('actual_content', 'qinternal.microsite.banner.main', $data);
		}

		public function BannerList()
		{
            $param = array();
            $rawPath = 'qinternal/get/microsite/side_banner/all';
            $param["limit"] = \LIMIT_PAGING;

            $page = Input::get('page');
            if($page != null)
            {
                $param["page"] = $page;
            }
                        
            $param_category_id = Input::get("category_id");
            if($param_category_id != null && !empty($param_category_id))
            {
                $param["category_id"] = $param_category_id;
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
                            ->nest('search_result', 'qinternal.microsite.banner.list', array('result' => $result));
		}
                
		public function FormBanner($banner_id = null)
		{
            $act_form   = $this->generate_link(GROUP_INTERNAL . '.submit.banner');
            $data       = array();
            
            //$param["is_finished"]   = 0;        

            $rawPath        = 'qinternal/get/game';
            $generatedParam = $this->generateParam(null, $rawPath);
            $dt_game        = new QIAPI($generatedParam);
            $dt_game->send();
            $dt_game        = $dt_game->read();
            //$data['games']  = isset($dt_game["result"]) ? $dt_game["result"] : null;            
            $data['games']  = $dt_game;            
            
            if($banner_id != null){
                //CODE GET BANNER FOR EDIT
                $param                      = array();
                $rawPath                    = 'qinternal/get/microsite/side_banner/single';
                $param["side_banner_id"]    = $banner_id;

                $generatedParam     = $this->generateParam($param, $rawPath);
                $dt_request         = new QIAPI($generatedParam);
                $dt_request->send();
                $dt_request         = $dt_request->read();
                $data['banner']     = $dt_request;
            }   
            
            $this->layout = View::make('qinternal.microsite.banner.form', array(
                'act_form' => $act_form,
                'data'     => $data
            ));
        }
                
         public function SubmitBanner()
        {
            $result = new \stdClass();
            $params = Input::all();
            $rules  = array(
                'category_id'    => 'required|min:1',
                'link'           => 'required|min:1',
                'alt'            => 'required|min:1',
                'start_date'     => 'required|min:1',
                'end_date'       => 'required|min:1',
                'order'          => 'required|min:1|numeric'
            );

            $validator = Validator::make(Input::all(), $rules);
            if($validator->fails())
            {
                $result->status = false;
                $result->message = "<br/>".implode("<br/>", $validator->messages()->all());
            } else {
                $banner_image       = Input::file('banner_image');
                $category_id        = Input::get("category_id");
                $link               = Input::get("link");
                $alt                = Input::get("alt");
                $start_date         = Input::get("start_date");
                $end_date           = Input::get("end_date");
                $order              = Input::get("order");
                $base_64_image      = Input::get("image");
                
                $default_params = array(
                        "category_id"   => $category_id,
                        "link"          => $link,
                        "alt"           => $alt,
                        "start_date"    => $start_date,
                        "end_date"      => $end_date,
                        "order"         => $order
                );                        
                
                if($params['banner_id'] == 0){
                    //CODE INSERT TEAM
                    if(isset($banner_image) && !empty($banner_image) && $banner_image != null){
                        $image_name         = $banner_image->getClientOriginalName();
                        $extra_params       = array_merge($default_params, array("image_name"       => $image_name, 
                                                                                    "image"         => $base_64_image));
                    }else{
                        $extra_params       = $default_params;
                    }
                }else{
                    //CODE EDIT TEAM
                    if(isset($banner_image) && !empty($banner_image) && $banner_image != null){
                        $image_name         = $banner_image->getClientOriginalName();
                        $extra_params       = array_merge($default_params, array("image_name"           => $image_name, 
                                                                                    "image"             => $base_64_image,
                                                                                    "side_banner_id"    => $params['banner_id']));                                
                    }else{
                        $extra_params       = array_merge($default_params, array("side_banner_id" => $params['banner_id']));                                  
                    }
                }
               
                $rawPath        = 'qinternal/insert/side_banner';
                $generatedParam = $this->generateParam($extra_params, $rawPath);
                $dt_update      = new QIAPI($generatedParam);
                $dt_update->send();

                $result->status  = $dt_update->getStatus();
                $result->message = $dt_update->read();
                $result->param   = $generatedParam;
            }

            $this->layout = View::make('layout.blank', array(
                        "content"   => json_encode(array(
                        "result"        => $result
                        ))                        
            ));            
        } 
	}

?>