define(['jquery.easyui.app', 'common/method'], function($, method) {
	var module = {
		option: {
			propertygrid: null
		},

		init: function(e){
			this.option.propertygrid = e;

			method.propertygrid.init(e, this);
		},

		//对应php代码controller中的action名称
		action: {
			//保存
			settingSave: function(e, row, rows){
				var href = $(e).data('href');
				var data = [];
				var rows = $(module.option.propertygrid).propertygrid('getChanges');
				for(var i=0; i<rows.length; i++){
					data.push({'name': rows[i]['key'], 'value': rows[i]['value'], 'type': (rows[i]['type'] || '')});
				}

				if(data.length == 0){
					method.messager.tip(language.public_data_change, 'error');
					return false;
				}

				method.request.post(href, {info: data}, function(){
					module.handle.refresh();
				});
			},

			//恢复
			settingReset: function(e, row, rows){
				var href = $(e).data('href');

				$.messager.confirm(language.public_system_hint, language.public_problem_continue, function(res) {
					if(!res) return false;

					method.request.post(href, {}, function(){
						module.handle.refresh();
					});
				});
			}
		},

		//其他操作
		handle: {
			//刷新
			refresh: function(){
				$(module.option.propertygrid).propertygrid({pageNumber: 1});
			}
		}
	};

	return module;
});
