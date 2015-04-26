<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
/* 	@license	http://opensource.org/licenses/OSL-3.0	*/
/* 	@link		http://brilliantretail.com 					*/
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

// Get the current highest order_id
  	$qry = $this->EE->db->query("SELECT order_id FROM exp_br_order ORDER BY order_id DESC LIMIT 1");
  	if($qry->num_rows() != 1)
  	{
  	 $order_id = 10000;
  	}
  	else
  	{
        $row = $qry->row();
        $order_id = $row->order_id + 1;
    }
  	
// Create a config option
	$qry = $this->EE->db->query("INSERT INTO exp_br_config (title,label,code,type,enabled,descr,version,sort) VALUES ('Order ID', '', 'order_id', 'system', '1', '', '', '')");
    $id = $this->EE->db->insert_id();

// Create the config data row
    $this->EE->db->query("INSERT INTO 
                            exp_br_config_data 
                                (
                                    config_id,
                                    label,
                                    code,
                                    type,
                                    value,
                                    options,
                                    descr,
                                    required,
                                    sort
                                )
                            VALUES 
                                (
                                    '".$id."', 'Order ID', '', '', '".$order_id."', null, null, '0', '0'
                                )
                            ");