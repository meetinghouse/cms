@extends('layouts.main')

@section('content')

    <div class="col-md-3 ">
        <div class="sidebar-nav">
            @include('shared.sidebar')
            @if(Auth::user())
                <div class="well">
                    Edit this page <br>
                    <a href="/projects/{{$project->id}}/edit" class="btn btn-success">Edit</a>
                </div>
            @endif

        </div>

    </div>

    <div class="col-md-9 column">
        @if($settings->theme != TRUE)  <h1>{{{ $project->title }}}</h1>@endif
        <div class="row">
            @if ($project->image)
                <div class="col-lg-12" id="main_image">
                    <img  src="/img/projects/{{$project->image}}" alt="{{$project->title}}">
                </div>
            @elseif ($project->thumbs->url('project_top') != '/thumbs/project_top/missing.png')
                <div class="col-lg-12 thumb" id="main_image">
                    <img src="<?= $project->thumbs->url('project_top') ?>">
                </div>
            @endif
        </div>
        @if($settings->theme != TRUE)
            <div class="row">
                <div class="col-lg-12">
                    {{{$project->city_county}}}
                    <br>
                    {{{$project->state_country}}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 body">
                    {{$project->body}}
                </div>
            </div>
        @endif

        @if($settings->theme == TRUE)
            <div class="row">
                <div class="col-lg-4 body">
                    <h1>{{{$project->title}}}</h1>

                    {{{$project->city_county}}}
                    <br>
                    Architect: {{{$project->architect}}}
                </div>
                <div class="col-lg-8 projectBody">
                    <article> {{$project->body}}</article>
                </div>
            </div>
        @endif

        @if(isset($project->images[0]))
            @if($settings->theme != TRUE)
                <div class="help-block">
                    Click on images below to enlarge.
                </div>
            @endif
            <div class="row gallery_row">

                @foreach ($project->images as $image)
                    @if($settings->theme == FALSE)
                        <div class="col-lg-6 gallery_item">
                            <a class="gallery" href="/assets/img/projects/{{$image->file_name}}" alt="{{$image->file_name}}" title="{{$image->image_caption}}">
                                <img class="col-lg-12" src="/assets/img/projects/{{$image->file_name}}" alt="{{$image->file_name}}">
                            </a>
                            @else
                                <div class="col-lg-12 gallery_item_dark">
                                    <img class="col-lg-12" src="/assets/img/projects/gallery/{{$image->file_name}}" alt="{{$image->file_name}}">
                                    @endif
                                    <br>

                                    <div class="project-caption caption">{{$image->image_caption}}</div>
                                </div>
                                @endforeach
                        </div>
            </div>
        @endif


    </div>


@stop