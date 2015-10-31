<?php

namespace Ecne\EmailTemplator;

class BaseEmailTemplate
{
	protected $html;
	protected $elements = array();

	protected $body = null;
	protected $to = null;
	protected $from = null;
	protected $headers = array('MIME-Version: 1.0\r\n', 'Content-Type: text/html; charset=ISO-8859-1\r\n');
	protected $subject = null;

	function __construct($to, $from, $subject)
	{
		$this->to = $to;
		$this->from = $from;
		$this->subject = $subject;
		return $this;
	}

	protected function addElement($element)
	{
		$this->elements[] = $element;
	}

	protected function addHeader($header)
	{
		$this->headers[] = $header;
	}

	protected function parse()
	{
		foreach($this->elements as $element) {
			$this->html .= $element;
		}
	}

	protected function output()
	{
		$this->parse();
		return $this->html;
	}

	/**
	 *	public methods...
	 *
	 *
	 */
	public function h($string, $size)
	{
		$this->addElement('<h'.$size.'>'.$string.'</h'.$size.'>');
		return $this;
	}
	public function p($string)
	{
		$this->addElement('<p>'.$string.'</p>');
		return $this;
	}
	public function strong($string)
	{
		$this->addElement('<strong>'.$string.'</strong>');
		return $this;
	}
	public function em($string)
	{
		$this->addElement('<em>'.$string.'</em>');
		return $this;
	}
	public function ul($list = array())
	{
		$str = '<ul>';
		if (count($list)) {
			foreach($list as $item) {
				$str .= '<li>'.$item.'</li>';
			}
		}
		$html .= '</ul>';
		return $this;
	}

	public function ol($list = array())
	{
		$str = '<ol>';
		if (count($list)) {
			foreach($list as $item) {
				$str .= '<li>'.$item.'</li>';
			}
		}
		$html .= '</ol>';
		return $this;
	}
}