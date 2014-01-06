<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/projects/deep-skies/">Deep-Skies</a>
        </div>
        <div class="navbar-collapse collapse">
            <form class="navbar-form navbar-left" action="search-results.php" method="post">
                <div class="form-group">
                    <input type="text" name="keywords" placeholder="Quick dso search" class="form-control" id="search1">
                </div>

                <button type="submit" name="quickSearch" class="btn btn-success" value="true">Go</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <!--
                <li class="active"><a href="#">Switch to history</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li class="dropdown-header">Nav header</li>
                    <li><a href="#">Separated link</a></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                </li> -->
                <li<?php if($sPageTitle == "Deep-Skies Home"){echo ' class="active"';}  ?>><a href="/projects/deep-skies/">Home</a></li>
                <li<?php if($sPageTitle == "NGC Abbreviations | Deep-Skies"){echo ' class="active"';}  ?>><a href="ngc-description.php">NGC Abbreviations</a></li>
                <li<?php if($sPageTitle == "Feedback | Deep-Skies"){echo ' class="active"';}  ?>><a href="feedback.php">Feedback</a></li>


            </ul>

        </div><!--/.navbar-collapse -->
    </div>
</div>