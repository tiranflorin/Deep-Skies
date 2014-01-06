<div class="row container blue-bg" id="showObserverSettings">
    <div class="row ">
        <div class="col-lg-10">
            <h2 class="text-left">Current observer settings
                <small id="smallObserverSettings">(<?php echo $currentSettings; ?>)</small>
            </h2>
        </div>
        <div class="col-lg-2">
            <button type="button" class="btn btn-success pull-right" id="changeSettings" style="margin-top:15px;margin-bottom: 20px;">
                <span class="glyphicon glyphicon-cog"></span> Settings
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-11" id="detailedObserverSettings">
            <?php
            foreach ($aSettings as $val) {
                echo $val;
            }
            ?>
        </div>
    </div>
</div>