/*公共JS模块 2015-04-03
已经添加到框架主页中，添加的公共方法在新的页面中可以直接使用
*/

/*********图表相关方法集合*********/

var myChart = null;  //图表变量

//清除图表数据
function clearChart(chartid) {
    if (myChart != null) {
        myChart.clear();
        myChart.dispose();
    }
    document.getElementById(chartid).innerHTML = "";
}

//显示图表加载提示消息
function ShowImagMask() {
    $(".datagrid-mask").css("display", "block");
    $(".datagrid-mask-msg").css("display", "block");
}

//隐藏图表加载提示信息
function CloseImagMask() {
    $(".datagrid-mask").css("display", "none");
    $(".datagrid-mask-msg").css("display", "none");
}

//绑定图标数据
//chartid:图表对应DIV的ID
//imgresult:图表数据参数
function LoadImagData(chartid, imgresult) {
    require.config({
        paths: {
            echarts: '/Public/static/js/chart/echarts',
            'echarts/chart/bar': '/Public/static/js/chart/echarts',
            'echarts/chart/line': '/Public/static/js/chart/echarts'
        }
    });
    require(
[
    'echarts',
    'echarts/chart/bar',
    'echarts/chart/line'
],
function (ec) {
    var option = eval('(' + imgresult + ')');
    myChart = ec.init(document.getElementById(chartid));
    myChart.setOption(option);
});
}


/*********窗口显示方法集合*********/
function ShowDialog(id, url) {
    if (url != null && url != "") {
        $("#" + id).dialog({ href: url });
    }
    $("#" + id).dialog("open");
}
/*********消息显示方法集合*********/
//显示进度条
function ShowProgress(msg) {
    $.messager.progress({ text: msg });
}
//隐藏进度条
function HideProgress() {
    $.messager.progress('close');
}

//显示错误提示框
function ShowErrorMsg(title,msg)
{
    ShowMsg(title, msg, 'error');
} 
//显示成功提示框
function ShowSuccessMsg(title,msg) {
    ShowMsg(title, msg, 'info');
} 
//显示确认提示框
function ShowQuestionMsg(msg) {
    ShowMsg('提示信息', msg, 'info');
}

//显示提示框（title：标题，msg：内容，icon：'error','info','question','warning'）
function ShowMsg(title, msg, icon) {
    $.messager.alert(title, msg, icon);
}
//显示提示框（title：标题，msg：内容，showposition：显示位置，showway：显示方式，show，slide，fade）
function ShowMsgBottomRight(title, msg, showposition, showway) {
    if (!showway) showway = "slide";
    var style = {};
    switch (showposition) {
        case "topleft":
            style = { right: '', left: 0, top: document.body.scrollTop + document.documentElement.scrollTop, bottom: '' };
            break;
        case "topcenter":
            style = { right: '', top: document.body.scrollTop + document.documentElement.scrollTop, bottom: '' };
            break;
        case "topright":
            style = { left: '', right: 0, top: document.body.scrollTop + document.documentElement.scrollTop, bottom: '' };
            break;
        case "centerleft":
            style = { left: 0, right: '', bottom: '' };
            break;
        case "center":
            style = { right: '', bottom: '' };
            break;
        case "centerright":
            style = { left: '', right: 0, bottom: '' };
            break;
        case "bottomleft":
            style = { left: 0, right: '', top: '', bottom: -document.body.scrollTop - document.documentElement.scrollTop };
            break;
        case "bottomcenter":
            style = { right: '', top: '', bottom: -document.body.scrollTop - document.documentElement.scrollTop };
            break;
        case "bottomright":
            style = {};
            break;
    }
    $.messager.show({
        title: title,
        msg: msg,
        showType: showway,
        style: style
    });
}

//快速显示消息框，右下角滑动方式（msg：内容，timer：显示持续时间，单位毫秒，wordcolor：消息字体颜色，fontsize：消息字体大小）
function QuickMsg(msg, timer, wordcolor, fontsize) {
    if (timer == null || timer == undefined || timer == "" || timer * 1 < 1000) {
        timer = 3000;
    }
    if (wordcolor == null || wordcolor == undefined || wordcolor == "") {
        wordcolor = "blue";
    }
    if (fontsize == null || fontsize == undefined || fontsize == "") {
        fontsize = "12";
    }

    var date = new Date().toLocaleTimeString();

    var showmsg = "<span style='color:" + wordcolor + ";font-size:" + fontsize + "px;'>" + msg + "</span>";


    $.messager.show({
        title: "<!--{$Think.lang.common_public_bg_tip}-->[" + date + "]",
        msg: showmsg,
        timeout: timer,
        showType: 'slide',
        showSpeed: 300
    });
}

/*********控件的值方法集合*********/

//获取combotree的值
function Getcombtreevalue(id)
{
    return $('#' + id).combotree('getValues');
}

