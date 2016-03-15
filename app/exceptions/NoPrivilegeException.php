<?php

namespace QInterface\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use View;

class NoPrivilegeException extends HttpException
{
	public function __construct($error = null)
    {
        var_dump($error);
        $this->show_error($error);
    }

    public function show_error($error = null)
    {
    	switch($error)
    	{
    		case 401:
    			View::make("errors.noPrivilege");
    			break;
    	}
    }
}