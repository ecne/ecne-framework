<?php

namespace Ecne\EmailTemplator;

class BaseEmailTemplate
{
	/**
	 * @var string
	 */
	protected $html;
    /**
     * @var array
     */
	protected $elements = array();

    /**
     * @var null
     */
	protected $body = null;
    /**
     * @var null
     */
	protected $to = null;
    /**
     * @var null
     */
	protected $from = null;
    /**
     * @var array
     */
	protected $headers = array('MIME-Version: 1.0\r\n', 'Content-Type: text/html; charset=ISO-8859-1\r\n');
    /**
     * @var null
     */
	protected $subject = null;

    /**
     * @param $to
     * @param $from
     * @param $subject
     */
	function __construct($to, $from, $subject)
	{
		$this->to = $to;
		$this->from = $from;
		$this->subject = $subject;
		return $this;
	}

    /**
     * @param $element
     */
	protected function addElement($element)
	{
		$this->elements[] = $element;
	}

    /**
     * @param $header
     */
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

    /**
     * @return string
     */
	protected function output()
	{
		$this->parse();
		return $this->html;
	}

	# region public methods
    /**
     * @param $string
     * @param $size
     * @return $this
     */
	public function h($string, $size)
	{
		$this->addElement('<h'.$size.'>'.$string.'</h'.$size.'>');
		return $this;
	}

    /**
     * @param $string
     * @return $this
     */
	public function p($string)
	{
		$this->addElement('<p>'.$string.'</p>');
		return $this;
	}

    /**
     * @param $string
     * @return $this
     */
	public function strong($string)
	{
		$this->addElement('<strong>'.$string.'</strong>');
		return $this;
	}

    /**
     * @param $string
     * @return $this
     */
	public function em($string)
	{
		$this->addElement('<em>'.$string.'</em>');
		return $this;
	}

    /**
     * @param array $list
     * @return $this
     */
	public function ul($list = array())
	{
		$str = '<ul>';
		if (count($list)) {
			foreach($list as $item) {
				$str .= '<li>'.$item.'</li>';
			}
		}
		$str .= '</ul>';
        $this->addElement($str);
		return $this;
	}

    /**
     * @param array $list
     * @return $this
     */
	public function ol($list = array())
	{
		$str = '<ol>';
		if (count($list)) {
			foreach($list as $item) {
				$str .= '<li>'.$item.'</li>';
			}
		}
		$str .= '</ol>';
        $this->addElement($str);
		return $this;
	}
}   # End Class Definition