define(['jquery.easyui.app', 'common/method'], function($, method) {
	var module = {
		option: {
			datagrid: null
		},

		init: function(e){
			this.option.datagrid = e;

			method.datagrid.init(e, this);
		},

		//对应php代码controller中的action名称
		action: {
			userAdd: function(e, row, rows){
				method.dialog.form(e, {
					width : 400,
					height: 320
				}, function(){
					module.handle.refresh();
				});
			},

			userEdit: function(e, row, rows){
				if(!row){
					method.messager.tip(language.public_unselected_data, 'error');
					return false;
				}

				var href = $(e).data('href');
				href += href.indexOf('?') != -1 ? '&id=' + row.userid : '?id=' + row.userid;

				method.dialog.form(e, {
					width : 400,
					height: 250,
					href  : href
				}, function(){
					module.handle.refresh();
				});
			},

			userDelete: function(e, row, rows){
				if(!row){
					method.messager.tip(language.public_unselected_data, 'error');
					return false;
				}
				var href = $(e).data('href');
				var ids  = [];
				for(var i = 0; i < rows.length; i++){
					ids.push(rows[i]['userid']);
				}

				$.messager.confirm(language.public_system_hint, language.public_problem_continue, function (res) {
					if(!res) return false;

					method.request.post(href, {ids: ids.join(',')}, function(){
						module.handle.refresh();
					});
				});
			},

			userReset: function(e, row, rows){
				if(!row){
					method.messager.tip(language.public_unselected_data, 'error');
					return false;
				}

				var href = $(e).data('href');
				$.messager.confirm(language.public_system_hint, language.public_problem_continue, function (res) {
					if(!res) return false;

					method.request.post(href, {id: row.userid}, function(res){
						$.messager.alert(language.public_prompt_information, language.public_psw_reset + res.password + language.public_psw_new, 'info');
					});
				});
			}
		},

		//其他操作
		handle: {
			//刷新
			refresh: function(){
				$(module.option.datagrid).datagrid('reload');
			}
		}
	};

	return module;
});
