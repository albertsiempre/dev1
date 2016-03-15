<?php
    class QIAPI_HELPER
    {
    	public static function generateParam($extra_param = array(), $rawPath = null)
    	{
    		$session = Session::get('qeon_session');
			$basic = array(
				'user_agent'   => Useragent::agent_string(),
	            'ip_address'   => Request::getClientIp(),
	            'session'      => $session['_auth']['session'],
	            'symkey'       => $session['_auth']['symkey']
			);

			if(!is_null($rawPath))
			{
				$basic["rawPath"] = $rawPath;
			}

			return array_merge($basic, array(
				'extra_param'	=>	$extra_param
			));
    	}
    }
?>