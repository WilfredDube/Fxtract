<?php

class Loop {

  public $Loop_ID;
  public $Normal;
  public $Edge_Count;
  public $Loop_Concavity;
  public $Loop_Type;
  public $Edge_List; // ARRAY
  public $Face_Pointers;
  public $loops;

  function __construct() {
    $this->Edge_List = array();
    $this->loops = array();
  }

  // Extract loops
  public function looptract($dsection, $psection, $edge) {
    global $xtract;
    //$xt = new Extract ();
    //$this->edgetract ();
    static $p = 1;

    foreach ( $dsection as $value ) {
      if ($value->EntityType == 508) // && $set == false)
      {
        $pentry = $psection [$value->PointerData];
        // echo $pentry."<br/>";

        $arr = $xtract->multiexplode ( array (
          ",",
          ";"
        ), $pentry );

        $edge504 = ($edge->getEdge504 ());
        // var_dump($edge504);
        $Edge_List = array ();

        $edgetuple = $arr [1];

        for($j = 2, $id = 1; $j < count ( $arr ); $j ++, $id ++) {

          if (($j + 1) >= count ( $arr ) && ($j + 2) >= count ( $arr )) {
            break;
          }

          $type = $arr [$j];
          $pointer = trim ( $arr [$j + 1] );
          $index = array ();

          $in = trim ( $arr [$j + 2] );

          $Edge_List [$id] = new EdgeList ();
          $Edge_List [$id] = $edge504 [$pointer] [$in]->Edge_List;

          if ($id == $edgetuple)
          $id = 1;

          $j += 4;
        }

        $this->loops [$value->PointerData] = new Loop ();
        $this->loops [$value->PointerData]->Edge_List = array ();
        $this->loops [$value->PointerData]->Edge_List = $Edge_List;
        $this->loops [$value->PointerData]->Edge_Count = count ( $Edge_List );
        $this->loops [$value->PointerData]->Loop_ID = $p;
        $this->loops [$value->PointerData]->Loop_Type = null;

        //var_dump($Edge_List);

        foreach ( $Edge_List as $edl ) {
          $i = 1;
          if ($edl->Edge_Type == "Arc") {
            $this->loops [$value->PointerData]->Loop_Type = "BEND";
            // echo "Bend";
            break;
          }

          ++ $i;
        }

        if ($this->loops [$value->PointerData]->Loop_Type != "BEND" || $this->loops [$value->PointerData]->Loop_Type == null) {
          $this->loops [$value->PointerData]->Loop_Type = "FACE";
          //var_dump($edge->getEdgeList());
          // var_dump($this->loops [$value->PointerData]->Edge_List);
          $fx = new Computation ( $this->loops [$value->PointerData]->Edge_List );
          $this->loops [$value->PointerData]->Normal = $fx->computeNormal ();
          // var_dump($this->loops [$value->PointerData]->Normal);
        } else
        $this->loops [$value->PointerData]->Normal = "bend";

        $p ++;
      }
    }
  }

  public function getLoops() {
    return $this;
  }
}
?>
