<?php
class PaginationResults{
    private $_iPageNumber;
    private $_iPageId;
    private $_iPagCount;
    private $_bPostPageId;

    function __construct($iPageNumber, $iPageId, $iPaginationCount, $bPostPageId){
        $this->_iPageNumber = $iPageNumber;
        $this->_iPageId = $iPageId;
        $this->_iPagCount = $iPaginationCount;
        $this->_bPostPageId = $bPostPageId;
        echo "<div id='pagination_area'>";
        if (($this->_iPagCount > 0) && ($this->_iPagCount <= 7)) {
            //we have 7 or less pages of results:
            $this->_displayPaginationFewResults($this->_iPagCount);

        } else {
            //we have more than 7 pages of results:
            $this->_displayPaginationMoreResults($this->_iPagCount);
        }
        echo '</div>';
    }

    public static function _getPagination($count, $resultsPerPage)
    {
        $paginationCount = floor($count / $resultsPerPage);

        $paginationModCount = $count % $resultsPerPage;
        if (!empty($paginationModCount)) {
            $paginationCount++;
        }

        return $paginationCount;
    }

    private function _displayPaginationFewResults($pagCount){
        echo '<ul class="pagination pagination-sm">';
        //for the prev button:
        //echo $this->_iPageId;
        //die;
        if ($this->_iPageId <= 0) {
            $finalStr = '<li> <a href="javascript:void(0)"> <span class="glyphicon glyphicon-chevron-left"> </span> </a></li>';
            echo $finalStr;
        } else {

            $finalStr = '<li class="';
            $finalStr .= $this->_iPageNumber - 1;
            $finalStr .= '_no">';
            $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
            $finalStr .= "'";
            $finalStr .= $this->_iPageNumber - 1;
            $finalStr .= "','";
            $finalStr .= $this->_iPageNumber - 1;
            $finalStr .= "_no')";
            $finalStr .= '"> <span class="glyphicon glyphicon-chevron-left"> </span> ';
            $finalStr .= '</a></li>';
            echo $finalStr;
        }

        //for ($i = 0; $i < $paginationCount; $i++) {
        for ($i = 0; $i < $pagCount; $i++) {
            $finalStr = '<li class="';
            $finalStr .= $i;
            if ($i == 0 && ($this->_bPostPageId === false)) {
                $finalStr .= '_no active "> ';
            } else {
                $finalStr .= '_no">';
            }

            $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
            $finalStr .= "'";
            $finalStr .= $i;
            $finalStr .= "','";
            $finalStr .= $i;
            $finalStr .= "_no')";
            $finalStr .= '">';
            $finalStr .= $i + 1;
            $finalStr .= '</a></li>';
            echo $finalStr;

        }
        //for the next button:
        if ($this->_iPageNumber == $pagCount - 1) {
            $finalStr = '<li> <a href="javascript:void(0)"> <span class="glyphicon glyphicon-chevron-right"> </span> </a></li>';
            echo $finalStr;
        } else {
            $finalStr = '<li class="';
            $finalStr .= $this->_iPageNumber + 1;
            $finalStr .= '_no">';
            $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
            $finalStr .= "'";
            $finalStr .= $this->_iPageNumber + 1;
            $finalStr .= "','";
            $finalStr .= $this->_iPageNumber + 1;
            $finalStr .= "_no')";
            $finalStr .= '"> <span class="glyphicon glyphicon-chevron-right"> </span>';
            $finalStr .= '</a></li>';
            echo $finalStr;
        }
        echo "</ul>";
        echo '<div class="flash"></div>';

    }

