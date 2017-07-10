<?php
require_once('../includes/initialize.php');

// ini_set('display_errors','on');
//ini_set('file_uploads','on');
//ini_set('upload_temp_dir','/home/digitalincentives/www/loginregister/uploads/');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: login.php');
}

$_POST['cool'] = 1;

if (isset($_GET['delete_id'])) {
    $fid = $_GET['delete_id'];
    try {//echo "dsds"; update files set units='nn' where file_id=2;
$query = "DELETE FROM `files` where file_id=$fid";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $query = "DELETE FROM `bends`";
        $stmt = $db->prepare($query);
        $stmt->execute();
            /**/
$query = "ALTER TABLE `files` DROP file_id";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $query = "ALTER TABLE `files` AUTO_INCREMENT = 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
            /**/
$query = "ALTER TABLE `files` ADD `file_id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
        $stmt = $db->prepare($query);
        $stmt->execute();
    } catch (PDOException $e) {
        $error[] = $e->getMessage();
    }
    header("Location: uploadhistory.php");
}

//define page title
$title = 'My Projects';

//include header template
require('templates/header.php');

?>
<?php
//ob_end_flush();
//xsession_start();
// include ('includes/config.php');
// ODO: Fix
// include('classes/extract.php');
//require('classes/parser.php');

//ini_set('display_errors','on');

//if logged in redirect to members page
if(!$user->is_logged_in()){unset($_POST['cool']);unset($_POST['process']); header('Location: login.php');  }
/*
$_POST['pro'] = 1;*/
$_POST['cool'] = 1;
$_POST['process'] = 1;

// TOD: Fix lines below
$fileID = trim($_GET['id']);
$query = "SELECT * FROM files  where file_id = '$fileID'";
foreach ($database->getRow($query) as $row){
$iges_file = $row['filename'];
$unit = $row['units'];
$caption = $row['file_caption'];

}

$title = 'Draw';
//include header template
require('layout/header.php');
/*
if (isset($_SESSION['BENDS'])){
$obj = (($_SESSION['BENDS']));
//$objj = unserialize($obj);
$temp = (unserialize($obj));
print_r($temp);
}*/
?>
<!DOCTYPE html>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<!-- script src="assets/js/jquery.js"></script -->
<script src="assets/js/springy.js"></script>
<script src="assets/js/springyui.js"></script>
		<style>

			* {
				box-sizing: border-box;
			}

			html {
				height: 100%;
			}

			body {
				background-color: #ffffff;
				margin: 0px;
				height: 100%;
				color: #555;
				//font-family: 'inconsolata';
				font-size: 15px;
				lline-height: 18px;
				overflow: hidden;
			}

			h1 {
				margin-top: 30px;
				margin-bottom: 40px;
				margin-left: 20px;
				font-size: 25px;
				font-weight: normal;
			}

			h2 {
				font-size: 20px;
				font-weight: normal;
			}

			a {
				color: #2194CE;
				text-decoration: none;
			}

			#panel {
				position: fixed;
				left: 0px;
				top:50px;
				width: 400px;
				height: 100%;
				overflow: auto;
				background: #fafafa;
			}

			#panel #content {
				padding: 0px 20px 20px 20px;
			}

			#panel #content .link {
				color: #2194CE;
				text-decoration: none;
				cursor: pointer;
				display: block;
			}

			#panel #content .selected {
				color: #ff0000;
			}

			#panel #content .link:hover {
				text-decoration: underline;
			}

			#viewer {
				position: absolute;
				border: 0px;
				left: 400px;
				width: calc(100% - 310px);
				height: 100%;
				overflow: auto;
			        cursor: move;
			}

			#button {
				position: fixed;
				bottom: 20px;
				right: 20px;
				padding: 8px;
				color: #fff;
				background-color: #555;
				opacity: 0.7;
			}

			#button:hover {
				cursor: pointer;
				opacity: 1;
			}

			.filterBlock{
				margin: 20px;
				position: relative;
			}

			.filterBlock p {
				margin: 0;
			}

			#filterInput {
				width: 100%;
				padding: 5px;
				font-family: inherit;
				font-size: 15px;
				outline: none;
				border: 1px solid #dedede;
			}

			#filterInput:focus{
				border: 1px solid #2194CE;
			}

			#clearFilterButton {
				position: absolute;
				right: 6px;
				top: 50%;
				margin-top: -8px;
				width: 16px;
				height: 16px;
				font-size: 14px;
				color: grey;
				text-align: center;
				line-height: 0;
				padding-top: 7px;
				opacity: .5;
			}

			#clearFilterButton:hover {
				opacity: 1;
			}

			.filtered {
				display: none !important;
			}

			#panel li b {
				font-weight: bold;
			}

			/* mobile */

			#expandButton {
				display: none;
				position: absolute;
				right: 20px;
				top: 12px;
				width: 32px;
				height: 32px;
			}

			#expandButton span {
				height: 2px;
				background-color: #2194CE;
				width: 16px;
				position: absolute;
				left: 8px;
				top: 10px;
			}

			#expandButton span:nth-child(1) {
				top: 16px;
			}

			#expandButton span:nth-child(2) {
				top: 22px;
			}

			@media all and ( max-width: 640px ) {
				h1{
					margin-top: 20px;
					margin-bottom: 20px;
				}
				#panel{
					position: absolute;
					left: 0;
					top: 0;
					height: 480px;
					width: 100%;
					right: 0;
					z-index: 100;
					overflow: hidden;
					border-bottom: 1px solid #dedede;
				}
				#content{
					position: absolute;
					left: 0;
					top: 90px;
					right: 0;
					bottom: 0;
					font-size: 17px;
					line-height: 22px;
					overflow: auto;
				}
				#viewer{
					position: absolute;
					left: 0;
					top: 56px;
					width: 100%;
					height: calc(100% - 56px);
				}
				#expandButton{
					display: block;
				}
				#panel.collapsed{
					height: 56px;
				}
			}

