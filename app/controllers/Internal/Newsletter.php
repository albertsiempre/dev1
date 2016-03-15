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

	class Newsletter extends BaseController
	{
 		public function main()
		{                
            $data['url_download_verified_email']     = $this->generate_link(GROUP_INTERNAL . '.download_verified_email');           
            $data['url_download_read_email']         = $this->generate_link(GROUP_INTERNAL . '.download_read_email');           
			$this->layout->title                     = "Qeon Interactive";

            $param                                   = array();
            $rawPath                                 = 'qinternal/get/newsletter/verified_email';
            $generatedParam                          = $this->generateParam($param, $rawPath);
            $dt_request                              = new QIAPI($generatedParam); $dt_request->send();
            $dt_request                              = $dt_request->read();
            $data['total']                           = isset($dt_request['total']) ? $dt_request['total'] : 0 ;
            $data['limit']                           = isset($dt_request['limit']) ? $dt_request['limit'] : 0 ;
            $data['part']                            = floor(($data['total'] / $data['limit']) + 1);
            
			$this->layout->content                   = View::make('layout.content')->nest('actual_content', 'qinternal.newsletter.download_email.main', $data);
		}

        public function ReportBounce()
        {
            $data                                   = array();            
            $this->layout->title                    = "Qeon Interactive";
            $curdate                                = date('Y-m-d');
            $start_date                             = date('Y-m-d', strtotime('-10 days', strtotime($curdate)));
            $end_date                               = $curdate;
            $this->layout->content                  = View::make('layout.content')->nest('search_content', 'layout.search', array(
                "data_form" => array(
                    "action"    => $this->generate_link(GROUP_INTERNAL . '.list_bounce'),
                    "form"      => array(                                            
                                        'start_date' => array(
                                            'type' => 'input',
                                            'data' => array(
                                                'name' => 'start_date',
                                                'class' => '_start_date',
                                                "value" => $start_date
                                            ),
                                            'validation' => 'required',
                                            'label' => 'Start Date'
                                        ),
                                        'end_date' => array(
                                            'type' => 'input',
                                            'data' => array(
                                                'name' => 'end_date',
                                                'class' => '_end_date',
                                                "value" => $end_date
                                            ),
                                            'validation' => 'required',
                                            'label' => 'End Date'
                                        )
                    )
                )
            ))->nest('actual_content', 'qinternal.newsletter.report_bounce.main', $data);
        }

        public function ReportBounceList()
        {
            $param              = array();
            $rawPath            = 'qinternal/get/newsletter/bounce_email';
            $param["limit"]     = \LIMIT_PAGING;
            $curdate            = date('Y-m-d');
            $start_date         = date('Y-m-d', strtotime('-10 days', strtotime($curdate)));
            $end_date           = $curdate;

            $param_start_date   = Input::get("start_date");
            if($param_start_date != null && !empty($param_start_date))
            {
                $param["start_date"] = $param_start_date;
            }else{
                $param["start_date"] = $start_date;
            }
                        
            $param_end_date = Input::get("end_date");                        
            if($param_end_date != null && !empty($param_end_date))
            {
                $param["end_date"] = $param_end_date;
            }else{
                $param["end_date"] = $end_date;
            }

            $generatedParam = $this->generateParam($param, $rawPath);
            $dt_request     = new QIAPI($generatedParam);
            $dt_request->send();
            $dt_request     = $dt_request->read();
            $result         = isset($dt_request) && $dt_request != 'success call' ? $dt_request : array();
            $pages          = 1;//isset($dt_request["pages"]) ? $dt_request["pages"] : null;

            $this->layout = View::make('layout.search_result', array('pages' => $pages, "total" => count($result)))
                            ->nest('search_result', 'qinternal.newsletter.report_bounce.list', array('result' => $result));
        }

        public function DownloadVerifiedEmail()
        {
            header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
            header("Content-Disposition: attachment; filename=email_".date("d-M-y").".xls");  //File name extension was wrong
            header("Expires: 0");
            header("Cache-Control: cache, must-revalidate");
            header("Cache-Control: private",false);
            header("Pragma: public");

            $param          = array();
            $page           = Input::get('page');
            if($page != null){
                $param["page"] = $page;
            }
            $rawPath        = 'qinternal/get/newsletter/verified_email';
            $generatedParam = $this->generateParam($param, $rawPath);
            $dt_request     = new QIAPI($generatedParam); $dt_request->send();
            $dt_request     = $dt_request->read();
            $data['total']  = isset($dt_request['total']) ? $dt_request['total'] : 0 ;
            $data['limit']  = isset($dt_request['limit']) ? $dt_request['limit'] : 0 ;
            $data['part']   = floor(($data['total'] / $data['limit']) + 1);
            $data['emails'] = isset($dt_request['emails']) ? $dt_request['emails'] : array() ;
            $this->layout   = View::make('qinternal.newsletter.download_email.verified_email', $data);
        }        

        public function DownloadReadEmail()
        {
            header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
            header("Content-Disposition: attachment; filename=read_email".date("d-M-y").".xls");  //File name extension was wrong
            header("Expires: 0");
            header("Cache-Control: cache, must-revalidate");
            header("Cache-Control: private",false);
            header("Pragma: public");

            $param          = array();
            $rawPath        = 'qinternal/get/newsletter/read_email';
            $generatedParam = $this->generateParam($param, $rawPath);
            $dt_request     = new QIAPI($generatedParam); $dt_request->send();
            $dt_request     = $dt_request->read();
            $data['emails'] = $dt_request;
            $this->layout   = View::make('qinternal.newsletter.download_email.verified_email', $data);
        }
	}
?>