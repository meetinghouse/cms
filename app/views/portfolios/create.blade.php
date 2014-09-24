@extends('layouts.main')

@section('content')

<div class="col-md-3 ">
  @include('shared.sidebar')
</div>

<div class="col-md-9 column">

  <h2>Create Portfolio:</h2>

  {{ Form::model('portfolio', array('method' => 'POST', 'route' => array('portfolios.store'), 'role' => 'form')) }}


  <div class="form-group">
    <label>Portfolio Name (<a href="http://www.restorationtrades.com/help.html#portolio_name">Help</a>)</label>
    {{ Form::text('title', null, array('class' => 'form-control')) }}
  </div>
  @if($errors->first('title'))
    <div class="alert alert-danger">
      {{  $errors->first('title'); }}
    </div>
  @endif

  <div class="form-group">
    <label>Portfolio Browser Description (a.k.a. Title Tag) (<a href="http://www.restorationtrades.com/help.html#portfolio_browser_description">Help</a>)</label>
    {{ Form::text('seo', null, array('class' => 'form-control')) }}
  </div>
  @if($errors->first('seo'))
    <div class="alert alert-danger">
      {{  $errors->first('seo'); }}
    </div>
  @endif

  <div class="form-group">
    <label>Portfolio Heading (<a href="http://www.restorationtrades.com/help.html#portfolio_heading">Help</a>)</label>
    {{ Form::text('header', null, array('class' => 'form-control')) }}
  </div>
  @if($errors->first('header'))
  <div class="alert alert-danger">
    {{  $errors->first('header'); }}
  </div>
  @endif


  <div class="form-group">
    <label>Portfolio Main Body (<a href="http://www.restorationtrades.com/help.html#portfolio_page_description">Help</a>)</label>
    {{ Form::textarea('body', null, array('rows' => 30, 'class' => 'ckeditor form-control')) }}
  </div>
  @if($errors->first('body'))
  <div class="alert alert-danger">
    {{  $errors->first('body'); }}
  </div>
  @endif

  <div class="form-group">
    <label>Portfolio Web Address (URL) (<a href="http://www.restorationtrades.com/help.html#portfolio_web_address">Help</a>)</label>
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

  <div class="form-group">
    <div class="controls">
      <div class="checkbox">
        <label class="checkbox">{{ Form::checkbox('published', 1) }} Published</label>
      </div>
    </div>
  </div>

  <div class="controls">
    {{ Form::submit('Create Portfolio', array('id' => 'submit', 'class' => 'btn btn-success')) }}
    <br>
  </div>

  {{ Form::close() }}


</div>


@stop
