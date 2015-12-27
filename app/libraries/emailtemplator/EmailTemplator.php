<?php

namespace Ecne\EmailTemplator;

class EmailTemplator extends BaseEmailTemplate
{
	/**
	 * @param $to
	 * @param $from
	 * @param $subject
	 */
	function __construct($to, $from, $subject)
	{
		parent::__construct($to, $from, $subject);
	}

	public function splash()
	{
		echo $this->output();
	}
}