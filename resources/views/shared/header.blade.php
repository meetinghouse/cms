<!-- shared.header -->
<?php
$banners_active = \App\Banner::slideShow();
?>
@if($settings->theme == false)

@if(count($banners_active) == 0)

<!-- no banners -->

@endif

@if(count($banners_active) >= 2)

<div class="carousel slide" id="banner-header">
    <ol class="carousel-indicators">
        @foreach($banners_active as $key => $banner_active)
        @if($key && $key == 0)
        <!--active class on li needed below-->
        <li class="active" data-slide-to="{!!$key!!}" data-target="#banner-header"></li>
        @else
        <!--active class on li needed below-->
        <li data-slide-to="{!!$key!!}" data-target="#banner-header"></li>
        @endif
        @endforeach
    </ol>

    <div class="carousel-inner">
        @foreach($banners_active as $key => $banner_active)

        @if($key == 0)
        <div class="item active"> <!--active on div needed on the one-->
            <img alt="" src="/img/banners/{!!$banner_active->banner_name!!}"/>
        </div>
        @else
        <div class="item"> <!--active on div needed on the one-->
            <img alt="" src="/img/banners/{!!$banner_active->banner_name!!}"/>
        </div>
        @endif
        @endforeach
    </div>

    @foreach($banners_active as $key => $banner_active)
    <a class="left carousel-control" href="#banner-header" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#banner-header" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
    @endforeach
</div>
@endif
@if(count($banners_active) == 1)
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column home-banner-wrapper">
            <img class="home-banner" src="/img/banners/{!!$banners_active[0]->banner_name!!}"/>
        </div>
    </div>
</div>

@endif
@endif
