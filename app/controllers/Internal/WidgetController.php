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

	class WidgetController extends BaseController
	{
        public function index()
        {
            $data = array();
            $data['add'] = true;
            $data['url_add'] = $this->generate_link(GROUP_INTERNAL . '.formAdd');

            $rawPath = 'qinternal/get/microsites';
            $generatedParam = $this->generateParam(null, $rawPath);
            $dt_games = new QIAPI($generatedParam);
            $dt_games->send();
            $dt_games = $dt_games->read();

            $games = array();
            if($dt_games)
            {
                foreach($dt_games as $game)
                {
                    $games[$game['id']] = isset($game['name']) ? $game['name'] : '';
                }
            }

            $rawPath = 'qinternal/get/widget/type';
            $generatedParam = $this->generateParam(null, $rawPath);
            $dt_widget_type = new QIAPI($generatedParam);
            $dt_widget_type->send();
            $dt_widget_type = $dt_widget_type->read();

            $widgets = array();
            $widgets[0] = "Semua Widget Type";
            if($dt_widget_type)
            {
                foreach($dt_widget_type as $type)
                {
                    $widgets[$type['id']] = isset($type['name']) ? $type['name'] : '';
                }
            }

            $forms = array(
                'game_id' => array(
                    'type' => 'checkbox',
                    'data' => array(
                        'name' => 'category_id[]'
                    ),
                    'label' => 'Game',
                    'check_options' => array(
                        "data"  => $games
                    )
                ),
                'widget_type_id' => array(
                    'type' => 'dropdown',
                    'validation' => 'required',
                    'data' => array(
                        'name' => 'widget_type_id'
                    ),
                    'label' => 'Widget Type',
                    'combo_options' => array(
                        "data"  => $widgets,
                        'class_name' => '_my_games_combos'
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
            );

            $this->layout->title = 'Qeon Interactive';
            $this->layout->content = View::make('layout.content')->nest('search_content', 'layout.search', array(
                "data_form" => array(
                    "action"    => $this->generate_link(GROUP_INTERNAL . '.filterWidget'),
                    "form"      => $forms,
                    "doInit"    => true
                )
            ))->nest('actual_content', 'qinternal.widget.main', $data);
        }

        public function filterWidget()
        {
            $params = array();
            $inputs = Input::all();

            if(isset($inputs['category_id']))
            {
                $categories = $inputs['category_id'];
                $params['category_id'] = $categories;
            }

            if(isset($inputs['widget_type_id']) && $inputs['widget_type_id'] != '0') $params['type_id'] = $inputs['widget_type_id'];
            if(isset($inputs['start_date']) && $inputs['start_date'] != null) $params['start_date'] = $inputs['start_date'];
            if(isset($inputs['end_date']) && $inputs['end_date'] != null) $params['end_date'] = $inputs['end_date'];

            $rawPath = "qinternal/get/widget";
            $dt_widget = new QIAPI($this->generateParam($params, $rawPath));
            $dt_widget->send();
            $dt_widget = $dt_widget->read();

            $this->layout = View::make('layout.search_result', array('pages' => 1, "total" => null))
                            ->nest('search_result', 'qinternal.widget.list', array(
                                'result'    => $dt_widget,
                                'inputs'    => $inputs,
                                'params'    => $params
                            ));
        }

        public function formAdd($id = null)
        {
            $data = array();

            $rawPath = 'qinternal/get/microsites';
            $generatedParam = $this->generateParam(null, $rawPath);
            $dt_games = new QIAPI($generatedParam);
            $dt_games->send();
            $dt_games = $dt_games->read();

            $games = array();
            if($dt_games)
            {
                foreach($dt_games as $game)
                {
                    $games[$game['id']] = isset($game['name']) ? $game['name'] : '';
                }
            }

            $rawPath = 'qinternal/get/widget/type';
            $generatedParam = $this->generateParam(null, $rawPath);
            $dt_widget_type = new QIAPI($generatedParam);
            $dt_widget_type->send();
            $dt_widget_type = $dt_widget_type->read();

            $widgets = array();
            $widgets[0] = "Pilih Widget Type ..";
            if($dt_widget_type)
            {
                foreach($dt_widget_type as $type)
                {
                    $widgets[$type['id']] = isset($type['name']) ? $type['name'] : '';
                }
            }

            if($id != null)
            {
                $params = array('id' => $id);
                $rawPath = "qinternal/get/widget";
                $dt_widget = new QIAPI($this->generateParam($params, $rawPath));
                $dt_widget->send();
                $dt_widget = $dt_widget->read();
                $data['widget'] = $dt_widget;
            }

            $rawPath = 'microsite/get/survey';
            $generatedParam = $this->generateParam(null, $rawPath);
            $dt_survey = new QIAPI($generatedParam);
            $dt_survey->send();
            $dt_survey = $dt_survey->read();

            $surveys = array();
            $surveys[0] = "Pilih Survey Key ..";
            if($dt_widget_type)
            {
                foreach($dt_survey as $type)
                {
                    $surveys[$type['id']] = isset($type['key']) ? $type['key'] : '';
                }
            }

            $data['surveys'] = $surveys;
            $data['games'] = $games;
            $data['type'] = $widgets;

            $this->layout = View::make('layout/popup', array(
                "title" => "Add Widget"
            ))->nest('form_popup', 'qinternal.widget.form_add', array(
                "data"          => $data,
                "url_form"      => $this->generate_link(GROUP_INTERNAL . ".addWidget")
            ));
        }

        public function submit()
        {
            $result = new stdClass;
            $session = Session::get('qeon_session');
            $inputs = Input::all();
            $extra_params = array();

            if(isset($inputs["category_id"]) && !empty($inputs["category_id"])) $extra_params["category_id"] = $inputs["category_id"];
            if(isset($inputs["widget_type_id"])) $extra_params["widget_type_id"] = $inputs["widget_type_id"];
            if(isset($inputs["title"])) $extra_params["title"] = $inputs["title"];
            if(isset($inputs["description"])) $extra_params["description"] = $inputs["description"];
            if(isset($inputs["button_label"])) $extra_params["label"] = $inputs["button_label"];
            if(isset($inputs["target_url"])) $extra_params["link"] = $inputs["target_url"];
            if(isset($inputs["priority_level"])) $extra_params["priority_level"] = $inputs["priority_level"];
            if(isset($inputs["survey_id"])) $extra_params["survey_id"] = $inputs["survey_id"];
            if(isset($inputs["is_default"])) $extra_params["is_default"] = $inputs["is_default"];
            if(isset($inputs['start_date']) && isset($inputs['start_hour']) && isset($inputs['start_minute']))
            {
                $extra_params["start_date"] = $inputs['start_date'] . " " . $inputs['start_hour'] . ":" . $inputs['start_minute'] . ":00";
            }

            if(isset($inputs['end_date']) && isset($inputs['end_hour']) && isset($inputs['end_minute']))
            {
                $extra_params["end_date"] = $inputs['end_date'] . " " . $inputs['end_hour'] . ":" . $inputs['end_minute'] . ":00";
            }

            if(isset($inputs["widget_id"]) && $inputs["widget_id"] != null) $extra_params["id"] = $inputs["widget_id"];

            $rawPath = "qinternal/insert/widget";
            $generatedParam = $this->generateParam($extra_params, $rawPath);
            $insert = new QIAPI($generatedParam);
            $insert->send();

            $result->status = $insert->getStatus();
            $result->data = $insert->read();

            $this->layout = View::make('layout/blank', array(
                "content"   => json_encode(array("result" => $result))
            ));
        }

        public function delete($id = null)
        {
            $status = false;
            $msg = null;
            if($id != null)
            {
                $extra_params = array(
                    'id' => $id
                );
                $rawPath = "qinternal/delete/widget";
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
                'content'   => json_encode(array(
                    'status'    => $status,
                    'message'   => $msg
                ))
            ));
        }
	}

?>