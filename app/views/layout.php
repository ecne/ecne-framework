<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!-- google fonts -->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://localhost/public/res/css/default.css" media="screen" title="no title" charset="utf-8">
        <title><?php echo $this->data('title'); ?></title>
    </head>
    <body>
        <div class="row">
            <nav class="navbar navbar-inverse">
              <div class="container-fluid">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="/">Home</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                      <li><a href="/contact">Contact</a></li>
                      <li><a href="/about">About</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                  </ul>
                </div>
              </div>
            </nav>
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
