<!DOCTYPE html>
<html lang="en">
<head>
    <title>Facebook Gallery</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PHP Facebook Album Gallery">
    <meta name="author" content="John Veldboom">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettyPhoto/3.1.6/css/prettyPhoto.css"/>
    <style>
        body { padding-top: 70px; }
        .thumbnail img {
            overflow: hidden;
            height: 100px;
            /*width: 100%;*/
        }
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="https://github.com/jveldboom/facebook-gallery">Facebook Gallery</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="https://github.com/jveldboom/facebook-gallery">github</a></li>
                <li><a href="https://github.com/jveldboom/facebook-gallery/issues">issues</a></li>

                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">example galleries <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="?fid=Threadless">Threadless</a></li>
                        <li><a href="?fid=Adobe">Adobe</a></li>
                        <li><a href="?fid=Starbucks">Starbucks</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container-fluid">
    <?php
    if(empty($_GET['fid'])){$_GET['fid'] = 'tacobell';} // force if empty for demo

    require('class.facebook-gallery.php');

    $config = array(
        'page_name' => $_GET['fid'],
        'app_id' => '{YOUR APP ID}',
        'app_secret' => '{YOUR APP SECRET}',
        'breadcrumbs' => true,
        'cache' => array(
            'location' => 'cache', // ensure this directory has permission to read and write
            'time' => 7200
        )
    );

    $gallery = new FBGallery($config);
    echo $gallery->display();

    ?>
</div><!-- /.container -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prettyPhoto/3.1.6/js/jquery.prettyPhoto.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
$(function () {
    $("a[rel^='prettyPhoto']").prettyPhoto({theme: 'dark_rounded',social_tools:'',deeplinking: false});
    $("[rel=tooltip]").tooltip();
});
</script>
</body>
</html>
