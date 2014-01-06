<?php
require_once('RetrieveObjectsBase.php');
class RetrieveObjects
{
    private $_minSelectedMagnitude;
    private $_maxSelectedMagnitude;
    private $_aSelectedObjTypes;
    private $_aSelectedObjConst;

    public function __construct(){
        $tmpObjMag = $_POST['selectedObjectMagnitude'];
        $this->_minSelectedMagnitude = $tmpObjMag[0];
        $this->_maxSelectedMagnitude = $tmpObjMag[1];

        $postValues = $_POST['selectedObjectTypesAndConst'];
        $aTemp = explode('&',$postValues);

        $aSelectedObjConst = array();
        $aSelectedObjTypes = array();
        for($i=0;$i<count($aTemp);$i++){
            //if the string "objectConstellation" is found, build the obj constellation array, else build the obj type array.
            if(strpos($aTemp[$i],'objectConstellation') !== false){
                if($aTemp[$i] == 'objectConstellation=allvisible' ){
                    $aSelectedObjConst[] = substr($aTemp[$i],-10);
                }
                else{
                    $aSelectedObjConst[] = substr($aTemp[$i],-3);
                }
            }else{
                $aSelectedObjTypes[] = substr($aTemp[$i],-5);
            }
        }
        //var_dump($aSelectedObjConst);
        //var_dump($aSelectedObjTypes);
        $this->_aSelectedObjConst = $aSelectedObjConst;
        $this->_aSelectedObjTypes = $aSelectedObjTypes;
        $this->_getVisibleObjects();
    }

    private function _getVisibleObjects()
    {
        $sWhereCondition1 = "AND `source`.`type` IN('";
        $sWhereCondition1 .= implode(',', $this->_aSelectedObjTypes);
        $sWhereCondition1 = str_replace(',', "','", $sWhereCondition1);
        $sWhereCondition1 .= "') ";
        if ($this->_aSelectedObjConst[0] == 'allvisible') {
            $sWhereCondition2 = "";
        } else {
            $sWhereCondition2 = "AND `source`.`constellation` IN('";
            $sWhereCondition2 .= implode(',', $this->_aSelectedObjConst);
            $sWhereCondition2 = str_replace(',', "','", $sWhereCondition2);
            $sWhereCondition2 .= "') ";
        }
        $sWhereCondition3 = "AND `source`.`mag` BETWEEN {$this->_minSelectedMagnitude} AND {$this->_maxSelectedMagnitude} ";

        if (isset($_POST['pageId'])) {
            $iPageId = $_POST['pageId'];
            $iPageNumber = $iPageId;
            $bPage = true;
        } else {
            $iPageId = 0;
            $iPageNumber = 0;
            $bPage = false;
        }

        $obj = new RetrieveObjectsBase();
        $obj->_getVisibleObjectsBase($sWhereCondition1, $sWhereCondition2, $sWhereCondition3, $iPageId, $iPageNumber, $bPage);
    }

}

$obj1 = new RetrieveObjects();