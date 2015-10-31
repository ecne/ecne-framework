<?php

namespace Ecne\EmailTemplator;

class EmailTemplator extends BaseEmailTemplate
{
	function __construct($to, $from, $subject)
	{
		parent::__construct($to, $from, $subject);
	}

	public function splash()
	{
		echo $this->output();
	}
}