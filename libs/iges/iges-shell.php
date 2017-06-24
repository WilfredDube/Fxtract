<?php

class Shell {

  public $Vertex_List;
	public $Edge_List;
	public $Loop_list; // ARRAY
	public $Surface_List; // ARRAY
	public $Face_List; // ARRAY
	public $Name;
	public $IGES_File;

  function __construct () {
    $Loop_list = array();
    $Surface_List = array();
    $Face_List = array();
  }

  public function createShell($vertexlist, $edgeList, $loops, $face_list) {
		$this->Vertex_List = $vertexlist;
		$this->Edge_List = $edgelist;
		$this->Loop_List = $loops;
		$this->Face_List = $face_list;
	}

  function getShell() {
  	return $this;
  }
}
?>
