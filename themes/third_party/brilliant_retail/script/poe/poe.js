$(function(){
	$('.cke_skin_kama a')
		.bind('click',function(){
			var a = $(this);
			var b = a.find('input');
			if(b.val() == 'y'){
				$(this).removeClass('poe_highlight');
				b.val('');
			}else{
				$(this).addClass('poe_highlight');
				b.val('y');
			}
			return false;
		});
});