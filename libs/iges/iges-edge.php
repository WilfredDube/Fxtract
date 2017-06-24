<?php
//require_once('../iges/iges-parameter.php');

class Edge {

  public $Edge_ID;
  public $Edge_Type;
  public $Start_Vertex;
  public $Terminate_Vertex;
  //public $Concavity;
  //public $Face_Pointer; // ARRAY
  //public $Loop_Pointer; // ARRAY
  //public $Dimension;
  public $edge504;
  private static $edgelist;

  function __construct () {
    self::$edgelist = array();
    //$this->Face_Pointer = array();
    //$this->Loop_Pointer = array();
  }

  public function edgetract($dsection=null, $psection = null, $edgetype = null, $vertex = null)
  {
    global $xtract;
    $counter = 0;

    $vertexlist = $vertex->getVertexList();

    //var_dump($vertexlist[1]->Vertex);

    if ($dsection != null)
    foreach ( $dsection as $value ) {
      if ($value->EntityType == 504) // && $set == false)
      {
        $pentry = $psection [$value->PointerData];

        $arr = $xtract->multiexplode ( array (
          ",",
          ";"
        ), $pentry );

        for($j = 2, $id = 1; $j < count ( $arr ); $j ++, $id ++) {

          if (($j + 1) >= count ( $arr ) && ($j + 2) >= count ( $arr )) {
            break;
          }
          ++ $counter;

          $pointer = trim ( $arr [$j] );
          $sindex = trim ( $arr [$j + 2] );
          $tindex = trim ( $arr [$j + 4] );

          //echo $tindex."<br/>";

          $this->Edge_ID = $id;

          if ($edgetype [$dsection [$pointer]->PointerData]->PROP3 == 1)
          $this->Edge_Type = "Line";
          else
          $this->Edge_Type = "Arc";

          $this->Start_Vertex = new Vertex ();
          $this->Start_Vertex = $vertexlist [$sindex]->Vertex;
          $this->Terminate_Vertex = new Vertex ();
          $this->Terminate_Vertex = $vertexlist [$tindex]->Vertex;

          //  var_dump($this);
          self::$edgelist [$id] = new EdgeList ();
          self::$edgelist [$id]->Edge_Count = trim ( $arr [1] );
          self::$edgelist [$id]->Edge_List = array ();
          //self::$edgelist [$id]->Edge_List = new Edge ();
          self::$edgelist [$id]->Edge_List = $this;

          //var_dump(self::$edgelist [$id]->Edge_List);

          $j = $j + 4;

          foreach ( $edgetype as $edt ) {
            $v = new Vertex ();
            $v1 = new Vertex ();

            $c = $edt->Control_Pend;

            $v->x = $edt->Control_Points [0];
            $v->y = $edt->Control_Points [1];
            $v->z = $edt->Control_Points [2];

            $v1->x = $edt->Control_Points [$c - 3];
            $v1->y = $edt->Control_Points [$c - 2];
            $v1->z = $edt->Control_Points [$c - 1];
          }
        }
      }

      if ($value->EntityType == 504) {
        $this->edge504 [$value->LineNumber] = new EdgeList ();
        $this->edge504 [$value->LineNumber] = self::$edgelist;
      }
    }

    //var_dump(self::$edgelist[1]->Edge_List);
    return self::$edgelist;
  }

  public function getEdge504() {
    return $this->edge504;
  }


  public function getEdgeList() {
  //for($id = 1; $id <= count(self::$edgelist); $id ++)
    //var_dump(self::$edgelist);//[$id]->Vertex);

    return self::$edgelist;
  }
}
// $edge = new Edge();
// $edge->edgetract();
//
// //$p = new Vertex();
//
// echo get_class($edge)."\n";
// //echo get_class($p)."\n";
//
// var_dump($edge);
// //var_dump($p);
?>
