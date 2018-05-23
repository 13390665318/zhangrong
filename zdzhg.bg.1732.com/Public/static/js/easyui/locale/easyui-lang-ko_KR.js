if ($.fn.pagination){
    $.fn.pagination.defaults.beforePageText = '';
    $.fn.pagination.defaults.afterPageText = '총 {pages} 페이지';
    $.fn.pagination.defaults.displayMsg = '{from}-{to},총 {total} 개 기록';
}
if ($.fn.datagrid){
    $.fn.datagrid.defaults.loadMsg = '처리중 입니다, 잠시만 기다려 주세요。。。';
}
if ($.fn.treegrid && $.fn.datagrid){
    $.fn.treegrid.defaults.loadMsg = $.fn.datagrid.defaults.loadMsg;
}
if ($.messager){
    $.messager.defaults.ok = '확인';
    $.messager.defaults.cancel = '취소';
}
$.map(['validatebox','textbox','filebox','searchbox',
    'combo','combobox','combogrid','combotree',
    'datebox','datetimebox','numberbox',
    'spinner','numberspinner','timespinner','datetimespinner'], function(plugin){
    if ($.fn[plugin]){
        $.fn[plugin].defaults.missingMessage = '필수 입력 항목 입니다.';
    }
});
if ($.fn.validatebox){
    $.fn.validatebox.defaults.rules.email.message = '유효한 이메일을 입력해주세요.';
    $.fn.validatebox.defaults.rules.url.message = '유효한 URL주소를 입력해주세요.';
    $.fn.validatebox.defaults.rules.length.message = '입력 내용의 길이는 {0}과{1}사이여야 합니다.';
    $.fn.validatebox.defaults.rules.remote.message = '내용을 수정해주세요';
}
if ($.fn.calendar){
    $.fn.calendar.defaults.weeks = ['일','월','화','수','목','금','토'];
    $.fn.calendar.defaults.months = ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'];
}
if ($.fn.datebox){
    $.fn.datebox.defaults.currentText = '오늘';
    $.fn.datebox.defaults.closeText = '닫기';
    $.fn.datebox.defaults.okText = '확인';
    $.fn.datebox.defaults.formatter = function(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
    };
    $.fn.datebox.defaults.parser = function(s){
        if (!s) return new Date();
        var ss = s.split('-');
        var y = parseInt(ss[0],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[2],10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d);
        } else {
            return new Date();
        }
    };
}
if ($.fn.datetimebox && $.fn.datebox){
    $.extend($.fn.datetimebox.defaults,{
        currentText: $.fn.datebox.defaults.currentText,
        closeText: $.fn.datebox.defaults.closeText,
        okText: $.fn.datebox.defaults.okText
    });
}
if ($.fn.datetimespinner){
    $.fn.datetimespinner.defaults.selections = [[0,4],[5,7],[8,10],[11,13],[14,16],[17,19]]
}
