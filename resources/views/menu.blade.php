@extends("app")

@section('content')
<div class="row">
    <div class="col-md-5">
    <div class="menu_preview_area">
        <div class="mobile_menu_preview">
            <div class="mobile_hd tc">{{ $mp['name'] }}</div>
            <ul class="horizontal-bar">
                @foreach($button_list as $button)
                    <li class="button-wrap" id="btn-{{ $button['id'] }}">
                        <a class="button" data-btn-id="{{ $button['id'] }}" data-type="{{ $button['type'] }}" data-key="{{ $button['key'] }}" data-url="{{ $button['url'] }}" data-order="{{ $button['order'] }}">{{ $button['name'] }}</a>
                        <div class="v-bar-wrap" style="display: none;">
                        <ul class="vertical-bar">
                            @foreach($button['sub_button'] as $child_button)
                                <li class="button-wrap" id="btn-{{ $child_button['id'] }}"><a class="button" data-btn-id="{{ $child_button['id'] }}" data-type="{{ $child_button['type'] }}" data-key="{{ $child_button['key'] }}" data-url="{{ $child_button['url'] }}" data-order="{{ $child_button['order'] }}">{{ $child_button['name'] }}</a></li>
                            @endforeach
                                <li class="button-wrap"><a class="button add-y-btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></li>
                        </ul>
                        </div>
                    </li>
                @endforeach
                <li class="button-wrap"><a class="button add-x-btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></li>
            </ul>
        </div>
    </div>
    </div>
    <div class="col-md-5 col-md-offset-1">
    <div class="button_edit_area" style="display: none;">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="btn-name">按钮标题</label>
                <input type="text" class="form-control" id="btn-name" value="">
            </div>

            <div class="form-group">
                <label for="btn-type">按钮类型</label>
                <label class="radio-inline">
                    <input type="radio" name="btn-type"  value="view"> 网页链接

                </label>
                <label class="radio-inline">
                    <input type="radio" name="btn-type"  value="click"> 点击事件
                </label>
                <label class="radio-inline">
                    <input type="radio" name="btn-type"  value="group"> 按钮组
                </label>
            </div>

            <div class="form-group">
                <label for="btn-url">按钮链接</label>
                <input type="text" class="form-control" id="btn-url" value="" disabled>
            </div>

            <div class="form-group">
                <label for="btn-url">按钮消息</label>
                <input type="text" class="form-control" id="btn-key" value="" disabled>
            </div>

            <div class="form-group">
                <label for="btn-order">按钮排序</label>
                <input type="number" class="form-control" id="btn-order" value="">
            </div>
            <button type="button" class="btn btn-success" id="confirm-btn">保存按钮</button>
            <button type="button" class="btn btn-danger" id="del-btn">删除按钮</button>
        </form>
    </div>
    </div>
</div>
<div class="toolbar">
    <input type="hidden" id="new_btn_num" value="0">
    <button type="button" class="btn btn-primary" id="pull-btn">从微信同步</button>
    <button type="button" class="btn btn-primary" id="save-btn">保存并同步至微信</button>
</div>
@endsection