//获取combobox的值
function Getcomboboxvalue(id) {
    return $('#' + id).combobox('getValue');
}
//获取combobox的值
function Getcomboboxtext(id) {
    return $('#' + id).combobox('getText');
}

//获取datebox的值
function Getdateboxvalue(id) {
    return $('#' + id).datebox('getValue');
}

//获取input的值
function Getinputvalue(id) {
    return $('#' + id).val();
}

/*********Dialog操作方法集合*********/
//打开窗体
function OpenDialog(dialogid) {
    $("#" + dialogid).dialog('open');
}
//关闭窗体
function CloseDialog(dialogid) {
    $("#" + dialogid).dialog('close');
}

/*********GRID操作方法集合*********/

//（grid的主键或唯一键必须为ID）取得选择列的ID结合，返回格式：1,2,3,4,5,6...（多选）
function getChooseIds(gridid) {
    var ss = "";
    var rows = $('#' + gridid).datagrid('getSelections');
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        ss += row.ID;
        if (i != rows.length - 1) {
            ss += ",";
        }
    }
    return ss;
}

//（grid的主键或唯一键必须为ID）取得选择列的ID（单选）
function getChooseId(gridid) {
    var row = $('#' + gridid).datagrid('getSelected');
    if (row) {
        return row.ID;
    }
}

//将Form表单的值传回服务器并重新加载Grid
function GridReload(gridid, formid) {
    var queryParams = $('#' + gridid).datagrid('options').queryParams;
    $.each($("#" + formid).form().serializeArray(), function (index) {
        queryParams[this['name']] = this['value'];
    });
    queryParams['queryrd'] = Math.random();
    $('#' + gridid).datagrid('load');
}

//将Form表单的值传回服务器并重新加载Grid，URL的方式
function GridUrlReload(gridid, formid, url, method, addrd) {
    var queryParams = $('#' + gridid).datagrid('options').queryParams;
    $.each($("#" + formid).form().serializeArray(), function (index) {
        queryParams[this['name']] = this['value'];
    });

    var queryrd = Math.random();

    //如果方法为null或未定义则默认post
    if (method == null || method == undefined || method == "") {
        method = "post";
    }

    //是否加一个随机数
    if (addrd == null || addrd == undefined || addrd == "") {
        addrd = false;
    }

    if (addrd) {
        //加上一个随机数，方便请求的时候不读取缓存
        if (url.indexOf("?") != -1) {
            url = url + "&queryrd=" + queryrd;
        }
        else {
            url = url + "?queryrd=" + queryrd;
        }
    }

    $('#' + gridid).datagrid({ url: url, method: method });
}

//检测ID是否存在
function CheckSingleId(id) {
    if (id == null || id == "") {
        ShowMsg("<!--{$Think.lang.common_tip}-->", "<!--{$Think.lang.common_public_selectr_tip}-->", 'warning');
        return false;
    }
    return true;
}

//获取URL中的参数,没有值返回null
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}



//获取选中的选项卡编号（参数id：选项卡ID）
function GetSelectedTabIndex(id) {
    return $("#" + id).tabs("getTabIndex", $("#" + id).tabs("getSelected"));
}

//将从datebox中获取的时间字符串转换为时间类型
function stringToTime(string) {
    var f = string.split(' ', 2);
    var d = (f[0] ? f[0] : '').split('-', 3);
    var t = (f[1] ? f[1] : '').split(':', 3);
    return (new Date(
   parseInt(d[0], 10) || null,
   (parseInt(d[1], 10) || 1) - 1,
    parseInt(d[2], 10) || null,
    parseInt(t[0], 10) || null,
    parseInt(t[1], 10) || null,
    parseInt(t[2], 10) || null
    )).getTime();
}


//加载图表数据，显示图表
function PublicLoadGridImag(controstr, paras, chartid) {
    //parastr例子：
    //btime=" + btime + "&etime=" + etime + "&serverid=" + serverid + "&channelnum=" + channelnum + "&platid=" + platid + "&gameid=" + gameid
    var parastr = "";
    for (var i = 0; i < paras.length; i++) {
        var mytxt = paras[i].split(":");
        if (i == (paras.length - 1)) {
            parastr = parastr + mytxt[0] + "=" + mytxt[1];
        }
        else {
            parastr = parastr + mytxt[0] + "=" + mytxt[1] + "&";
        }
    }

    var geturl = controstr + "&" + parastr;

    $.get(geturl, "", function (result) {
        //关闭图表加载提示
        CloseImagMask();
        //加载图表
        LoadImagData(chartid, result);
    });
}

//导出EXCEL
//此方法要注意，添加参数的时候顺序要和后台获取数据的顺序对应好
function PublicExportExcel(controstr, paras) {
    var parastr = "";
    for (var i = 0; i < paras.length; i++) {
        if (i == (paras.length - 1)) {
            parastr = parastr + paras[i];
        }
        else {
            parastr = parastr + paras[i] + "|";
        }
    }

    window.open(controstr + "&vars=" + parastr, "windows", "scrollbars=1,height=500,width=1200,status=yes,toolbar=no,menubar=no,location=no", "false");
}


