<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    if ( !isset($HTTP_GET_VARS['cookie_test']) ) {
      $all_get = tep_get_all_get_params();

      tep_redirect(tep_href_link(FILENAME_LOGIN, $all_get . (empty($all_get) ? '' : '&') . 'cookie_test=1', 'SSL'));
    }

    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }
if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $username = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
    $check_query = tep_db_query("
      select
          id,
          user_name,
          user_password,
          role,
          status
      from
        " . TABLE_ADMINISTRATORS . "
      where
        user_name = '" . tep_db_input($username) . "'
            and
        status = 1
    ");
    if (tep_db_num_rows($check_query) == 1) {
        $check = tep_db_fetch_array($check_query);
        if (tep_validate_password($password, $check['user_password'])) {
            $user_name = $check['user_name'];
            $role = $check['role'];
            $id = $check['id'];
            tep_session_register('user_name');
            tep_session_register('id');
            tep_session_register('role');
            tep_redirect(tep_href_link('#/'));
        }
    }else {
        $messageStack->add('login', 'Error: Invalid administrator login attempt.');
    }
}
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));

//  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clinic Management System</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/login/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login/assets/css/form-elements.css">
    <link rel="stylesheet" href="css/login/assets/css/style.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="images/icon.jpg">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="css/login/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="css/login/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="css/login/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="css/login/assets/ico/apple-touch-icon-57-precomposed.png">

</head>

<body>
<!-- Top content -->
<div class="top-content">
    <div style="width: auto; min-height: 150px; background-color: rgba(26, 0, 170, 0.17);"></div>
    <div class="inner-b  g">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="form-top">
                        <div class="form-top-left">
                            <h3 style="color: #333a99">
                                <b>International Dental Clinic</b>
                            </h3>
                            <p>Enter your username and password to log on:</p>
                        </div>
                        <div class="form-top-right">
                            <i class="fa fa-lock" style="color: rgba(0, 0, 255, 0.54)"></i>
                        </div>
                        <div class="clearfix"></div>
                        <?php
                            if ($messageStack->size('login') > 0) {
                                echo $messageStack->output('login');
                            }
                        ?>
                    </div>
                    <div class="form-bottom">

                            <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', 'class="login-form"', true); ?>
                                <div class="form-group">
                                    <label class="sr-only" for="form-username">Email Address</label>
                                    <input
                                        type="text"
                                        required
                                        name="email_address"
                                        placeholder="User Name..."
                                        class="form-username form-control"
                                        id="inputEmail"
                                        >
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="form-password">Password</label>
                                    <input
                                        type="password"
                                        name="password"
                                        placeholder="Password..."
                                        class="form-password form-control"
                                        id="inputPassword"
                                        required
                                        >
                                </div>
                                <button type="submit" class="btn btn-success">Log In!</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="width: auto; min-height: 150px; background-color: rgba(26, 0, 170, 0.17);"></div>

</div>


<!-- Javascript -->
<script src="css/login/assets/js/jquery-1.11.1.min.js"></script>
<script src="css/login/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="css/login/assets/js/jquery.backstretch.min.js"></script>
<script src="css/login/assets/js/scripts.js"></script>

<!--[if lt IE 10]>
<script src="css/login/assets/js/placeholder.js"></script>
<![endif]-->

</body>

</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