@section("script")
<script>
    $(document).ready(function() {
        if($(".horizontal-bar>.button-wrap").length>=4) {
            $(".add-x-btn").parent().hide();
        }
        var x_button_num = $(".horizontal-bar>.button-wrap").length;
        x_button_num = x_button_num>3?3:x_button_num;
        $(".horizontal-bar>.button-wrap").css("width",(100/x_button_num).toFixed(2)+"%");
    });

    $("#pull-btn").click(function() {
        var api = "/api/{{ $mp['id'] }}/menu/pull";
        $.getJSON(api,function(data) {
            location.href = location.href;
        });
    });

    $(".mobile_menu_preview").on('click','.button',function() {
        var btn_name = $(this).text();
        console.log(btn_name);
        if(btn_name.length<1) return;
        $("#btn-name").val($(this).text());
        $("#del-btn").data( "btnId", $(this).data("btnId") );
        $("#confirm-btn").data( "btnId", $(this).data("btnId") );
        $("#btn-url").val($(this).data("url"));
        $("#btn-order").val($(this).data("order"));
        $("#btn-key").val($(this).data("key"));
        if($(this).data("type")!="") {
            $("input[name=btn-type][value="+$(this).data("type")+"]").click();
        } else {
            $("input[name=btn-type][value='group']").click();
        }
        $(".button_edit_area").show();
        if($(this).parent().find(".v-bar-wrap").length>0) {
            $(".v-bar-wrap").hide();
            var width = $(".horizontal-bar>.button-wrap").css("width");
            $(".v-bar-wrap").css("width",width);
            $(this).parent().find(".v-bar-wrap").show();
        }
        $(".button-wrap").removeClass("current");
        $(this).parent().addClass("current");

    });
    $("input[name='btn-type']").change(function(){
        var type_list = {"view":"url","click":"key"};
        for(var i in type_list) {
            $("#btn-"+type_list[i]).attr("disabled",true);
        }
        var checked = $("input[name='btn-type']:checked").val();
        if(checked in type_list) {
            $("#btn-"+type_list[checked]).attr("disabled",false);
        }
    })
    $("#del-btn").click(function() {
        var btn_id = $(this).data("btnId");
        $("#btn-"+btn_id).remove();
        if($("#btn-"+btn_id).parent().find(".add-y-btn").length>0) {
            $("#btn-"+btn_id).parent().find(".add-y-btn").show();
        } else {
            $(".add-x-btn").parent().show();
            var x_btn_num = $(".horizontal-bar>.button-wrap").length;
            x_btn_num = x_btn_num>3?3:x_btn_num;
            $(".horizontal-bar>.button-wrap").css("width",(100/x_btn_num).toFixed(2)+"%");
        }
    });
    $("#confirm-btn").click(function() {
        var btn_id = $(this).data("btnId");
        var btn = $("#btn-"+btn_id).find(".button")[0];
        $(btn).text($("#btn-name").val());
        var btn_type = $("input[name='btn-type']:checked").val();
        if(btn_type!=undefined) {
            $(btn).data("type",btn_type);
        }
        $(btn).data("url",$("#btn-url").val());
        $(btn).data("order",$("#btn-order").val());
        $(btn).data("key",$("#btn-key").val());
        return true;
    });
    $(".add-x-btn").click(function() {
        var x_btn_num = $(".horizontal-bar>.button-wrap").length+1;
        var new_btn_num = parseInt($("#new_btn_num").val())+1;
        var content = '<li class="button-wrap" id="btn-new-'+new_btn_num+'"><a class="button" ';
        content += 'data-btn-id="new-'+new_btn_num+'" ';
        content += 'data-order="'+x_btn_num+'" ';
        content += '>添加标题</a>';
        content += '<div class="v-bar-wrap" style="display: none;"><ul class="vertical-bar">';
        content += '<li class="button-wrap"><a class="button add-y-btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></li>';
        content += '</ul></div></li>';
        $(this).parent().before(content);
        if(x_btn_num>=4) {
            $(this).parent().hide();
        }

        x_btn_num = x_btn_num>3?3:x_btn_num;
        $(".horizontal-bar>.button-wrap").css("width",(100/x_btn_num).toFixed(2)+"%");
        $("#new_btn_num").val(new_btn_num);
    });
    $(".mobile_menu_preview").on('click','.add-y-btn',function() {
        var v_bar = $(this).parent().parent();
        var v_bar_btn_num = $(v_bar).find(".button-wrap").length;
        console.log(v_bar_btn_num);
        var new_btn_num = parseInt($("#new_btn_num").val())+1;
        var content = '<li class="button-wrap" id="btn-new-'+new_btn_num+'"><a class="button" ';
        content += 'data-btn-id="new-'+new_btn_num+'" ';
        content += 'data-order="'+v_bar_btn_num+'" ';
        content += '>添加标题</a></li>';
        $(this).parent().before(content);
        $("#new_btn_num").val(new_btn_num);
        $("#btn-new-"+new_btn_num).find(".button").click();
        if(v_bar_btn_num>=5) {
            $(this).parent().hide();
        }

    });
    $("#save-btn").click(function() {
        var x_btn_list = $(".horizontal-bar>.button-wrap").toArray();
        var post_data = {};
        for (var i in x_btn_list) {
            var btn_list = $(x_btn_list[i]).find(".button").toArray();
            if(btn_list.length<1) {
                continue;
            }
            var btn = btn_list[0];
            var btn_name = $(btn).text();
            if(btn_name.length<1) {
                continue;
            }
            var btn_data = {};
            btn_data['name'] = btn_name;
            btn_data['type'] = $(btn).data("type");
            btn_data['key'] = $(btn).data("key");
            btn_data['url'] = $(btn).data("url");
            btn_data['order'] = $(btn).data("order");
            btn_data['sub_button'] = {};
            delete btn_list[0];
            for(var j in btn_list) {
                var sub_btn = btn_list[j];
                var btn_name = $(sub_btn).text();
                if(btn_name.length<1) {
                    continue;
                }
                var sub_btn_data = {};
                sub_btn_data['name'] = btn_name;
                sub_btn_data['type'] = $(sub_btn).data("type");
                sub_btn_data['key'] = $(sub_btn).data("key");
                sub_btn_data['url'] = $(sub_btn).data("url");
                sub_btn_data['order'] = $(sub_btn).data("order");

                btn_data['sub_button'][j]=sub_btn_data;
            }

            post_data[i]=btn_data;

        }
        console.log(post_data);

        $.ajax
        ({
            type: "POST",
            url: '/api/{{ $mp['id'] }}/menu/save',
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            async: false,
            data: JSON.stringify({data:post_data}),
            success: function () {

            }
        });

    });
</script>
@endsection
