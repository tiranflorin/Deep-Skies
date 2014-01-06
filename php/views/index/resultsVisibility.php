<div id="results-visibility">
    <div class="row">
        <div class="col-lg-6">
            <h2>Results:</h2>
        </div>

        <div class="col-lg-3">
            <h2>Display type:</h2>
        </div>
        <div class="col-lg-3 ">
            <div class="list-group">
                <a href="#" id="displayList" class="list-group-item myList active">
                    <span class="glyphicon glyphicon-th-list "></span> List
                </a>
                <a href="#" id="displayGrid" class="list-group-item myGrid">
                    <span class="glyphicon glyphicon-th "></span> Grid</a>
            </div>
        </div>
    </div>


    <div class="row" id="links">
        <div class="col-lg-9" id="displayResults">
        </div>

        <div class="col-lg-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Export results to:</h3>
                </div>
                <div class="panel-body">

                    <form role="form" method="post" action="php/ExportToExcel.php">
                        <p><input type="submit" class="btn btn-sm btn-success" name="exportXls" value="Export to XLS"></p>
                    </form>

                    <form role="form" method="post" action="php/ExportToPdf.php">
                        <p><input type="submit" class="btn btn-sm btn-success" name="exportPdf" value="Export to PDF"></p>
                    </form>

                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Resources used:</h3>
                </div>
                <div class="panel-body">


                    <p> DSO datails:</p>
                    <p><a href="http://www.saguaroastro.org" target="_blank">Saguaro Astronomy Club</a></p>

                    <p> DSO image credits:</p>
                    <p><a href="http://stdatu.stsci.edu/cgi-bin/dss_form" target="_blank">The STScI Digitized Sky Survey</a></p>

                </div>
            </div>
        </div>
    </div>
</div><!-- /results-visibility -->