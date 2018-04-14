<?php
//ob_end_flush();
require_once('../includes/initialize.php');

ini_set('display_errors','off');

//if logged in redirect to members page
if(!$user->is_logged_in()){
  unset($_POST['cool']);
  unset($_POST['process']);
  header('Location: login.php');
}

$_POST['pro'] = 1;
$_POST['cool'] = 1;
$_POST['process'] = 1;

$fileID = trim($_GET['id']);

// echo $_SESSION['projectid'];
$query = "select filename from files where fileid=? LIMIT 1";
$rows = $database->getRow($query, [$fileID]);
// print_r(array_shift($rows));
// foreach ($rows as $row){
$iges_file = array_shift($rows);

// echo "File : ".$iges_file;

$_SESSION['fileid'] = $fileID;
//echo $iges_file;
// }

//$title = 'Draw';
//include header template
//require('layout/header.php');
?>
<?php
ob_end_flush();

$FILE_REPOSITORY = User::getUserFolder();
$parser = new Parser($FILE_REPOSITORY.'/'.$iges_file);
// print_r($parser);
$total = $parser->count_dline();

$parser->get_back();
$dline1 = $parser->get_line();
$dline1 = $parser->jump_to_dsection($dline1);
$dline2 = $parser->get_line();

$parser->parse_d_entry($dline1, $dline2);

for ($i = 1; strpos($dline1, 'D') == true; $i++)
{
  $dline1 = $parser->get_line();
  $dline2 = $parser->get_line();

  if ($dline2 == null)
    break;

  $parser->parse_d_entry($dline1, $dline2);
}

$psection = $parser->param_section();
$gsection = $parser->global_section();
$dsection = $parser->getDsection();

// var_dump($gsection);
// var_dump($vtlist);
$edgetype = array();
$rbspline = new RBSplineCurve();
$edgetype = $rbspline->rbsplineCurveTract($dsection, $psection);//, $edgetype);

// var_dump($edgetype);

// Extraction of vertextes to create the vertex list
$vt = new Vertex();
$vtlist = $vt->vertract($dsection, $psection);
// $vtlist = $vt->getVertexList();

// if (!empty($vlist))
// var_dump($vtlist);

// var_dump($edgetype);
$edge = new Edge();
$edgelist = $edge->edgetract($dsection, $psection, $edgetype, $vt);

// var_dump($edgelist);
//($edge->getEdgeList());
$x = new Extract();
$dim = $x->getDimensions($gsection);
$loops = new Loop();
$loops->looptract($dsection, $psection, $edge, $vtlist, $dim);

// var_dump($loops);
// var_dump($loops->getLoops());
// print_r($loops);
$bends = new Bend();
$bendz = $bends->bendTract($loops->getLoops());
//
//
$bends->insertBendFeatures($bendz, $dim);

// var_dump($bendz);
// print_r($edgetype[90]->Control_Points);
//$xt->RBSplineSurface();
$array = $edgetype;

// echo $fileID;
// $row = BendFeatures::find_feature_by_id($fileID);
// print_r($row);
// var_dump($edgetype);
// echo $array;
// $loops = ($xt->getLoops ());
//$shell = $xt->getShell();

// $edget = $xt->getEdge504 ();

/*$i = 1;
foreach ($edget as $edgt)
        foreach ($edgt as $elist){
        echo $i."=> ";
        print_r($elist->Edge_List->Edge_Type);//." ";
        echo "<br/>";
        $i++;
        }
*/
// $xt->display();
//$_SESSION['BENDS'] = arra();
// $_SESSION['BENDS'] = serialize($xt->getBends());

//print_r (unserialize($_SESSION['BENDS']));
//$xt->facetract();

?>
<head>
<?php
//include header template
//require('layout/footer.php');
?>
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>
			body {
				width: 50%;
				/*background: #B3B3B3;*
				bottom: 10px;*/
				/*cursor: crosshair;*/
				/*//margin-top: 5px;
				//margin-left: 5px;*/
			}
			#info {
				position: absolute;
				top: 10px;
				width: 100%;
				padding: 5px;
				text-align:center;
			}
		</style>
    <style>
			body { margin: 0; }
			canvas { width: 100%; height: 100% }
		</style>
	</head>
  <body>

    <canvas id="debug" style="position:absolute; left:100px"></canvas>

    <script src="assets/js/three.min.js"></script>

    <script src="assets/js/curves/NURBSCurve.js"></script>
    <script src="assets/js/curves/NURBSUtils.js"></script>

    <script src="assets/js/renderers/Projector.js"></script>
    <script src="assets/js/renderers/CanvasRenderer.js"></script>

    <script src="assets/js/libs/stats.min.js"></script>

    <script src="assets/js/controls/TrackballControls.js"></script>
    <script src="assets/js/controls/OrbitControls.js"></script>
    <script src="assets/js/Detector.js"></script>
    <script src="assets/js/libs/stats.min.js"></script>
    <script>
    			var scene = new THREE.Scene();
    			var camera = new THREE.PerspectiveCamera( 75, window.innerWidth/window.innerHeight, 0.1, 1000 );

    			var renderer = new THREE.WebGLRenderer({ antialias: true });
          renderer.setSize( window.innerWidth, window.innerHeight );
    			// renderer.setSize( 970, 570 );
          renderer.setClearColor( 0xB3B3B3);
    			document.body.appendChild( renderer.domElement );

    			// var geometry = new THREE.BoxGeometry( 1, 1, 1 );
    			// var material = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
    			// var cube = new THREE.Mesh( geometry, material );
    			// scene.add( cube );

  //         var geometry = new THREE.Geometry();
  //
  // geometry.vertices.push(
  // 	new THREE.Vector3( -1,  1, 0 ),
  // 	new THREE.Vector3( -1, -1, 0 ),
  // 	new THREE.Vector3(  1, -1, 0 )
  //
  // );
  //
  // geometry.faces.push( new THREE.Face3( 0, 1, 2 ) );
  //
  // geometry.computeBoundingSphere();
  // var material = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
  // var cube = new THREE.Mesh( geometry, material );
  //         scene.add( cube );
  var material = new THREE.LineBasicMaterial({
	color: 0x0000ff
});

var geometry = new THREE.Geometry();
geometry.vertices.push(
	new THREE.Vector3( -1, 0, 0 ),
	new THREE.Vector3( 0, 1, 0 ),
	new THREE.Vector3( 1, 0, 0 ),
  new THREE.Vector3( 0, -1, 0 ),
  new THREE.Vector3( -1, 0, 0 )
);

var line = new THREE.Line( geometry, material );
scene.add( line );
    			camera.position.z = 5;

    			var render = function () {
    				// requestAnimationFrame( render );

    				// cube.rotation.x += 0.1;
    				// cube.rotation.y += 0.1;

    				renderer.render(scene, camera);
    			};

    			render();

    </script>
  <?php //print_r ($_SESSION['ERROR']); ?>
  </body>
</html>
