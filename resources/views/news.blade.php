@extends("app")

@section('content')
<div class="news-list-wrap">
    <div class="toolbar">
        <button class="btn btn-success">创建素材</button>
    </div>
    <ul class="news-list">
        @foreach($news_list as $news)
            <li>
                <div class="inner">
                    <div class="content">
                        <img src="{{ $news[0]['thumb_url'] }}" alt="暂无图片">
                        <div class="title_list">
                            @foreach($news as $article)
                                <p class="title">
                                    <a href="{{ $article['url'] }}">{{ $article['title'] }}</a>
                                </p>
                            @endforeach
                        </div>
                    </div>

                    <div class="opr">
                        <a href="#">编辑</a>
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