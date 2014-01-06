<?php
require_once('RetrieveObjectsBase.php');

class RetrieveObjectsPredefined
{
    private $_sPredefinedFilters;

    public function __construct()
    {

        $this->_sPredefinedFilters = $_POST['predefinedFilters'];

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

        switch ($this->_sPredefinedFilters) {
          case 'naked_eye':
              $obj->_getVisibleObjectsBase(' AND `source`.`mag` BETWEEN 0 AND 6.9 ', ' AND 1 ', ' AND 1 ', $iPageId, $iPageNumber, $bPage);
              break;
          case 'binoculars':
              $obj->_getVisibleObjectsBase(' AND `source`.`mag` BETWEEN 4 AND 8.5 ', ' AND 1 ', ' AND 1 ', $iPageId, $iPageNumber, $bPage);
              break;
          case 'small_telescope':
              $obj->_getVisibleObjectsBase(' AND `source`.`mag` BETWEEN 8.6 AND 11 ', ' AND 1 ', ' AND 1 ', $iPageId, $iPageNumber, $bPage);
              break;
        }
    }
}
$obj1 = new RetrieveObjectsPredefined();