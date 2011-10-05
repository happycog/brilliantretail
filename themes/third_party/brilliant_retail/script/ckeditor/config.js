/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.removePlugins = 'scayt';
	config.extraPlugins = 'products';
    
    config.toolbar = 'MyToolbar';
    config.resize_enabled = false;
    config.height = '100px';
    config.toolbar_MyToolbar =
    [
        ['Source','Cut','Copy','Paste','PasteText','PasteFromWord'],
	    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	    ['Bold','Italic','Underline'], 
	    ['Strike','-','Subscript','Superscript'],
	    '/',
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['Image','Link','Unlink','Anchor'],
	    '/',
	    ['Format','Font','FontSize'],
	    ['TextColor','BGColor'] 
	];
};

CKEDITOR.plugins.load('pgrfilemanager');