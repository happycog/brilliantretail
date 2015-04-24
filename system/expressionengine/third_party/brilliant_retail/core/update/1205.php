<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/************************************************************/
/*	BrilliantRetail 										*/
/*															*/
/*	@package	BrilliantRetail								*/
/*	@Author		David Dexter  								*/
/* 	@copyright	Copyright (c) 2010-2015						*/
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

// Update UK Counties (States) per bug report: http://bugs.brilliantretail.com/tracker/view.php?id=191

	// First drop the old entries
		$sql[] = "DELETE FROM exp_br_state WHERE zone_id = 234;";
	
	// Insert the new list
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ABE','Aberdeen City');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ABD','Aberdeenshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ANS','Angus');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ANT','Antrim');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ARD','Ards');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'AGB','Argyll and Bute');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ARM','Armagh');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BLA','Ballymena');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BLY','Ballymoney');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BNB','Banbridge');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BNE','Barnet (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BNS','Barnsley (South Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BAS','Bath and North East Somerset (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BDF','Bedfordshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BFS','Belfast');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BEX','Bexley (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BIR','Birmingham (West Midlands district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BBD','Blackburn with Darwen');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BPL','Blackpool');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BGW','Blaenau Gwent');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BOL','Bolton (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BMH','Bournemouth');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BRC','Bracknell Forest');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BRD','Bradford (West Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BEN','Brent (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BGE','Bridgend');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BNH','Brighton and Hove');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BST','Bristol');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BRY','Bromley (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BKM','Buckinghamshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'BUR','Bury (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CAY','Caerphilly');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CLD','Calderdale (West Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CAM','Cambridgeshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CMD','Camden (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CRF','Cardiff');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CMN','Carmarthenshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CKF','Carrickfergus');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CSR','Castlereagh');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CGN','Ceredigion');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CHS','Cheshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CLK','Clackmannanshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CLR','Coleraine');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CWY','Conwy');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CKT','Cookstown');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CON','Cornwall (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'COV','Coventry (West Midlands district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CGV','Craigavon');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CRY','Croydon (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'CMA','Cumbria (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DAL','Darlington (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DEN','Denbighshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DER','Derby');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DBY','Derbyshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DRY','Derry');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DEV','Devon (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DNC','Doncaster (South Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DOR','Dorset (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DOW','Down');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DUD','Dudley (West Midlands district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DGY','Dumfries and Galloway');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DND','Dundee City');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DGN','Dungannon');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'DUR','Durham');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'EAL','Ealing (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'EAY','East Ayrshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'EDU','East Dunbartonshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ELN','East Lothian');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ERW','East Renfrewshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ERY','East Riding of Yorkshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ESX','East Sussex (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'EDH','Edinburgh');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ELS','Eilean Siar');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ENF','Enfield (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ESS','Essex (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'FAL','Falkirk');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'FER','Fermanagh');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'FIF','Fife');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'FLN','Flintshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'GAT','Gateshead');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'GLG','Glasgow City');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'GLS','Gloucestershire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'GRE','Greenwich (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'GWN','Gwynedd');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HCK','Hackney (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HAL','Halton (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HMF','Hammersmith and Fulham (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HAM','Hampshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HRY','Haringey (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HRW','Harrow (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HPL','Hartlepool (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HAV','Havering (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HEF','Herefordshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HRT','Hertfordshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HLD','Highland');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HIL','Hillingdon (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'HNS','Hounslow (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'IVC','Inverclyde');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'AGY','Isle of Anglesey');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'IOW','Isle of Wight (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'IOS','Isles of Scilly');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ISL','Islington (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'KEC','Kensington and Chelsea (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'KEN','Kent (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'KHL','Kingston upon Hull');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'KTT','Kingston upon Thames (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'KIR','Kirklees (West Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'KWL','Knowsley (metropolitan borough of Merseyside)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LBH','Lambeth (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LAN','Lancashire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LRN','Larne');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LDS','Leeds (West Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LCE','Leicester (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LEC','Leicestershire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LEW','Lewisham (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LMV','Limavady');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LIN','Lincolnshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LSB','Lisburn');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LIV','Liverpool');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LND','London');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'LUT','Luton (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MFT','Magherafelt');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MAN','Manchester (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MDW','Medway (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MTY','Merthyr Tydfil');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MRT','Merton (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MDB','Middlesbrough (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MID','Middlesex');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MLN','Midlothian');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MIK','Milton Keynes');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MON','Monmouthshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MRY','Moray');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'MYL','Moyle');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NTL','Neath Port Talbot');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NET','Newcastle upon Tyne');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NWM','Newham (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NWP','Newport');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NYM','Newry and Mourne');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NTA','Newtownabbey');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NFK','Norfolk (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NAY','North Ayrshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NDN','North Down NIR');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NEL','North East Lincolnshire (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NLK','North Lanarkshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NLN','North Lincolnshire (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NSM','North Somerset (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NTY','North Tyneside (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NYK','North Yorkshire (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NTH','Northamptonshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NBL','Northumberland');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NGM','Nottingham');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'NTT','Nottinghamshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'OLD','Oldham (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'OMH','Omagh');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ORK','Orkney Islands');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'OXF','Oxfordshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'PEM','Pembrokeshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'PKN','Perth and Kinross');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'PTE','Peterborough (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'PLY','Plymouth');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'POL','Poole');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'POR','Portsmouth');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'POW','Powys');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RDG','Reading');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RDB','Redbridge (London Borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RCC','Redcar and Cleveland');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RFW','Renfrewshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RCT','Rhondda Cynon Taf');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RIC','Richmond upon Thames (London Borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RCH','Rochdale (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ROT','Rotherham (South Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'RUT','Rutland (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SLF','Salford (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SAW','Sandwell (West Midlands district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SCB','Scottish Borders');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SFT','Sefton (Merseyside borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SHF','Sheffield (South Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'ZET','Shetland Islands');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SHR','Shropshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SLG','Slough (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SOL','Solihull (West Midlands district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SOM','Somerset (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SAY','South Ayrshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SGC','South Gloucestershire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SLK','South Lanarkshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STY','South Tyneside');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STH','Southampton');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SOS','Southend-on-Sea');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SWK','Southwark (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SHN','St Helens (Merseyside borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STS','Staffordshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STG','Stirling');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SKP','Stockport (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STT','Stockton-on-Tees');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STE','Stoke-on-Trent (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STB','Strabane');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SFK','Suffolk (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'GB-SND','Sunderland');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SRY','Surrey (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'STN','Sutton (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SWA','Swansea');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'SWD','Swindon');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'TAM','Tameside (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'TFW','Telford and Wrekin (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'THR','Thurrock (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'TOB','Torbay');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'TOF','Torfaen');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'TWH','Tower Hamlets (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'TRF','Trafford (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'VGL','Vale of Glamorgan');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WKF','Wakefield (West Yorkshire district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WLL','Walsall (West Midlands district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WFT','Waltham Forest (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WND','Wandsworth (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WRT','Warrington (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WAR','Warwickshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WBK','West Berkshire (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WDU','West Dunbartonshire');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WLN','West Lothian');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WSX','West Sussex (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WSM','Westminster (London borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WGN','Wigan (Manchester borough)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WIL','Wiltshire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WNM','Windsor and Maidenhead (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WRL','Wirral (metropolitan borough of Merseyside)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WOK','Wokingham (unitary authority)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WLV','Wolverhampton (West Midlands district)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WOR','Worcestershire (county)');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'WRX','Wrexham');";
		$sql[] = "INSERT INTO exp_br_state (zone_id,code,title) VALUES (234,'YOR','York (unitary authority)');";