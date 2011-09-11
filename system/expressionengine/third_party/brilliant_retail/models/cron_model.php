<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.3.2								*/
/*															*/
/************************************************************/
/* NOTICE													*/
/*															*/
/* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF 	*/
/* ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED	*/
/* TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 		*/
/* PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT 		*/
/* SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY */
/* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION	*/
/* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR 	*/
/* IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 		*/
/* DEALINGS IN THE SOFTWARE. 								*/	
/************************************************************/

class Cron_model extends CI_Model {

	/**
	 * Get Cron
	 *
	 * Get the current list of cron tasks
	 *
	 * @access public
	 * @return array 
	 */

	public function get_tasks(){
		$tasks = array();
		$this->db->from('br_cron');
		$this->db->where('status',1);
		$qry = $this->db->get();
		$i = 0;
		foreach($qry->result() as $row){
			$tasks[$i] = array(
								"class" 	=> $row->class, 
								"method"	=> $row->method, 
								"params"	=> $row->params
							);
			$i++;
		}
		return $tasks;
	}
}