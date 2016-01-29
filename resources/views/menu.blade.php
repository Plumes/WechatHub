@extends("app")

@section('content')
<div class="row">
    <div class="col-md-5">
    <div class="menu_preview_area">
        <div class="mobile_menu_preview">
            <div class="mobile_hd tc">{{ $mp['name'] }}</div>
            <ul class="horizontal-bar">
                @foreach($button_list as $button)
                    <li class="button-wrap">
                        <a class="button" data-btn-id="{{ $button['id'] }}" data-type="{{ $button['type'] }}" data-key="{{ $button['key'] }}" data-url="{{ $button['url'] }}" data-order="{{ $button['order'] }}">{{ $button['name'] }}</a>
                        <div class="v-bar-wrap" style="display: none;">
                        <ul class="vertical-bar">
                            @foreach($button['sub_button'] as $child_button)
                                <li class="button-wrap"><a class="button" data-btn-id="{{ $child_button['id'] }}" data-type="{{ $child_button['type'] }}" data-key="{{ $child_button['key'] }}" data-url="{{ $child_button['url'] }}" data-order="{{ $child_button['order'] }}">{{ $child_button['name'] }}</a></li>
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
            </div>

            <div class="form-group">
                <label for="btn-url">按钮链接</label>
                <input type="text" class="form-control" id="btn-url" value="">
            </div>

            <div class="form-group">
                <label for="btn-order">按钮排序</label>
                <input type="number" class="form-control" id="btn-order" value="">
            </div>

            <button type="button" class="btn btn-danger" id="del-btn">删除按钮</button>
        </form>
    </div>
    </div>
</div>
<div class="toolbar">
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
    $(".add-x-btn").click(function() {
       var content = '<li class="button-wrap"><a class="button add-x-btn">添加标题</a></li>';
        $(this).parent().before(content);
        if($(".horizontal-bar>.button-wrap").length>=4) {
            $(this).parent().hide();
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
    $(".horizontal-bar>.button-wrap").click(function() {
        $(".v-bar-wrap").hide();
        var width = $(".horizontal-bar>.button-wrap").css("width");
        $(".v-bar-wrap").css("width",width);
        $(this).children(".v-bar-wrap").show();
    });
    $(".button").click(function() {
        var btn_name = $(this).text;
        if(btn_name<1) return;
        $("#btn-name").val($(this).text());
        $("#del-btn").data( "btnId", $(this).data("btnId") );
        $("#btn-url").val($(this).data("url"));
        $("#btn-order").val($(this).data("order"));
        if($(this).data("type")!="") {
            $("input[name=btn-type][value="+$(this).data("type")+"]").attr("checked",true);
        }
        $(".button_edit_area").show();

    });
    $("#del-btn").click(function() {
       var btn_id = $(this).data("btnId");
    });
</script>
@endsection
