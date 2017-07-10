<?php

class Bend {

  protected static $table_name = 'bends';
  public $Bend_ID;
  public $Face1;
  public $Face2;
  public $Angle;
  public $Bend_Loop;
  public $Bend_Length;
  public $Bend_Thickness;
  public $Bend_height;
  public $Bend_force;

  static $bends;

  function __construct(){
    self::$bends = array();
  }

  public function bendTract($face_list) {
    $x = new Extract ();
    $fx = new Computation ();
    $flag = false;

    $loops = $face_list;
    $faces = $face_list;

    $p = 0;

    foreach ( $loops as $loop )
    {
      $face1 = null;
      $face2 = null;
      $bendE = null;

      $i = 0;
      foreach ( $loop->External_Loop->Edge_List as $bedl ) {
        if ($bedl->Edge_Type == "Line" && $loop->Bend_ID != - 1) {

          $i ++;

          $bendE = $bedl;

          foreach ( $faces as $face ) {
            if ($face->Bend_ID == -1) {
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

          $bendLength = $fx->computeBendLength ( $bendE );
          $angle = $fx->computeAngle ( $face1->External_Loop->Normal, $face2->External_Loop->Normal );

          $i = 0;

          self::$bends [$p] = new Bend ();
          self::$bends [$p]->Bend_ID = $loop->Bend_ID;
          self::$bends [$p]->Face1 = $face1->Face_ID;
          self::$bends [$p]->Face2 = $face2->Face_ID;
          self::$bends [$p]->Angle = $angle;
          self::$bends [$p]->Bend_Loop = $loop->External_Loop->Loop_ID;
          self::$bends [$p]->Bend_Length = $bendLength;
          $_SESSION['bends'][$p] = self::$bends [$p];

          ++ $p;
        }
      }
    }

    return $_SESSION['bends'];
  }

  public function insertBendFeatures($Bends, $unit) {
    global $database;
    $fx = new Computation ();
    $tstrength = new TStrength();

    if (isset ( $Bends )) {
      foreach ( $Bends as $bend ) {
        $thick = 0.5;
        $rads = array (
          2,
          3,
          5,
          8
        );
        $key = array_rand ( $rads, 1 );

        $height = 2.5 * $thick * $rads [$key];

        $query = "SELECT filemodelmaterial from files where fileid=?";
        $materialID = $database->insert_id($query, "m_id", [$_SESSION ['fileid']]);

        $TS = $tstrength->getMaterialStrength($materialID);

        $force = $fx->computeBendingForce ( $this->Bend_Length, $thick, $unit, $TS );

        $id = $_SESSION ['fileid'] . "" . $this->Bend_ID . "" . $this->Bend_Loop;
        $this->Bend_height = $height;
        $this->Bend_force = $force;
        $this->Bend_Thickness = $thick;

        $query = 'INSERT INTO bends (b_id, file_id, bend_id,face1_id, face2_id, angle, bend_loop_id, bend_length, bend_thickness, bend_radius, bend_height, bending_force) VALUES (:b_id, :fileid, :bendid, :face1id ,:face2id, :angle ,:bend_loop_id, :bend_length, :bend_thickness, :bend_radius, :bend_height, :bending_force)';
        $params = array (
          ':b_id' => trim ( $id ),
          ':fileid' => $_SESSION ['fileid'],
          ':bendid' => $this->Bend_ID,
          ':face1id' => $this->Face1,
          ':face2id' => $this->Face2,
          ':angle' => $this->Angle,
          ':bend_loop_id' => $this->Bend_Loop,
          ':bend_length' => $this->Bend_Length,
          ':bend_thickness' => $this->Bend_Thickness,
          ':bend_radius' => $rads [$key],
          ':bend_height' => $this->Bend_height,
          ':bending_force' => $this->Bend_force
        );

        if ($database->insertRow($query, $params) == 1)
        echo "Successfully added new bend";
      } /* */
      $done = true;
    }
  }

  public function displaybends($bends) {
    echo count ( self::$bends );
    if (isset ( self::$bends )) {
      foreach ( self::$bends as $bend ) {
        print_r ( $bend->Angle );
        echo "<br/>";
      }
    }
  }

  public function getbends() {
    return $this;
  }

}
?>
