/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'products',
{
	requires : [ 'dialog' ],
	init : function( editor )
	{
		var command = editor.addCommand( 'products', new CKEDITOR.dialogCommand( 'products' ) );
		command.modes = { wysiwyg:1, source:1 };
		command.canUndo = true;

		editor.ui.addButton( 'GetProducts',
			{
				label : 'Get Products',
				command : 'products',
				icon: this.path + "images/products.gif"

			});

		CKEDITOR.dialog.add( 'products', this.path + 'dialogs/products.js' );
	}
});
