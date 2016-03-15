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

	class dashboard extends BaseController
	{
		function home()
		{
			$this->layout->title = "Qeon Interactive";
			$this->layout->content = View::make('layout.content')->nest('actual_content', 'crm.dashboard.home');
		}
	}

?>