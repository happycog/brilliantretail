<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2014						*/
/* 	@license	http://brilliantretail.com/license.html		*/
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

// Add performance index to the order table

$this->EE->load->library('api'); 
$this->EE->api->instantiate('channel_fields');
$this->EE->api->instantiate('channel_structure');

// First create a new fieldtype
// for each site
    $this->EE->load->model('core_model');
    
    $config = $this->EE->core_model->get_config();
    $prefix = $this->EE->db->dbprefix;
    
foreach($config["store"] as $key => $val)
{
    $channel = $this->EE->api_channel_structure->get_channel_info($val["channel_id"]);
    $field_group = $channel->result_array()[0]["field_group"];
    
    if($key == 1){
        $field_name = 'detail';        
    }else{
        $field_name = 'detail_'.$key;        
    }
             
    $data = array(
                    'site_id'               => $key,            // Site ID
                    'group_id'              => $field_group,    // (int)
                    'field_name'            => $field_name,     // (string a-zA-Z0-9_- only)
                    'field_label'           => 'Detail',        // (string)
                    'field_type'            => 'textarea',      // (string a valid fieldtype short name)
                    'field_order'           => 1,               // (int)
                    'field_instructions'    => '',
                    'field_is_hidden'       => 'n',
                    'field_required'        => 'n',             // Is it required?
                    'field_search'          => 'y',             // Can it be searched 
                    'field_is_hidden'       => 'n',             // (string y/n)
                    'field_fmt'             => 'none',
                    'field_show_fmt'        => 'n',
                    'field_text_direction'  => 'ltr',
                    'field_maxl'            => 128
                );

    $new_field = $this->EE->api_channel_fields->update_field($data);

    $products = $this->EE->db->query("  SELECT 
                                        	p.product_id,
                                        	pe.product_entry_id,
                                        	p.detail   
                                        FROM 
                                    	    ".$prefix."br_product p,
                                            ".$prefix."br_product_entry pe 
                                        WHERE 
                                    	    p.product_id = pe.product_id 
                                        AND  
                                            p.site_id = ".$key);
    
    foreach($products->result_array() as $p){
        $data = array(
                        "field_id_".$new_field => $p["detail"]
                    );
        $this->EE->db->where('entry_id',$p["product_entry_id"])->update($prefix."channel_data",$data);
    }
}