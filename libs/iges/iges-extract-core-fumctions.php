<?php

class Extract {

  function __construct() {

  }

  public function multiexplode($delimeters, $string) {
    $ready = str_replace ( $delimeters, $delimeters [0], $string );
    $launch = explode ( $delimeters [0], $ready );

    return $launch;
  }

  private function addFileInformation($gsection) {
    $unit = trim ( $gsection [13] );
    $dim = null;

    // echo $unit."dd";

    switch ($unit) {
      case 1 :
      $dim = "inches";
      break;
      case 2 :
      $dim = "mm";
      break;
      case 3 :
      $dim = "special";
      break;
      case 4 :
      $dim = "ft";
      break;
      case 5 :
      $dim = "miles";
      break;
      case 6 :
      $dim = "metres";
      break;
      case 7 :
      $dim = "Km";
      break;
      case 8 :
      $dim = "mils";
      break;
      case 9 :
      $dim = "microns";
      break;
      case 10 :
      $dim = "cm";
      break;
      case 11 :
      $dim = "minchs";
      break;
      default :
      break;
    }

    return $dim;
  }

  public function removeD($arr) {
    $kn = trim ( $arr );

    $pos = strpos ( $kn, "D" );

    if ($pos) {
      $kn [$pos] = "E";
    }

    return $kn;
  }

  public function display() {
    $x = new Extract ();
    $flag = false;

    $loops = $this->face_list;
    $faces = $this->face_list;

    $p = 0;

    foreach ( $loops as $loop ) {
      $face1 = null;
      $face2 = null;
      $bendE = null;

      $i = 0;
      foreach ( $loop->External_Loop->Edge_List as $bedl ) {
        if ($bedl->Edge_Type == "Line" && $loop->Bend_ID != - 1) {

          $i ++;
          $bendE = $bedl;

          foreach ( $faces as $face ) {
            if ($face->Bend_ID == - 1) {
              foreach ( $face->External_Loop->Edge_List as $fedl )
              if ($bendE == $fedl) {
                $flag = true;
                if ($face1 == null)
                $face1 = $face;
                else
                $face2 = $face;
              } else
              continue;
            } else
            continue;

            if ($flag == true) {
              $flag = false;
              break;
            }
          }
        } else {
        }

        if ($i == 2) {
          $bendLength = $this->fx->computeBendLength ( $bendE );
          $angle = $this->fx->computeAngle ( $face1->External_Loop->Normal, $face2->External_Loop->Normal );
          $i = 0;
          $this->Bends [$p] = new Bend ();
          $this->Bends [$p]->Bend_ID = $loop->Bend_ID;
          $this->Bends [$p]->Face1 = $face1->Face_ID;
          $this->Bends [$p]->Face2 = $face2->Face_ID;
          $this->Bends [$p]->Angle = $angle;
          $this->Bends [$p]->Bend_Loop = $loop->External_Loop->Loop_ID;
          $this->Bends [$p]->Bend_Length = $bendLength;

          ++ $p;
        }
      }
    }

    $this->insertBendFeatures ();
  }
}

$xtract = new Extract();

//var_dump($xtract);

?>
