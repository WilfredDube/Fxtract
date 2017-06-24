<?php

class Face {

  var $Face_ID;
  var $Bend_ID;
  var $Surface_Type;
  var $Surface_Pointer;
  var $External_Loop;
  var $Internal_Loop_Count;
  var $Internal_Loop; // ARRAY

	function __construct() {
		$this->Internal_Loop = array();
		$shell = new $Shell();
	}

  public function facetract($dsection, $psection, $loops, $face_list) {
    $counter = 0;
    $bend = 0;
    $nfaces = 0;
    $face = null;
    static $p = 1;
    $f = 1;

    foreach ( $dsection as $value ) {
      if ($value->EntityType == 510)
      {

        $pentry = $psection [$value->PointerData];

        $arr = $this->multiexplode ( array (
          ",",
          ";"
        ), $pentry );

        $face = new Face ();
        $pSurf = trim ( $arr [1] );
        $Num_of_loops = $arr [2];
        $OuterLoopFlag = $arr [3];
        $pLoop = $arr [4];

        $index = $dsection [$pSurf]->PointerData;
        $ret = $this->RBSplineSurface ( $psection [$index] );

        $ppentry_ploop = $dsection [$pLoop]->PointerData;

        $counter ++;
        if (($ret->PROP3) == 1) {
          if ($loops [$ppentry_ploop]->Loop_Type != "BEND") {
            $this->Surface_Type = "Plane Surface";
            $this->Face_ID = $counter;
            $this->Bend_ID = - 1;
            $nfaces ++;
          } else {
            $this->Surface_Type = "Edge Side";
            $this->Face_ID = $counter;
            $this->Bend_ID = - 1;
            $nfaces ++;
          }
        } else {
          $bend ++;
          $this->Surface_Type = "Curved Surface";
          $this->Face_ID = - 1;
          $this->Bend_ID = $bend;
        }

        $this->External_Loop = $loops [$ppentry_ploop];
        $face_list [$counter] = new Face ();
        $face_list [$counter] = $face;
      }
    }

    if (isset ( $face_list ))
    ;

    $Shell->createShell ();
  }

}
?>
