<?php
/* ENTIRY 126 RBSpline Curve */

class RBSplineCurve {

  public $K;
  public $Degree;
  public $PROP1;
  public $PROP2;
  public $PROP3;
  public $PROP4;
  public $Knot_Sequence; // ARRAY
  public $Weights; // ARRAY
  public $Control_Points; //
  public $Control_Pend;

  function __construct() {
    $this->Knot_Sequence = array();
    $this->Weights = array();
  }

  public function rbsplineCurveTract($dsection = null, $psection = null) {
    global $xtract;
    $counter = 0;

    if ($dsection != null)
    foreach ( $dsection as $value ) {

      if ($value->EntityType == 126)
      {
        $id = $value->PointerData;
        $pentry = $psection [$id];

        $arr = $xtract->multiexplode ( array (
          ",",
          ";"
        ), $pentry );

        $j = 1;
        {
          if (($j + 1) >= count ( $arr ) && ($j + 2) >= count ( $arr )) {
            // echo "out";
            break;
          }
          ++ $counter;
          $K = trim ( $arr [$j] );
          $M = trim ( $arr [$j + 1] );

          $N = 1 + $K - $M;
          $A = $N + 2 * $M;

          $this->K = $K;
          $this->Degree = $M;

          $this->PROP1 = $arr [$j + 2];
          $this->PROP2 = $arr [$j + 3];
          $this->PROP3 = $arr [$j + 4];
          $this->PROP4 = $arr [$j + 5];

          $this->Knot_Sequence = array ();
          $this->Weights = array ();
          $this->Control_Points = array ();

          $knotstart = $j + 6;
          $knotend = $knotstart + $A;

          $weightstart = $knotend + 1;
          $weightend = $weightstart + $K;

          $controlpstart = $weightend + 1;
          $controlpend = 9 + $A + (4 * $K) + 2;

          for($x = 0, $i = $knotstart; $i <= ($knotend); $i ++, $x ++) {
            $kn = ($arr [$i]);

            $pos = strpos ( $kn, "D" );

            if ($pos) {
              $kn [$pos] = "E";
            }

            $this->Knot_Sequence [$x] = $kn;
          }

          for($x = 0, $i = $weightstart; $i <= ($weightend); $i ++, $x ++) {
            $kn = ($arr [$i]);

            $pos = strpos ( $kn, "D" );

            if ($pos) {
              $kn [$pos] = "E";
            }

            $this->Weights [$x] = $kn;
          }

          for($x = 0, $i = $controlpstart; $i <= ($controlpend); $i ++, $x ++) {
            $kn = ($arr [$i]);

            $pos = strpos ( $kn, "D" );

            if ($pos) {
              $kn [$pos] = "E";
            }

            $this->Control_Points [$x] = $kn;
          }

          $this->Control_Pend = count ( $this->Control_Points );

          $edgetype [$id] = $this;
        }
      }
    }

    return ($edgetype);
  }
}

?>
