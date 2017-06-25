<?php

require_once ('./includes/initialize.php');

$iges_file = '90.igs';

$parser = new Parser(FILE_REPOSITORY.$iges_file);
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

// Extraction of vertextes to create the vertex list
$vt = new Vertex();
$vt->vertract($dsection, $psection);
$vtlist = $vt->getVertexList();

$edgetype = array();
$rbspline = new RBSplineCurve();
$edgetype = $rbspline->rbsplineCurveTract($dsection, $psection);//, $edgetype);

$edge = new Edge();
$edgelist = $edge->edgetract($dsection, $psection, $edgetype, $vt);

//($edge->getEdgeList());
$loops = new Loop();
$loops->looptract($dsection, $psection, $edge);

// foreach ($edgelist as $value) {
// var_dump($value->Edge_List);
// }

//var_dump($_SESSION ['edgelist'][1]->Edge_List);

//var_dump($rbspline);
//var_dump($edgetype);
//var_dump($vtlist);
//echo "$dline1\n";

?>
