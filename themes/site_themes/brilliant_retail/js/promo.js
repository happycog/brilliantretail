var timer 			= ''; 	// Timer for rotating the promo 
var promoCurrent 	= 0;
var promoIndex 		= 10;
var promoDelay 		= 8000;
var promoLimit 		= 0;

$(function(){ 
	var slider = $('.hp_slider');
	promoLimit = slider.length - 1;
	if(promoLimit >= 1){
		timer = setTimeout('showNext()',promoDelay);
		$('.hp_slider:gt(0)').hide(); 
		for(i=0;i<=promoLimit;i++){
			$('<img id="btn_'+i+'" class="promo_button" src="{theme}/images/0.gif" alt="" />').appendTo($('#promo_control'));
		}
		$('#promo_control .promo_button:eq(0)').addClass('active');
		$('#promo_control .promo_button').bind('click',setButton);
	}
	
});
function showNext(){
	// Clear our current timer
	clearTimeout(timer);
	btnSel = promoCurrent;
	promoCurrent < promoLimit ? promoCurrent = promoCurrent + 1 : promoCurrent = 0; 
	$('.hp_slider:eq('+promoCurrent+')').show('slide',{direction:'right'},500,function(){
		timer = setTimeout('showNext()',promoDelay);
		$('.hp_slider:eq('+btnSel+')').css({'display':'none','zIndex':promoIndex});
		promoIndex++;
	});	
}
function showPrev(){
	clearTimeout(timer);
	btnSel = promoCurrent;
	promoCurrent > 0 ? promoCurrent = promoCurrent - 1 : promoCurrent = promoLimit; 
	$('.hp_slider:eq('+promoCurrent+')').show('slide',{direction:'left'},500,function(){
		timer = setTimeout('showNext()',promoDelay);
		$('.hp_slider:eq('+btnSel+')').css({'display':'none','zIndex':promoIndex});
		promoIndex++;
	});	
}
function setButton(){
	clearTimeout(timer);
	var a = $(this);
	var b = a.attr('id').split('_');
	var pid = b[1];
	$('#promo_control .promo_button').removeClass('active');
	a.addClass('active');
	
	a.addClass('active');
	if(promoCurrent > pid){
		promoCurrent = (pid > 0) ? (pid - 1) : (promoLimit - 1) ;
		showPrev();
	}
	if(promoCurrent < pid){
		promoCurrent = (pid > 0) ? (pid - 1) : (promoLimit - 1) ;
		showNext();
	}
	timer = setTimeout('showNext()',promoDelay);
	return false;
}