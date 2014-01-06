<div class="row blue-bg" style="margin-bottom: 30px;">
    <form class="form-horizontal" role="form" id="saveFiltersFormId">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <h3>Constellation</h3>
            <select name="objectConstellation" multiple="multiple" class="form-control" style="height:200px; margin-bottom: 15px;">
                <option value="allvisible" selected="selected">All Visible</option>
                <?php
                foreach($constellations as $const){
                    echo '<option value="'.$const.'">'.$const.'</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="row">
            <div class="col-xs-12 col-sm-6 col-lg-12">
                <h3>Object type:</h3>
                <select name="objectType" multiple="multiple" style="height:97px;" class="form-control">
                    <option value="galxy" selected="selected">Galaxy</option>
                    <option value="plnnb">Planetary Nebula</option>
                    <option value="glocl">Globular Cluster</option>
                    <option value="opncl">Open Cluster</option>
                    <option value="other">Nebulae</option>
                </select>
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-12">
                <h3>Magnitude</h3>
                <div id="slider-range" style="margin-top:10px;"></div>
                <input type="text" id="amount" style="border: 0; color: #f6931f; font-weight: bold;" />
            </div>
            </div>
        </div>


        <div class="col-xs-12 col-xs-pull-6 col-sm-6 col-md-12 col-lg-1">
            <div class="row">

            <div class="col-xs-12 col-sm-6 col-lg-12">
                <h3 style="visibility: hidden;">Filter</h3>
                <div class="form-horizontal">
                    <button type="submit" class="btn btn-success pull-right" id="saveFilters">Filter</button>
                </div>
            </div>
            </div>
        </div>
    </form>

    <div class="col-xs-12 col-sm-12 col-lg-5">
        <h3>Predefined Filters:</h3>
        <form class="form-horizontal" role="form" id="savePredefinedFiltersFormId">
            <div class="row">
                <div id="predefinedFilters">
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <input type="radio" id="radio1" name="predefinedFilters" value="naked_eye" >
                        <label for="radio1">naked eye</label>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <input type="radio" id="radio2" name="predefinedFilters" value="binoculars">
                        <label for="radio2">binoculars</label>
                    </div>
                    <div class="col-xs-12 col-sm-5 col-lg-5">
                        <input type="radio" id="radio3" name="predefinedFilters" value="small_telescope">
                        <label for="radio3">small telescope</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>