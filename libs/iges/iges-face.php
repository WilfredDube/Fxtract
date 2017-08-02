<?php

class Face {

  var $Face_ID;
  var $Bend_ID;
  var $Dimension;
  var $Surface_Type;
  var $Surface_Pointer;
  var $Thickness;
  var $External_Loop;
  var $Internal_Loop_Count;
  var $Internal_Loop; // ARRAY

  private $face_list;
  static $shell;
  static $surface;

  function __construct() {
    $this->face_list = array();
    $this->Internal_Loop = array();
    self::$shell = new Shell();
    self::$surface = new RBSplineSurface();
  }

  public function facetract($dsection, $psection, $loops, $vertexlist, $edgeList, $dim) {
    global $xtract;
    $counter = 0;
    $bend = 0;
    $nfaces = 0;
    $face = null;
    static $p = 1;
    $f = 1;

    // var_dump($edgeList);
    $i = 1;
    foreach ( $dsection as $value )
    {

      if ($value->EntityType == 510)
      {
        // echo $i."<br>";
        // var_dump($value);
        $pentry = $psection [$value->PointerData];

        $arr = $xtract->multiexplode ( array (
          ",",
          ";"
        ), $pentry );

        $face = new Face ();
        $pSurf = trim ( $arr [1] );
        $Num_of_loops = $arr [2];
        $OuterLoopFlag = $arr [3];
        $pLoop = $arr [4];

        $index = $dsection [$pSurf]->PointerData;
        $ret = self::$surface->RBSplineSurfaceTract( $psection [$index] );
        // var_dump($ret);
        $ppentry_ploop = $dsection [$pLoop]->PointerData;

        $counter ++;
        if (($ret->PROP3) == 1) {
          if ($loops [$ppentry_ploop]->Loop_Type != "BEND") {
            $face->Surface_Type = "Plane Surface";
            $face->Face_ID = $counter;
            $face->Bend_ID = - 1;
            $face->Dimension = $dim;
            $nfaces ++;
          } else {
            $face->Surface_Type = "Edge Side";
            $face->Face_ID = $counter;
            $face->Bend_ID = - 1;
            $face->Dimension = $dim;
            $nfaces ++;
          }
        } else {
          $bend ++;
          $face->Surface_Type = "Curved Surface";
          $face->Face_ID = - 1;
          $face->Bend_ID = $bend;
          $face->Dimension = $dim;
        }

        $face->External_Loop = $loops [$ppentry_ploop];

        if ($face->Surface_Type == "Plane Surface") {

            // var_dump($face->External_Loop->Edge_List);
            $fx = new Computation();
            $face->Thickness = $fx->computeThickness($face->External_Loop->Edge_List);

            // var_dump($face->Thickness);
        }

        $this->face_list [$counter] = new Face ();
        $this->face_list [$counter] = $face;
        // $_SESSION['facelist'][$counter] = self::$face_list[$counter];
      //   echo $i."<br>";
      // var_dump($face);
        $i++;
      }
    }

    $arra = array();
    if (isset ( $this->face_list ))
    foreach ($this->face_list as $value) {
      if ($value->Surface_Type == "Plane Surface") {
        $arra[] = round($value->Thickness, 2);
      }
    }
    natsort($arra);

    // var_dump($arra);
    $arra = array_shift($arra);

    foreach ($this->face_list as $value) {
      if ($value->Surface_Type == "Plane Surface") {
        $value->Thickness = $arra;
      }
    }
    //
    // foreach ($this->face_list as $value) {
    //   if ($value->Surface_Type == "Plane Surface") {
    //     var_dump($value);
    //   }
    // }
    // var_dump($this->face_list);

    self::$shell->createShell($vertexlist, $edgeList, $loops, $this->face_list);
  }

  public function getFaceList() {
    return $this->face_list;//$_SESSION['facelist'];
  }
}


?>
