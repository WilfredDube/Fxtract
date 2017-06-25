<?php

class Bend {
  
  public $Bend_ID;
  public $Face1;
  public $Face2;
  public $Angle;
  public $Bend_Loop;
  public $Bend_Length;
  public $Bend_Thickness;

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

  public function insertBendFeatures($Bends, $gsection) {
    $fx = new Computation ();
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
        $unit = trim ( $gsection [13] );

        $fid = $_SESSION ['fileid'];
        $query = "SELECT file_material from files where file_id=$fid";
        foreach ( $db->query ( $query ) as $row )
        ;

        $mid = $row ['file_material'];
        $query = "SELECT tensile_strength from tstrength where m_id=$mid";
        foreach ( $db->query ( $query ) as $row )
        ;
        $TS = $row ['tensile_strength'];

        $force = $fx->computeBendingForce ( $this->Bend_Length, $thick, $unit, $TS );

        $id = $_SESSION ['fileid'] . "" . $this->Bend_ID . "" . $this->Bend_Loop;
        try { // echo "dsds";
          $stmt = $db->prepare ( 'INSERT INTO bends (b_id, file_id, bend_id,face1_id, face2_id, angle, bend_loop_id, bend_length, bend_thickness, bend_radius, bend_height, bending_force) VALUES (:b_id, :fileid, :bendid, :face1id ,:face2id, :angle ,:bend_loop_id, :bend_length, :bend_thickness, :bend_radius, :bend_height, :bending_force)' );
          $stmt->execute ( array (
            ':b_id' => trim ( $id ),
            ':fileid' => $_SESSION ['fileid'],
            ':bendid' => $this->Bend_ID,
            ':face1id' => $this->Face1,
            ':face2id' => $this->Face2,
            ':angle' => $this->Angle,
            ':bend_loop_id' => $this->Bend_Loop,
            ':bend_length' => $this->Bend_Length,
            ':bend_thickness' => $thick,
            ':bend_radius' => $rads [$key],
            ':bend_height' => $height,
            ':bending_force' => $force
            ) );
          } catch ( PDOException $e ) {
            $error [] = $e->getMessage ();
          }
          $this->addFileInformation ();
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
