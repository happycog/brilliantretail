CKEDITOR.dialog.add( 'products', function( editor ){
	return {
		title : 'Some Title',
		minWidth : 390,
		minHeight : 230,
		contents : [
			{
				id : 'tab1',
				label : '',
				title : '',
				expand : true,
				padding : 0,
				elements :
				[
					{
						type : 'html',
						html : 	'<div>' +
								'<p>Some Copyright</p>'+
								'</div>'
					}
				]
			}
		],
		buttons : [ CKEDITOR.dialog.cancelButton ]
	};
} );