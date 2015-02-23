@extends('layouts.main')

@section('content')

<!-- posts.edit -->
<div class="col-md-9 column">

    <h2>Edit Blog Post: {{$post->title}}</h2>

    {{ Form::model($post, array('method' => 'PUT', 'route' => array('posts.update', $post->id), 'files' => 'true', 'role' => 'form')) }}


    <div class="form-group">
        <label>Blog Post Name (<a href="http://www.restorationtrades.com/help.html#portolio_name" target="_blank">Help</a>)</label>
        {{ Form::text('title', null, array('class' => 'form-control')) }}
    </div>
    @if($errors->first('title'))
    <div class="alert alert-danger">
        {{  $errors->first('title'); }}
    </div>
    @endif

    <div class="form-group">
        <label>Intro Paragraph (<a href="http://www.restorationtrades.com/help.html#Blog Post_page_description" target="_blank">Help</a>)</label>
        {{ Form::textarea('intro', null, array('rows' => 30, 'class' => 'ckeditor form-control')) }}
    </div>
    @if($errors->first('intro'))
    <div class="alert alert-danger">
        {{  $errors->first('intro'); }}
    </div>
    @endif

    <div class="form-group">
        <label>Blog Post Main Body (<a href="http://www.restorationtrades.com/help.html#Blog Post_page_description" target="_blank">Help</a>)</label>
        {{ Form::textarea('body', null, array('rows' => 30, 'class' => 'ckeditor form-control')) }}
    </div>
    @if($errors->first('body'))
    <div class="alert alert-danger">
        {{  $errors->first('body'); }}
    </div>
    @endif


    @include('shared.tags', array('model' => 'projects'))

    @if(Auth::user() && Auth::user()->admin == 1)
    <div class="form-group">
        <label>Blog Post Web Address (URL) (<a href="http://www.restorationtrades.com/help.html#Blog Post_web_address" target="_blank">Help</a>)</label>
        {{ Form::text('slug', null, array('class' => 'form-control')) }}
        <div class="help-block">The url must start with / </div>
    </div>
    @if($errors->first('slug'))
    <div class="alert alert-danger">
        @if($errors->first('slug'))
        {{ $errors->first('slug') }}
        @endif
    </div>
    @endif
    @endif

    <div class="form-group">
        <div class="controls">
            <div class="checkbox">
                <label class="checkbox">{{ Form::checkbox('published', 1) }} Published</label>
            </div>
        </div>
    </div>

    <!--    images-->

    <div class="form-group">
        <label for="email">Default Image Uploader (<a href="http://www.restorationtrades.com/help.html#blog_default_image_uploader" target="_blank">Help</a>)</label>
        {{ Form::file('image', null, array('class' => 'form-control', 'tabindex' => 1)) }}
        @if($errors->first('image'))
        <div class="alert alert-danger">
            {{  $errors->first('image'); }}
        </div>
        @endif
        @if($post->image)
        <div class="row">
            <div>
                <img  class="col-lg-4" src="/{{$path}}/{{$post->image}}" class="banner-show">
            </div>
        </div>
        @endif
        <div class="help-block">This is the image we will use for the default Blog image</div>
    </div>

    <br>


    <div class="controls row">
        <div class="col-lg-2">
            {{ Form::submit('Update Blog Post', array('id' => 'submit', 'class' => 'btn btn-success')) }}
            {{ Form::close() }}
        </div>
        <div class="col-lg-2">
            {{ Form::open(['method' => 'DELETE', 'action' => ['PostsController@destroy', $post->id]]) }}
            {{ Form::submit('Delete', array('class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure you want to delete this?")')) }}
            {{ Form::close() }}
        </div>
    </div>
</div>


@stop
