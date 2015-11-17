<?php

namespace Calctool\Other;

class Calctool {

	public function remoteAddr()
	{
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
			return $_SERVER["HTTP_CF_CONNECTING_IP"];
		else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		else
			return $_SERVER['REMOTE_ADDR'];
	}

}