    private function _displayPaginationMoreResults($pagCount){
        echo '<ul class="pagination pagination-sm">';

        /*--------------------------------------------------------------------------------
        the prev button:
        --------------------------------------------------------------------------------*/
        if ($this->_iPageId  <= 0) {
            $finalStr = '<li> <a href="javascript:void(0)"> <span class="glyphicon glyphicon-chevron-left"> </span> </a></li>';
            echo $finalStr;
        } else {

            $finalStr = '<li class="';
            $finalStr .= $this->_iPageNumber - 1;
            $finalStr .= '_no">';
            $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
            $finalStr .= "'";
            $finalStr .= $this->_iPageNumber - 1;
            $finalStr .= "','";
            $finalStr .= $this->_iPageNumber - 1;
            $finalStr .= "_no')";
            $finalStr .= '"> <span class="glyphicon glyphicon-chevron-left"> </span> ';
            $finalStr .= '</a></li>';
            echo $finalStr;
        }

        /*--------------------------------------------------------------------------------
        the first two pages:
        --------------------------------------------------------------------------------*/
        for ($i = 0; $i < 2; $i++) {
            $finalStr = '<li class="';
            $finalStr .= $i;
            if ($i == 0 && ($this->_bPostPageId === false)) {
                $finalStr .= '_no active"> ';
            } else {
                $finalStr .= '_no">';
            }

            $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
            $finalStr .= "'";
            $finalStr .= $i;
            $finalStr .= "','";
            $finalStr .= $i;
            $finalStr .= "_no')";
            $finalStr .= '">';
            $finalStr .= $i + 1;
            $finalStr .= '</a></li>';
            echo $finalStr;
        }


        if (($this->_iPageNumber >= 0) && ($this->_iPageNumber <= 2)) {
            for ($i = 2; $i <= 3; $i++) {
                $finalStr = '<li class="';
                $finalStr .= $i;
                if ($i == 0 && ($this->_bPostPageId === false)) {
                    $finalStr .= '_no active"> ';
                } else {
                    $finalStr .= '_no">';
                }
                $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
                $finalStr .= "'";
                $finalStr .= $i;
                $finalStr .= "','";
                $finalStr .= $i;
                $finalStr .= "_no')";
                $finalStr .= '">';
                $finalStr .= $i + 1;
                $finalStr .= '</a></li>';
                echo $finalStr;
            }
            echo '<li>...</li>';
        } elseif ($this->_iPageNumber == 3) {
            for ($i = 2; $i <= 4; $i++) {
                $finalStr = '<li class="';
                $finalStr .= $i;
                if ($i == 0 && ($this->_bPostPageId === false)) {
                    $finalStr .= '_no active"> ';
                } else {
                    $finalStr .= '_no">';
                }
                $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
                $finalStr .= "'";
                $finalStr .= $i;
                $finalStr .= "','";
                $finalStr .= $i;
                $finalStr .= "_no')";
                $finalStr .= '">';
                $finalStr .= $i + 1;
                $finalStr .= '</a></li>';
                echo $finalStr;
            }
            echo '<li>...</li>';
        } elseif ($this->_iPageNumber == 4) {
            for ($i = 2; $i <= 5; $i++) {
                $finalStr = '<li class="';
                $finalStr .= $i;
                if ($i == 0 && ($this->_bPostPageId === false)) {
                    $finalStr .= '_no active"> ';
                } else {
                    $finalStr .= '_no">';
                }
                $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
                $finalStr .= "'";
                $finalStr .= $i;
                $finalStr .= "','";
                $finalStr .= $i;
                $finalStr .= "_no')";
                $finalStr .= '">';
                $finalStr .= $i + 1;
                $finalStr .= '</a></li>';
                echo $finalStr;
            }
            echo '<li>...</li>';
        } elseif (($this->_iPageNumber >= 5) && ($this->_iPageNumber < $pagCount - 4)) {
            echo '<li>...</li>';

            for ($i = $this->_iPageNumber - 2; $i < $this->_iPageNumber + 3; $i++) {
                //for($i=0;$i<$paginationCount;$i++){
                //for ($i = 0; $i < 5; $i++) {
                $finalStr = '<li class="';
                $finalStr .= $i;
                if ($i == 0 && ($this->_bPostPageId === false)) {
                    $finalStr .= '_no active"> ';
                } else {
                    $finalStr .= '_no">';
                }
                $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
                $finalStr .= "'";
                $finalStr .= $i;
                $finalStr .= "','";
                $finalStr .= $i;
                $finalStr .= "_no')";
                $finalStr .= '">';
                $finalStr .= $i + 1;
                $finalStr .= '</a></li>';
                echo $finalStr;

            }
            echo '<li>...</li>';
        } elseif ($this->_iPageNumber >= $pagCount - 4) {
            echo '<li>...</li>';
            for ($i = $this->_iPageNumber - 6; $i <= $pagCount - 3; $i++) {
                /*
                echo $i."<br/>";
                echo $paginationCount-3;
                echo"<br/><br/>";
                */
                $finalStr = '<li class="';
                $finalStr .= $i;
                if ($i == 0 && ($this->_bPostPageId === false)) {
                    $finalStr .= '_no active"> ';
                } else {
                    $finalStr .= '_no">';
                }
                $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
                $finalStr .= "'";
                $finalStr .= $i;
                $finalStr .= "','";
                $finalStr .= $i;
                $finalStr .= "_no')";
                $finalStr .= '">';
                $finalStr .= $i + 1;
                $finalStr .= '</a></li>';
                echo $finalStr;
            }

        }

        /*--------------------------------------------------------------------------------
        the last two pages:
        --------------------------------------------------------------------------------*/
        $finalStr = '<li class="';
        $finalStr .= $pagCount - 2;
        if ($i == 0 && ($this->_bPostPageId === false)) {
            $finalStr .= '_no active"> ';
        } else {
            $finalStr .= '_no">';
        }
        $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
        $finalStr .= "'";
        $finalStr .= $pagCount - 2;
        $finalStr .= "','";
        $finalStr .= $pagCount - 2;
        $finalStr .= "_no')";
        $finalStr .= '">';
        $finalStr .= $pagCount - 1;
        $finalStr .= '</a></li>';
        echo $finalStr;

        $finalStr = '<li class="';
        $finalStr .= $pagCount - 1;
        $finalStr .= '_no">';
        $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
        $finalStr .= "'";
        $finalStr .= $pagCount - 1;
        $finalStr .= "','";
        $finalStr .= $pagCount - 1;
        $finalStr .= "_no')";
        $finalStr .= '">';
        $finalStr .= $pagCount;
        $finalStr .= '</a></li>';
        echo $finalStr;


        /*--------------------------------------------------------------------------------
        the next button:
        --------------------------------------------------------------------------------*/
        if ($this->_iPageNumber == $pagCount - 1) {
            $finalStr = '<li class="link"> <a href="javascript:void(0)"> <span class="glyphicon glyphicon-chevron-right"> </span> </a></li>';
            echo $finalStr;
        } else {
            $finalStr = '<li class="';
            $finalStr .= $this->_iPageNumber + 1;
            $finalStr .= '_no">';
            $finalStr .= '<a href="javascript:void(0)" onclick="changePagination(';
            $finalStr .= "'";
            $finalStr .= $this->_iPageNumber + 1;
            $finalStr .= "','";
            $finalStr .= $this->_iPageNumber + 1;
            $finalStr .= "_no')";
            $finalStr .= '"> <span class="glyphicon glyphicon-chevron-right"> </span>';
            $finalStr .= '</a></li>';
            echo $finalStr;
        }
        echo "</ul>";
        echo '<div class="flash"></div>';
    }
}