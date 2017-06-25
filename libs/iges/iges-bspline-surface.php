<?php

class RBSplineSurface {

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

  public function RBSplineSurfaceTract($surface) {
    global $xtract;

  		$counter = 1;

  		// echo count($dsection);
  		// $set = false;
  		// foreach ($dsection as $value)
  		{
  			// if ($value->EntityType == 128)// && $set == false)
  			{
  				// $id = $value->PointerData;
  				$pentry = $surface; // [$value->PointerData];
  				                    // echo $pentry."<br/><br/>";

  				$arr = $xtract->multiexplode ( array (
  						",",
  						";"
  				), $pentry );
  				// echo count($arr)." ";
  				// print_r($arr);
  				// echo"<br/><br/>";

  				$j = 1;
  				// for ($j = 1; $j < count($arr); $j++)
  				{
  					//$this = new SurfaceType ();

  					if (($j + 1) >= count ( $arr ) && ($j + 2) >= count ( $arr )) {
  						// echo "out";
  						// break;
  					}

  					$K1 = trim ( $arr [$j] );
  					$K2 = trim ( $arr [$j + 1] );

  					$M1 = trim ( $arr [$j + 2] );
  					$M2 = trim ( $arr [$j + 3] );

  					$N1 = 1 + $K1 - $M1;
  					$N2 = 1 + $K2 - $M2;
  					$A = $N1 + 2 * $M1;
  					$B = $N2 + 2 * $M2;
  					$C = (1 + $K1) * (1 + $K2);

  					// echo "[".$id."] K1 = ".$K1." M1 = ".$M1." N1 = ".$N1." K2 = ".$K2." M2 = ".$M2." N2 = ".$N2." A = ".$A." B = ".$B." C = ".$C."<br/>";

  					$this->K1 = $K1;
  					$this->K2 = $K2;

  					// echo $K1;
  					$this->Degree1 = $M1;
  					$this->Degree2 = $M2;

  					$this->PROP1 = $arr [$j + 4];
  					$this->PROP2 = $arr [$j + 5];
  					$this->PROP3 = $arr [$j + 6];
  					$this->PROP4 = $arr [$j + 7];
  					$this->PROP5 = $arr [$j + 8];

  					$this->Knot_Sequence1 = array ();
  					$this->Knot_Sequence2 = array ();
  					$this->Weights = array ();
  					$this->Control_Points = array ();

  					$knot1start = $j + 9;
  					$knot1end = $knot1start + $A;

  					$knot2start = $j + 10 + $A;
  					$knot2end = $knot2start + $B;

  					$weight1start = $knot2end + 1;
  					$weight1end = $knot2end + $C;

  					// $weight2start = $weight2end + 1;
  					// $weight2end = $knot2end + $C;

  					$controlpstart = $weight1end + 1;
  					$controlpend = 9 + $A + $B + (4 * $C) + 2;

  					for($x = 0, $i = $knot1start; $i <= ($knot1end); $i ++, $x ++) {
  						if ($arr [$i] == 0.)
  							$arr [$i] = 0.0;
  						else if ($arr [$i] == 1.)
  							$arr [$i] = 1.0;
  						$kn = ($arr [$i]);
  						// echo $kn."dasasasa";
  						$pos = strpos ( $kn, "D" );

  						if ($pos) {
  							$kn [$pos] = "E";
  						}

  						// echo ($kn)." ".$s;
  						$this->Knot_Sequence1 [$x] = $kn;
  					}

  					for($x = 0, $i = $knot2start; $i <= ($knot2end); $i ++, $x ++) {
  						if ($arr [$i] == 0.)
  							$arr [$i] = 0.0;
  						else if ($arr [$i] == 1.)
  							$arr [$i] = 1.0;
  						$kn = ($arr [$i]);
  						// echo $kn."dasasasa";
  						$pos = strpos ( $kn, "D" );

  						if ($pos) {
  							$kn [$pos] = "E";
  						}

  						// echo ($kn)." ".$s;
  						$this->Knot_Sequence2 [$x] = $kn;
  					}
  					for($x = 0, $i = $weight1start; $i <= ($weight1end); $i ++, $x ++) {
  						if ($arr [$i] == 0.)
  							$arr [$i] = 0.0;
  						else if ($arr [$i] == 1.)
  							$arr [$i] = 1.0;
  						$kn = ($arr [$i]);
  						// echo $kn."dasasasa";
  						$pos = strpos ( $kn, "D" );

  						if ($pos) {
  							$kn [$pos] = "E";
  						}

  						// echo ($kn)." ".$s;
  						// $this->Knot_Sequence[$x] = $kn;
  						$this->Weights [$x] = $kn;
  					}

  					for($x = 0, $i = $controlpstart; $i <= ($controlpend); $i ++, $x ++) {

  						if ($arr [$i] == 0.)
  							$arr [$i] = 0.0;
  						else if ($arr [$i] == 1.)
  							$arr [$i] = 1.0;
  							/*
  						 * X else ($arr[$i] == 2.)
  						 * $arr[$i] = 2.0;
  						 * else if ($arr[$i] == 3.)
  						 * $arr[$i] = 3.0;
  						 * else if ($arr[$i] == 4.)
  						 * $arr[$i] = 5.0;
  						 * else if ($arr[$i] == 6.)
  						 * $arr[$i] = 6.0;
  						 * else if ($arr[$i] == 7.)
  						 * $arr[$i] = 7.0;
  						 * else if ($arr[$i] == 8.)
  						 * $arr[$i] = 8.0;
  						 * else ($arr[$i] == 9.)
  						 * $arr[$i] = 9.0;
  						 */
  						$kn = ($arr [$i]);
  						// echo $kn."dasasasa";
  						$pos = strpos ( $kn, "D" );

  						if ($pos) {
  							$kn [$pos] = "E";
  						}

  						// echo ($kn)." ".$s;
  						$this->Control_Points [$x] = $kn;
  					}
  					/*
  					 * echo $pentry."<br/>";
  					 * echo "Knot Seq1 => ";
  					 * print_r ($edt->Knot_Sequence1);
  					 * echo "<br/>";
  					 *
  					 * echo "Knot Seq2 => ";
  					 * print_r ($edt->Knot_Sequence2);
  					 * echo "<br/>";
  					 *
  					 * echo "Weights => ";
  					 * print_r ($edt->Weights);
  					 * echo "<br/>";
  					 *
  					 * echo "Control Points => ";
  					 * print_r ($edt->Control_Points);
  					 * echo "<br/>"; /*
  					 */

  					// echo $edt->PROP1." ".$edt->PROP2." ".$edt->PROP3." ".$edt->PROP4."<br/>";

  					// $this->surfacetype[$id] = new SurfaceType();
  					// $this->surfacetype[$id] = $this;
  				}

  				// echo "<br/><br/>";
  			}
  		}

  		return $this;
  	}


}
?>
