define(['jquery.easyui.app', 'common/method'], function($, method) {
	var module = {
		option: {
			datagrid: null
		},

		init: function(e){
			this.option.datagrid = e;

			method.datagrid.init(e, this);
		},

		//点击操作
		onClickCell: function(index, field, value){
			switch(field){
				//查看描述
				case 'description':
					module.handle.detail(value);
					break;
			}
		},

		//对应php代码controller中的action名称
		action: {
			//添加
			typeAdd: function(e, row, rows){
				method.dialog.form(e, {
					width : 400,
					height: 290
				}, function(){
					module.handle.refresh();
				});
			},

			//编辑
			typeEdit: function(e, row, rows){
				if(!row){
					method.messager.tip(language.public_unselected_data, 'error');
					return false;
				}

				var href = $(e).data('href');
				href += href.indexOf('?') != -1 ? '&id=' + row.typeid : '?id=' + row.typeid;

				method.dialog.form(e, {
					width : 400,
					height: 290,
					href  : href
				}, function(){
					module.handle.refresh();
				});
			},

			//删除
			typeDelete: function(e, row, rows){
				if(!row){
					method.messager.tip(language.public_unselected_data, 'error');
					return false;
				}
				var href = $(e).data('href');
				var ids  = [];
				for(var i = 0; i < rows.length; i++){
					ids.push(rows[i]['typeid']);
				}

				$.messager.confirm(language.public_system_hint, language.public_problem_continue, function (res) {
					if(!res) return false;

					method.request.post(href, {ids: ids.join(',')}, function(){
						module.handle.refresh();
					});
				});
			}
		},

		//其他操作
		handle: {
			//刷新
			refresh: function(){
				$(module.option.datagrid).datagrid('reload');
			},

			//查看参数详情
			detail: function(content){
				if(content.length < 50) return false;

				method.dialog.content(null, {
					title       : language.public_info_parameter,
					content     : '<p>' + content + '</p>',
					iconCls     : 'fa fa-file-o',
					width       : 350,
					height      : 200,
					maximizable : true,
					resizable   : true
				});
			}

		}
	};

	return module;
});
