@extends("app")

@section('content')
<div class="row">
    <div class="col-md-4">
    <div class="news-preview">
        <div class="news-preview-list" id="news-preview-list">
            @foreach($news as $article)
                <div class="article-item" id="article-{{ $article['id'] }}" data-id="{{ $article['id'] }}" data-new-id="{{ $article['news_media_id'] }}" data-order="{{ $article['order'] }}">
                    <div class="content">
                        <img src="{{ $article['thumb_url'] }}" alt="暂无图片" class="thumb">
                        <div class="title">
                            <span>{{ $article['title'] }}</span>
                        </div>
                    </div>
                    <div class="toolbar-mask">
                        <a href="" class="glyphicon glyphicon-arrow-up" aria-hidden="true" title="上移"></a>
                        <a href="" class="glyphicon glyphicon-arrow-down" aria-hidden="true" title="下移"></a>
                        <a href="" class="glyphicon glyphicon-trash" aria-hidden="true" title="删除"></a>
                    </div>
                </div>
            @endforeach    
        </div>

        <div class="toolbar">
            <a href="" class="btn btn-primary" id="add-btn">添加新文章</a>
            <a href="" class="btn btn-success" id="send-btn">保存并发送</a>
        </div>
    </div>
    </div>
    <div class="col-md-8">
    <div class="editor-wrap" style="margin: 20px;">
        <form class="form-horizontal">
            <div class="form-group">
                <input type="text" class="form-control" id="article-title" value="" placeholder="请输入标题">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="article-author" value="" placeholder="请输入作者">
            </div>
            <div class="form-group">
                <textarea type="text" class="form-control" id="article-content" value="" placeholder="请输入文章内容" rows="20">

                </textarea>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="article-url" value="" placeholder="请输入原文链接">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="article-digest" value="" placeholder="请输入文章提要">
            </div>
            <a href="" class="btn btn-success" id="save-btn">保存</a>
        </form>
    </div>
    </div>
</div>
@endsection

@section("script")
<script>
    $("#news-preview-list").on("click",".article-item", function() {
        $(".article-item").removeClass("current");
        $(this).addClass("current");
        var article_id = $(this).data("id");
        var api = "/api/article/"+article_id;
        $.getJSON(api,function(data) {
            var article = data['article'];
            console.log(article);
            $("#article-title").val(article['title']);
            $("#article-author").val(article['author']);
            $("#article-content").val(article['content']);
            $("#article-url").val(article['url']);
            $("#article-digest").val(article['digest']);
        });
    });
    $("#save-btn").click(function() {

    })
</script>
@endsection