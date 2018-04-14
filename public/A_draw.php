<?php
//ob_end_flush();
require_once('../includes/initialize.php');

//ini_set('display_errors','on');

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

// print_r($loops->getLoops());
// var_dump (count($loops->getLoops()));
// print_r($loops);
// $bends = new Bend();
// $bendz = $bends->bendTract($loops->getLoops());
//
//
// $bends->insertBendFeatures($bendz, $dim);

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
  <?php //print_r ($_SESSION['ERROR']); ?>
  </body>
</html>
