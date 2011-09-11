<?php
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		Brilliant2.com 								*/
/* 	@copyright	Copyright (c) 2010, Brilliant2.com 			*/
/* 	@license	http://brilliantretail.com/license.html		*/
/* 	@link		http://brilliantretail.com 					*/
/* 	@since		Version 1.0.0 Beta							*/
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

function clear_quotes($d) {
	return str_replace("\"", "", $d);
}

function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
$arrData = array();

// if input is object, convert into array
if (is_object($arrObjData)) {
    $arrObjData = get_object_vars($arrObjData);
}

if (is_array($arrObjData)) {
    foreach ($arrObjData as $index => $value) {
        if (is_object($value) || is_array($value)) {
            $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
        }
        if (in_array($index, $arrSkipIndices)) {
            continue;
        }
        $arrData[$index] = $value;
    }
}
return $arrData;
}


function create_date() {
	$dte = date("YdmGis000000O");
    //return date_format($dte,"YYYYDDMMHHNNSSKKK000sOOO");
    return $dte;
}

function cc_type_number($cc_number) {
 	$card_type = "";
 	$card_regexes = array(
       "/^4\d{12}(\d\d\d){0,1}$/" => "Visa",
       "/^5[12345]\d{14}$/"       => "MasterCard",
       "/^3[47]\d{13}$/"          => "AmEx",
       "/^6011\d{12}$/"           => "Discover",
       "/^30[012345]\d{11}$/"     => "DinersClub",
       "/^3[68]\d{12}$/"          => "DinersClub",
    );

 	foreach ($card_regexes as $regex => $type) {
    		if (preg_match($regex, $cc_number)) {
        		$card_type = $type;
        		break;
    		}
 	}
 	return $card_type;
 }