//标识符转换
function convert(type)
{
    if( type == 1 ) // 10 to 32
    {
        document.getElementById("rolecode").value = "";
        var roleid = document.getElementById("roleid").value;

        var idArray = new Array();
        idArray = roleid.split(',', 1000);

        var idstr = "";
        for (var i = 0; i < idArray.length; ++i)
        {
            idstr = idstr.concat( UInt10To32(idArray[i]) );
            idstr = idstr.concat( ',' );
            if (idArray.length - 1 == i)
            {
                idstr = idstr.substring(0, idstr.length-1);
            }
        }
        // if (idstr.endsWith(','))
        // {
        //     idstr = idstr.substring(0, idstr.length-1);
        // }

        document.getElementById("rolecode").value = idstr;
    }
    else
    {
        // 32 to 10
        document.getElementById("roleid").value = "";
        var rolecode = document.getElementById("rolecode").value;

        var idArray = new Array();
        idArray = rolecode.split(',', 1000);

        var codestr = "";
        for (var i = 0; i < idArray.length; ++i)
        {
            codestr = codestr.concat( UInt32To10(idArray[i]) );
            codestr = codestr.concat( ',' );
            if (idArray.length - 1 == i)
            {
                codestr = codestr.substring(0, codestr.length-1);
            }
        }

        // if (codestr.endsWith(','))
        // {
        //     codestr = codestr.substring(0, codestr.length-1);
        // }
        document.getElementById("roleid").value = codestr;
    }
}

// 10进制转32进制 1--9，A-Z; 去掉 0，O，1，I
function UInt10To32( value )
{
    value = parseInt(value);
    if( isNaN(value) )
        return "#";

    var szvalue = "";
    var urv = value % 32;
    value = parseInt(value / 32);

    while( true )
    {
        if ( urv == 0 )
        {
            szvalue = "Z" + szvalue;
        }
        else if ( urv == 1 )
        {
            szvalue = "Y" + szvalue;
        }
        else if (urv >= 2 && urv <= 9)
        {
            var charv = "2";
            var nv = charv.charCodeAt() + (urv - 2);
            szvalue = String.fromCharCode(nv) + szvalue;
        }
        else if (urv >= 10 && urv <= 17)  // 去掉 i
        {
            var charv = "A";
            var nv = charv.charCodeAt() + (urv - 10);
            szvalue = String.fromCharCode(nv) + szvalue;
        }
        else if (urv >= 18 && urv <= 22)  // 去掉 o
        {
            var charv = "J";
            var nv = charv.charCodeAt() + (urv - 18);
            szvalue = String.fromCharCode(nv) + szvalue;
        }
        else if (urv >= 23 && urv <= 32)
        {
            var charv = "P";
            var nv = charv.charCodeAt() + (urv - 23);
            szvalue = String.fromCharCode(nv) + szvalue;
        }

        if (value == 0)
            break;

        urv = value % 32;
        value = parseInt(value/32);
    }

    return "#"+szvalue;
}

// 32进制转10进制 1--9，A-Z; 去掉 0，O，1，I
function  UInt32To10( szValue)
{
    szValue = szValue.toUpperCase();
    if ( szValue.length <= 1 || szValue.length > 8)
        return 0;

    if (szValue.charAt(0) != '#'||(szValue.length == 8 && szValue.charAt(1) != 'Y' && szValue.charAt(1) != '2' && szValue.charAt(1) != '3'))
        return 0;

    var uValue = 0;
    for (var n = 0, i = szValue.length - 1; i >= 0; --i, ++n)
    {
        var ch = szValue.charAt(i);
        if (ch == '#')
            break;

        var ubase = parseInt(Math.pow(32, n));
        if (ch == 'Z') //0
            uValue += 0 * ubase;
        else if (ch == 'Y') // 1
            uValue += 1 * ubase;
        else if (ch >= '2' && ch <= '9')
        {
            var charv = "2";
            uValue += (ch.charCodeAt() - charv.charCodeAt() + 2) * ubase;
        }
        else if (ch >= 'A' && ch <= 'H')
        {
            var charv = "A";
            uValue += (10 + (ch.charCodeAt() - charv.charCodeAt())) * ubase;
        }
        else if (ch >= 'J' && ch <= 'N')
        {
            var charv = "J";
            uValue += (18 + (ch.charCodeAt() - charv.charCodeAt())) * ubase;
        }
        else if (ch >= 'P' && ch <= 'X')
        {
            var charv = "P";
            uValue += (23 + (ch.charCodeAt() - charv.charCodeAt())) * ubase;
        }
        else
        {
            return 0;
        }
    }

    return uValue;
}