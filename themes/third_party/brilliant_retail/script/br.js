$(function(){
	$('#br_status_link').bind('click',function(){
		$('#b2r_status').slideUp();
		return false;
	});
	setTimeout("$('#b2r_status').slideUp()",7500);
	
	// Globally handle password inputs
		$('.cleartext')
			.bind('focus',function(){
					var a = $(this);
					if(a.val() == '************************'){
						a.val('');
					}
				})
			.bind('blur',function(){	
					var a = $(this);
					if(a.val() == ''){
						a.val('************************');
					}
				});
});