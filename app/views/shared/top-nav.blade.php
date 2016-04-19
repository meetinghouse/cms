<!-- shared.top-nav -->
<button class="btn navbar-toggle" data-toggle="collapse" data-target="#nav-collapse-top">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</button>

<nav class="navbar-collapse collapse" id="nav-collapse-top">
    <ul class="nav nav-pills">
      <?php $count = 1; ?>
      @foreach($top_left_nav as $top)
        @if($settings->portfolio_menu_postion == $count)
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Portfolios</a>

          <ul class="dropdown-menu">
          @foreach($portfolio_links as $key => $portfolio)
          <li class="@if(Request::server('PATH_INFO') ==  $portfolio) {{'active'}} @else {{'not-active'}} @endif">
            <a href= {{$portfolio}}>{{$key}}</a>
          </li>
          @endforeach
          </ul>
        </li>
        @else
        <li class="@if(Request::server('PATH_INFO') ==  $top->slug) {{'active'}} @else {{'not-active'}} @endif">
          <a href="{{URL::to($top->slug)}}">{{$top->title}}</a>
        </li>          
        @endif
      

      <?php $count++; ?>
      @endforeach
       
    </ul>
</nav>


