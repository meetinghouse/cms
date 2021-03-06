@extends('layouts.main')

@section('content')
<div class="col-md-3">
    <div class = "sidebar-nav">
		<div class="mobile-menu light-theme"><a href="#"><i class="fa fa-bars"></i></a></div>
        @include('shared.sidebar')
        @if(Auth::user())
        <div class="well">
            Create New Post <br>
            <a href="/posts/create" class="btn btn-success">Create</a>
        </div>
        @endif
    </div>
</div>
<div class="col-md-9 column content blog_index">
    <div class = "">
        @foreach($posts as $p)
        <div class="row blog_row">
           <div class="post_intro col-md-9">
				<h2 class="media-heading"><a href="{{$p->slug}}">{{{$p->title}}}</a></h2>
				{{ $p->intro }} <span class="read-more"><a href="{{$p->slug}}">Read more...</a></span>
			</div>
			<div class="post_img col-md-3">
				@if ($p->image)
					@if(file_exists(public_path().'/img/posts/thumb/'.$p->image))
						<img  src="/img/posts/thumb/{{$p->image}}" alt="{{$p->title}}" class="img-responsive">
					@else
						<img  src="/img/posts/{{$p->image}}" alt="{{$p->title}}" class="img-responsive">
					@endif
				@else
				{{--<img  src="/img/default/photo_default_0.png" alt="{{$p->title}}" class="img-responsive">--}}
				@endif
			</div>
        </div>
        @endforeach
    </div>
</div>
@stop