<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>{{$settings->name}}</title>

    <!-- Bootstrap core CSS -->
    {{ HTML::style('assets/css/bootstrap.css') }}
    {{ HTML::style('assets/css/colorfrog.css') }}
    <!-- <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'> -->
    {{ HTML::style('assets/css/main.css') }}
    {{ HTML::style('assets/css/prettify.css') }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{ HTML::script('assets/js/html5shiv.js') }}
    {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->

</head>

<body class="{{$settings->color}}">
<!--
Use the corresponding body tag for your chosen theme
<body class="blue">
<body class="orange">
<body class="green">
<body class="bw">
-->

<!-- //end container -->

<div class="row"><div class="span12"><hr></div></div>
<!-- //end header -->
<div class="container content">
    <div class="row">
        <div class="row clearfix">
            <div class="col-lg-12">
                @include('shared.alerts')
            </div>
        </div>
        <h2>Will return shortly.</h2>
    </div>
</div>

</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

{{ HTML::script('/assets/js/jquery-1.11.js') }}
{{ HTML::script('/assets/js/noty-2.2.2/js/noty/packaged/jquery.noty.packaged.min.js') }}
{{ HTML::script('/assets/js/jquery-sortable.js') }}
{{ HTML::script('/assets/js/bootstrap/bootstrap.min.js') }}
{{ HTML::script('/assets/js/jquery.fitvids.js') }}
{{ HTML::script('/assets/js/colorfrog.js') }}
{{ HTML::script('/assets/js/lib/ckeditor-full/ckeditor.js') }}
{{ HTML::script('/assets/js/app.js') }}

</body>
</html>
