<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php if(isset($title)){ echo $title; }?></title>
    <link href="style/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/hero.css">
    <link rel="stylesheet" href="style/overlay.css">
    <?php if(!($title == 'My Projects')){
    echo "<link rel=\"stylesheet\" href=\"style/index.css\">";
    } ?>
    <script src="style/bootstrap/js/jquery.js"></script>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            function load_modal(){
                    $('#myModal').modal('show');
            }

            function load_modal2(){
                    $('#myAFG').modal('show');
            }
    </script>
    <style>

    .modal {
            position: absolute;
            top: 3%;
            left:15%;
            margin-left:-150px;
            mbargin-tbop:-150px;
    }
    </style>
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
        <a class="navbar-brand" href="..">FXtract</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
<?php
if (!$user->is_logged_in()) {
?>
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
<?php
}else {
?>
           <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">File Options <span class="caret"></span></a>
            <ul class="dropdown-menu">
            <?php if (isset($_POST['cool'])) {?>
  <li><a href="memberpage.php">New File</a></li>
  <li role="separator" class="divider"></li>
  <?php }?>
              <li><a href="uploadhistory.php">Uploaded Files</a></li>
              <!--li><a href="#">View Files</a></li>
              <li><a href="#">Features</a></li>

              <li role="separator" class="divider"></li>
              <li class="dropdown-header">Nav header</li>
              <li><a href="#">Separated link</a></li>
              <li><a href="#">One more separated link</a></li-->


          </ul> </li>
            <?php if (isset($_POST['process'])) {?>
                         <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Features<span class="caret"></span></a>
            <ul class="dropdown-menu">
  <li><a href="a_index.php?id=<?php echo $fileID;?>">Extract Features</a></li>
  <li role="separator" class="divider"></li>
              <!--li><a href="uploadhistory.php">Extract Fields</a></li>
  <li role="separator" class="divider"></li>
              <li><a href="#">View Brep</a></li-->
              <li>  <a href="#" onclick="load_modal()">Full Model Features</a></li>
  <li role="separator" class="divider"></li>
              <li>  <a href="#" onclick="load_modal2()">Face Adjacency Graph</a></li>
              <!--li role="separator" class="divider"></li>
              <li class="dropdown-header">Nav header</li>
              <li><a href="#">Separated link</a></li>
              <li><a href="#">One more separated link</a></li-->

            </ul>
<?php
}?>

          </ul> </li>

  <ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
<a marked="1" href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
<!--img src="Main%20Menu%20-%20Layouts%20-%20PixelAdmin_files/1.jpg"-->
<span class="glyphicon glyphicon-user"><?php echo " ".$_SESSION['user']['username'];?></span>
</a>
<ul class="dropdown-menu">
<li><a marked="1" href="#"><span class="label label-warning pull-right">New</span>Profile</a></li>
<li><a marked="1" href="#"><span class="badge badge-primary pull-right">New</span>Account</a></li>
<li><a marked="1" href="#"><i class="dropdown-icon fa fa-cog"></i>&nbsp;&nbsp;Settings</a></li>
<li class="divider"></li>
<li><a marked="1" href="logout.php"></i>&nbsp;&nbsp;Log Out</a></li>
</ul>
</li>
  </ul>
<?php
}?>

      </div><!--/.nav-collapse -->
    </div>
  </nav>
