// jQuery $('document').ready(); function
var oTable;
$(document).ready(function () {
    oTable = initTable();
    $("#deleteFun").click(_deleteList);
//checkbox全选
    $("#checkAll").click(function () {
        if ($(this).prop("checked")) {
            $("input[name='checkList']").prop("checked", true);
        } else {
            $("input[name='checkList']").attr("checked", false);
        }
    });
    $.ajax({
        url: '/admin/index/role',
        type: 'POST',
        dataType: 'json',
        data: {},
        success: function (r) {
            if (r.code == 200) {
                $('#username').text(r.msg.name);
                if(r.msg.auth==2){
                    $(".nav-list").children().last().css('display','none');
                }
            } else {
                window.location.href='/Public/admin/login.html';
            }
        }
    });
    $("input[id=lefile]").wrap("<form id='myupload' action='/admin/contact/upload' method='post' enctype='multipart/form-data'></form>");
    $('input[id=lefile]').change(function() {
        $('#myupload').ajaxSubmit({
            dataType:"json",
            success:function(data){
                $('#img').val(data.msg);
            }
        });
    });
    var len=$(document.body).height();
    $('#mask').height(len);
});

/**
 * 表格初始化
 * @returns {*|jQuery}
 */
function initTable() {
    var table = $("#example").dataTable({
        //"iDisplayLength":10,
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "oLanguage": {
            "sLengthMenu": "每页显示 _MENU_ 条记录",
            "sZeroRecords": "抱歉， 没有找到",
            "sInfoEmpty": "没有数据",
            "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
            "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "前一页",
                "sNext": "后一页",
                "sLast": "尾页"
            },
            "sZeroRecords": "没有检索到数据"
        },
        "pagingType": "full_numbers",
        "searching": true,
        "sAjaxSource": "/admin/contact/contact_list",
        'bPaginate': true,
        "bDestory": true,
        "bRetrieve": true,
        "bFilter": false,
        "bSort": false,
        "bProcessing": true,
        "aoColumns": [
            {
                "mDataProp": "id",
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<input type='checkbox' name='checkList' value='" + sData + "'>");

                }
            },
            {"mDataProp": "id"},
            //{"mDataProp": "admin"},
            {"mDataProp": "company"},
            {"mDataProp": "tel"},
            {"mDataProp": "fax"},
            {"mDataProp": "email"},
            {
                "mDataProp": "add",
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    var text = sData;
                    $.trim(text);
                    var len = text.length;
                    if (len > 20) {
                        text = text.substring(0, 19) + '...';
                    }
                    $(nTd).html("<span>" + text + "</span>");
                }
            },
            {"mDataProp": "time"},
            //{"mDataProp": "img"},

            {
                "mDataProp": "id",
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("")
                        .append("<a href='javascript:void(0);' class='btn btn-success' style='padding:2px 10px;' onclick='edit(" + oData.id + ")'>编辑</a>&nbsp;").append("<a href='javascript:void(0);' class='btn btn-danger' style='padding:2px 10px;' onclick='_deleteFun(" + oData.id + ")'>删除</a>");
                }
            },
        ],
        "sDom": "<'row-fluid'<'span6 float' l><'span6 float position myBtnBox'><'span6'f>r>t<'row-fluid'<'span6 float top1'i><'span6 top'p>>",
        "fnCreatedRow": function (nRow, aData, iDataIndex) {
            //add selected class
            $(nRow).click(function () {
                if ($(this).hasClass('row_selected')) {
                    $(this).removeClass('row_selected');
                } else {
                    oTable.$('tr.row_selected').removeClass('row_selected');
                    $(this).addClass('row_selected');
                }
            });
        },
        "fnInitComplete": function (oSettings, json) {
            $('<a href="#"  class="btn btn-primary" style="padding: 2px 10px;" onclick="add()" data-toggle="modal">新增</a>' + '&nbsp;' +
            '<a href="#" class="btn btn-danger" style="padding: 2px 10px;" id="deleteFun">删除</a>' + '&nbsp;').appendTo($('.myBtnBox'));
            $("#deleteFun").click(_deleteList);
        }
    });
    return table;
}

/**
 * 删除
 * @param id
 * @private
 */
function _deleteFun(id) {
    $.ajax({
        url: '/admin/index/role',
        type: 'POST',
        dataType: 'json',
        data: {},
        async: false,
        success: function (r) {
            if (r.code == 200) {

            } else {
                window.location.href='/Public/admin/login.html';
            }
        }
    });
    if (confirm('确认删除吗？')) {
        $.ajax({
            url: "/admin/contact/contact_delete",
            data: {"id": id},
            type: "post",
            dataType: 'json',
            success: function (backdata) {
                if (backdata.code == 200) {
                    oTable.fnReloadAjax();
                } else {
                    alert("删除失败");
                }
            }, error: function (error) {
                console.log(error);
            }
        });
    }
}

$.fn.dataTableExt.oApi.fnReloadAjax = function (oSettings) {
//oSettings.sAjaxSource = sNewSource;
    this.fnClearTable(this);
    this.oApi._fnProcessingDisplay(oSettings, true);
    var that = this;

    $.getJSON(oSettings.sAjaxSource, null, function (json) {
        /* Got the data - add it to the table */
        for (var i = 0; i < json.aaData.length; i++) {
            that.oApi._fnAddData(oSettings, json.aaData[i]);
        }
        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
        that.fnDraw(that);
        that.oApi._fnProcessingDisplay(oSettings, false);
    });
}


/**
 * 批量删除
 * 未做
 * @private
 */
function _deleteList() {
    var str = '';
    $("input[name='checkList']:checked").each(function (i, o) {
        str += $(this).val();
        str += ",";
    });
    if (str.length > 0) {
        var IDS = str.substr(0, str.length - 1);
        _deleteFun(IDS);
    } else {
        alert("至少选择一条记录操作");
    }
}
$('document').ready(function () {
    $('ul.nav-list').accordion();
});