.panel-body1 {
background-color:#EDEDED;}

.btn-default, .modal-header, .modal-footer {
background-color: #EDEDED;
}

.panel-body2 {
background-color:#ABEDF6;}

 .modal-body1 {
background-color: #EDEDFF;
}

		</style>
	</head>

	<body>

		<div id="panel" class="collapsed">

<div class = "panel panel-default">
   <div class = "panel-heading">
      <h3 class = "panel-title">
         <b>Model Features</b>
      </h3>
   </div>

   <div class = "panel-body panel-body1">

			<!--<a id="expandButton" href="#">
				<span></span>
				<span></span>
				<span></span>
			</a>
			div class="filterBlock" >
				<input type="text" id="filterInput" placeholder="Type to filter"/>
				<a href="#" id="clearFilterButton" >x</a><div id="content">   </div>

			</div-->

<div class = "panel panel-default">
   <div class = "panel-heading">
      <h3 class = "panel-title">
         <b>Face Adjacency Graph</b>
      </h3>
   </div>

   <div class = "panel-body">
<script>
var graph = new Springy.Graph();
/*
var dennis = graph.newNode({
  label: 'Dennis',
  ondoubleclick: function() { console.log("Hello!"); }
});*/

<?php
$query = "SELECT * FROM bends  where file_id = '$fileID'";
$i = 1;
foreach ($db->query($query) as $row){
//$iges_file = $row['file_id'];
//if (($i % 2) == 0)
{
?>
if (!face<?php echo $row['face1_id'];?>)
var face<?php echo $row['face1_id'];?> = graph.newNode({label: 'Face <?php echo $row['face1_id'];?>'});
//var bend<?php echo $row['bend_id'];?> = graph.newNode({label: 'Bend <?php echo $row['bend_id'];?>'});
if (!face<?php echo $row['face2_id'];?>)
var face<?php echo $row['face2_id'];?> = graph.newNode({label: 'Face <?php echo $row['face2_id'];?>'});
<?php
}
++$i;
}
?>
<?php
$i = 1;
foreach ($db->query($query) as $row){
//if (($i % 2) == 0)
{
$colours = array("#00A0B0","#6A4A3C","#CC333F","#EB6841", "#EDC951", "#7DBE3C", "#000000");
$key = array_rand($colours, 1);
?>
graph.newEdge(face<?php echo $row['face1_id'];?>, face<?php echo $row['face2_id'];?>, {color: '<?php echo $colours[$key];?>'});
//graph.newEdge(face<?php echo $row['face1_id'];?>, bend<?php echo $row['bend_id'];?>, {color: '#00A0B0'});
//graph.newEdge(face<?php echo $row['face2_id'];?>, bend<?php echo $row['bend_id'];?>, {color: '#00A0B0'});
<?php
}
++$i;
}
?>
jQuery(function(){
  var springy = window.springy = jQuery('#springydemo').springy({
    graph: graph,
    nodeSelected: function(node){
      console.log('Node selected: ' + JSON.stringify(node.data));
    }
  });
});
</script>
      <canvas id="springydemo" width="330" height="300"></canvas>
   </div>
</div>
<div class = "panel panel-default">
   <div class = "panel-heading"><b>Bend Features</b></div>
   <table class = "table table-hover">
     <thead>
    <tr>
      <th>Bend ID</th>
      <th>Radius</th>
      <th>Thickness</th>
      <th>Length</th>
      <th>Angle</th>
    </tr>
  </thead>
<?php
$query = "SELECT * FROM bends  where file_id = '$fileID'";
$i = 1;
foreach ($db->query($query) as $row){
//$iges_file = $row['file_id'];
//if (($i % 2) == 0)
{
?>

      <tr>
         <td><?php echo $row['bend_id']; ///2;?></td>
         <td><?php echo $row['bend_radius'];?></td>
         <td><?php echo $row['bend_thickness'];?></td>
         <td><?php echo $row['bend_length'];?></td>
         <td><?php echo $row['angle']."&deg";?></td>
      </tr>
<?php
}
++$i;
}
?>
   </table>

</div>
<div class = "panel panel-default" style="margin-bottom:30px">
   <div class = "panel-heading"><b>Face Relationships</b></div>

   <table class = "table table-hover">
     <thead>
    <tr>
      <th>Face 1</th>
      <th>Bend</th>
      <th>Face 2</th>
    </tr>
  </thead>
<?php
$i = 1;
foreach ($db->query($query) as $row){
//if (($i % 2) == 0)
{
?>
      <tr>
         <td><?php echo $row['face1_id'];?></td>
         <td><?php echo $row['bend_id'];//2;?></td>
         <td><?php echo $row['face2_id'];?></td>
      </tr>
<?php
}
++$i;
}
?>
<span></span>
   </table>

</div>


</div>
</div>
		</div>
		<iframe id="viewer" name="viewer" allowfullscreen onmousewheel="" src=<?php echo "A_draw.php?id=".$fileID;?>></iframe>

   <div
 class="container">

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close"
data-dismiss="modal">&times;</button>
          <h3 class="modal-title">Bend Features : (<?php echo $caption;?>)</h3>
        </div>
        <div class="modal-body">
          <p>
             <table class = "table table-hover">
     <thead>
    <tr>
      <th>Bend ID</th>
      <th>Radius<?php echo "(mm)"; ?></th>
      <th>Thickness<?php echo "(mm)"; ?></th>
      <th>Length<?php echo "(".$unit.")"; ?></th>
      <th>Angle(Degrees)</th>
      <th>Height<?php echo "(mm)"; ?></th>
      <th>Bending Force(N)</th>
    </tr>
  </thead>
<?php
$query = "SELECT * FROM bends  where file_id = '$fileID'";
$i = 1;
foreach ($db->query($query) as $row){
//$iges_file = $row['file_id'];
//if (($i % 2) == 0)
{
?>

      <tr>
         <td><?php echo $row['bend_id'];///2;?></td>
         <td><?php echo $row['bend_radius'];?></td>
         <td><?php echo $row['bend_thickness'];?></td>
         <td><?php echo $row['bend_length'];?></td>
         <td><?php echo $row['angle']."&deg";?></td>
         <td><?php echo $row['bend_height'];?></td>
         <td><?php echo $row['bending_force'];?></td>
      </tr>
<?php
}
++$i;
}
?>
   </table></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-lg"
data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myAFG" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close"
data-dismiss="modal">&times;</button>
          <b><h3 class="modal-title">Face Adjacency Graph</h3></b>
        </div>
        <div class="modal-body ">
<script>

function drawAFG(){
var graph = new Springy.Graph();
/*
var dennis = graph.newNode({
  label: 'Dennis',
  ondoubleclick: function() { console.log("Hello!"); }
});*/

<?php
$query = "SELECT * FROM bends  where file_id = '$fileID'";
$i = 1;
foreach ($db->query($query) as $row){
//$iges_file = $row['file_id'];
//if (($i % 2) == 0)
{
?>
if (!face<?php echo $row['face1_id'];?>)
var face<?php echo $row['face1_id'];?> = graph.newNode({label: 'Face <?php echo $row['face1_id'];?>'});
var bend<?php echo $row['bend_id'];?> = graph.newNode({label: 'Bend <?php echo $row['bend_id'];///2;?>'});
if (!face<?php echo $row['face2_id'];?>)
var face<?php echo $row['face2_id'];?> = graph.newNode({label: 'Face <?php echo $row['face2_id'];?>'});
<?php
}
++$i;
}
?>
<?php
$colours = array("#00A0B0","#6A4A3C","#CC333F","#EB6841", "#EDC951", "#7DBE3C", "#000000");

$i = 1;
foreach ($db->query($query) as $row){
//if (($i % 2) == 0)
{
$key = array_rand($colours, 1);

?>
//graph.newEdge(face<?php echo $row['face1_id'];?>, face<?php echo $row['face2_id'];?>, {color: '#00A0B0'});
graph.newEdge(face<?php echo $row['face1_id'];?>, bend<?php echo $row['bend_id'];?>, {color: '<?php echo $colours[$key];?>'});
<?php $key = array_rand($colours, 1); ?>
graph.newEdge(face<?php echo $row['face2_id'];?>, bend<?php echo $row['bend_id'];?>, {color: '<?php echo $colours[$key];?>'});
<?php
}
++$i;
}
?>
jQuery(function(){
  var springy = window.springy = jQuery('#springyAFG').springy({
    graph: graph,
    nodeSelected: function(node){
      console.log('Node selected: ' + JSON.stringify(node.data));
    }
  });
});

}

drawAFG();
</script>
      <canvas id="springyAFG" width="880" height="380"></canvas>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-lg"
data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<footer>
<div class="icon-bar">
<a href="myprojects.php"><i class="fa fa-arrow-left"></i></a>
<a href="#"><i class="fa fa-eye"></i></a>
<a class="active" href="#"><i class="fa fa-bars"></i></a>
<!-- <a href="myprojects.php"><i class="fa fa-share"></i></a> -->
<!-- <a href="#"><i class="fa fa-envelope"></i></a> -->
<!-- <a href="#"><i class="fa fa-globe"></i></a> -->
<a href="#"><i class="fa fa-share-alt"></i></a>
<a href="#"><i></i></a>
</div>
</footer>
<?php
//include header template
// get the contents, and echo it out.
require('templates/footer.php');
?>
