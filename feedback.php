<?php
session_start();
$sPageTitle = "Feedback | Deep-Skies";
require_once('php/DbPdo.php');
if (isset($_POST['submit'])) {
    $feedbackArea = "
        <div class='col-lg-8'>
          <h2>Your feedback has been saved.</h2>
          <h3 class='text-right'>Thank you!</h3>
          <hr>
        </div>
    ";

    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        $name = "casda";
    }
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = "veere";
    }
    if (!empty($_POST['message'])) {
        $message = $_POST['message'];
    } else {
        $message = "ceva";
    }

    //$email = "";
    //$message = "";
    $creation = date("Y-m-d H:i:s");
    $sSql = "
    INSERT INTO `dso`.`feedback`(
    `name`,
    `email`,
    `message`,
    `creation`
    )
    VALUES(
    '{$name}',
    '{$email}',
    '{$message}',
    '{$creation}'
    )
    ";

    $iResult = $dbh->exec($sSql);

} else{
    $feedbackArea = "
        <div class='col-lg-8'>
          <form role='form' action='/projects/deep-skies/feedback.php' method='post'>
            <div class='form-group'>
              <label for='name'>Name:</label>
              <input type='text' class='form-control' name='name' id='name' placeholder='Enter name'>
            </div>
            <div class='form-group'>
              <label for='exampleInputEmail1'>Email address:</label>
              <input type='email' class='form-control' name='email' id='exampleInputEmail1' placeholder='Enter email'>
            </div>
            <div class='form-group'>
              <label for='comments'>Message:</label>
              <textarea class='form-control' rows='6' id='comments' name='message' placeholder='Enter message'></textarea>
            </div>
            <input type='submit' name='submit' class='btn btn-primary btn-lg' value='Send Message'>
          </form>
          <hr>
        </div>
  ";
}
  require_once('php/views/header.php');
?>


<body>

<div id="wrap">

    <?php
    require_once('php/views/horizontalNavigation.php');
    require_once('php/views/feedback/jumbotron.php');
    ?>


    <div class="container">
        <div class="row">
            <div class="col-lg-2">
            </div>
            <?php echo $feedbackArea; ?>
            <div class="col-lg-2">
            </div>
        </div>
    </div>
    <!-- /container -->

</div>
<!-- /wrap -->

<?php
require_once('php/views/footer.php');
?>