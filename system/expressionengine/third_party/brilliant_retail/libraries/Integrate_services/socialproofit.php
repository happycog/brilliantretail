<?php
class Socialproofit extends Integrate {
	function run()
	{
		$tmp = '<hr />';
		$tmp .= $this->EE->TMPL->tagdata;
		$tmp .= '<hr />';
		$tmp .= $this->EE->TMPL->fetch_param('please');
		$tmp .= '<hr />';
		$tmp .= $this->EE->TMPL->fetch_param('these');
		$tmp .= '<hr />';
		return $tmp;
	}
}