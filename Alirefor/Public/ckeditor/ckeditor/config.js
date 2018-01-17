/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// 背景颜色
    //工具栏默认是否展开
    config.toolbarStartupExpanded = false;
	//工具栏的位置
    config.toolbarLocation = 'top';//可选：bottom
	//是否使用HTML实体进行输出 plugins/entities/plugin.js
    config.entities = false;

	//是否对编辑区域进行渲染 plugins/editingblock/plugin.js
    config.editingBlock = false;
	//是否转换一些难以显示的字符为相应的HTML字符 plugins/entities/plugin.js
    config.entities_greek = false;
    //是否转换一些拉丁字符为HTML plugins/entities/plugin.js
    config.entities_latin = false;
	//是否使用完整的html编辑模式 如使用，其源码将包含：<html><body></body></html>等标签
    config.fullPage = false;
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for a single toolbar row.
	config.toolbarGroups = [
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'forms' },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'tools' },
		{ name: 'others' },
		{ name: 'about' }
	];

	// The default plugins included in the basic setup define some buttons that
	// are not needed in a basic editor. They are removed here.
	config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript';

	// Dialog windows are also simplified.
	config.removeDialogTabs = 'link:advanced';
};
