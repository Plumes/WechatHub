@extends("app")

@section('content')
<div class="news-list-wrap">
    <div class="toolbar">
        <button type="button" id="create-btn" class="btn btn-success">创建素材</button>
        <button type="button" id="pull-btn" class="btn btn-primary">从微信同步</button>
    </div>
    <ul class="news-list">
        @foreach($news_list as $news)
            <li>
                <div class="inner">
                    <div class="content">
                        <img src="{{ $news[0]['thumb_url'] }}" alt="暂无图片">
                        <div class="title-list">
                            @foreach($news as $article)
                                <p class="title">
                                    <a href="{{ $article['url'] }}">{{ $article['title'] }}</a>
                                </p>
                            @endforeach
                        </div>
                    </div>

                    <div class="opr">
                        <a href="/{{ $mp['id'] }}/news/{{ $news[0]['news_media_id'] }}/edit">编辑</a>
                        <a href="#">删除</a>
                    </div>

                    <div class="date">
                        <span>{{ $news[0]['updated_at'] }}</span>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection

@section('script')
<script>
    $("#pull-btn").click(function() {
        var api ='/api/{{ $mp['id'] }}/news/pull';
        $.getJSON(api,function( response ) {
            if(response['code']=='0') {
                location.href=location.href;
            }
        })
    });
</script>
@endsection