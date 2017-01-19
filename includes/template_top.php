<?php
if (!tep_session_is_registered('id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-se=1cal">
<title>Revenue & Exspend Management System</title>
    <link rel="shortcut icon" href="images/icon.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/lib_template/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/date-time-picker/angular-datepicker.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.css">
<!--    <link rel="stylesheet" type="text/css" href="css/lib_template/animate.min.css">-->
    <link rel="stylesheet" type="text/css" href="css/lib_template/bootstrap-switch.min.css">
    <!-- CSS App -->
<!--    <link rel="stylesheet" type="text/css" href="css/lib_template/jquery-ui.min.css">-->
    <link rel="stylesheet" type="text/css" href="css/lib_template/style.css">
    <link rel="stylesheet" type="text/css" href="css/lib_template/select.css">
    <link rel="stylesheet" type="text/css" href="css/lib_template/flat-blue.css">
    <link rel="stylesheet" type="text/css" href="css/lib_template/select2.min.css">
    <!-- Select2 theme -->
    <link rel="stylesheet" href="css/lib_template/select2.css">
    <link rel="stylesheet" type="text/css" href="css/lib_template/selectize.default.css">
<!--    <link rel="stylesheet" type="text/css" href="css/date-time-picker/helper.css">-->
    <link rel="stylesheet" type="text/css" href="css/print_table.css">
</head>
<?php
//    $queryBirthday = tep_db_query("select count(*) from tab_customer where DAY(dob) = DAY(NOW()) AND MONTH(dob) = MONTH(NOW())");
//    $countBirthDay = tep_db_fetch_array($queryBirthday);
//?>
<body class="flat-blue">
<div class="app-container">
    <div class="row content-container">
        <nav class="navbar navbar-default navbar-fixed-top navbar-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-expand-toggle">
                        <i class="fa fa-bars icon"></i>
                    </button>
                    <ol class="breadcrumb navbar-breadcrumb">
                        <li class="active">Revenue & Expend Management System</li>
                    </ol>
                    <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                        <i class="fa fa-th icon"></i>
                    </button>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                        <i class="fa fa-times icon"></i>
                    </button>
                    <li class="dropdown danger">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-birthday-cake"></i>
                            <?php echo $countBirthDay['count(*)'];?>
                        </a>
                        <ul class="dropdown-menu danger  animated fadeInDown">
                            <li class="title">
                                Notification <span class="badge pull-right"><?php echo $countBirthDay['count(*)'];?></span>
                            </li>
                            <li>
                                <ul class="list-group notifications">
                                    <a href="#/customer_birthday">
                                        <li class="list-group-item">
                                            <span class="badge"><?php echo $countBirthDay['count(*)'];?></span>
                                            <i class="fa fa-birthday-cake"></i>
                                            Customer Birthday
                                        </li>
                                    </a>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown profile">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span id="user">
                                <?php
                                    echo $_SESSION['user_name'];
                                ?>
                            </span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu animated fadeInDown">
                            <li>
                                <div class="profile-info">
                                    <h4 class="username">
                                        <?php
                                            echo $_SESSION['user_name'];
                                        ?>
                                    </h4>
                                    <p>
                                        <?php
                                            echo $_SESSION['customer_email'];
                                        ?>
                                    </p>
                                    <div class="btn-group margin-bottom-2x" role="group">
                                        <a href="#/setting">
                                            <button type="button" class="btn btn-success">
                                                <i class="fa fa-user"></i>
                                                Profile
                                            </button>
                                        </a>
                                        <a href="logoff.php">
                                            <button type="button" class="btn btn-primary">
                                                <i class="fa fa-sign-out"></i> Logout
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="side-menu sidebar-inverse">
            <nav class="navbar navbar-default" role="navigation">
                <div class="side-menu-container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <div class="icon fa fa-signal"></div>
                            <div class="title">Revenue & Expend</div>
                        </a>
                        <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                            <i class="fa fa-times icon"></i>
                        </button>
                    </div>
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="#">
                                <span class="icon fa fa-tachometer"></span><span class="title">Dashboard</span>
                            </a>
                        </li>
                        <li class="panel panel-default dropdown">
                            <a data-toggle="collapse" href="#dropdown-element">
                                <span class="icon fa fa-desktop"></span><span class="title">Data Setup</span>
                            </a>
                            <!-- Dropdown level 1 -->
                            <div id="dropdown-element" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul class="nav navbar-nav">
                                        <li><a href="#/customer_type">Setup Expend</a></li>
                                        <li><a href="#/product_type">Setup Revenue</a></li>
                                        <li><a href="#/doctor_type">Setup Exchange Rate( Baht To Dollar)</a></li>
                                        <li><a href="#/account_type">Setup Exchange Rate( Riel To Dollar )</a></li>
<!--                                        <li><a href="#/customer_balance">Customer Balance Report</a></li>-->
<!--                                        <li><a href="#/received_payment">Customer Payment</a></li>-->
<!--                                        <li><a href="#/account_receivable_summary">Customer Payment Report (A/R)</a></li>-->
<!--                                        --><?php //
//                                            if($_SESSION['role'] == 'Admin'){
//                                                echo '<li><a href="#/service">Setup Service</a></li>';
//                                            }
////                                        ?>
<!--                                        <li><a href="#/appointment">Appointment</a></li>-->
<!--                                        <li><a href="#/appointment_report">Appointment Report</a></li>-->
<!--                                        <li><a href="#/create_invoice">Issue Invoice</a></li>-->
<!--                                        <li><a href="#/report_invoice">Issue Invoice Report</a></li>-->
<!--                                        <li><a href="#/daily_case_report">Daily Case Report</a></li>-->
<!--                                        <li><a href="#/account_payable">Account Payable Report</a></li>-->
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <?php 
                            if($_SESSION['role'] == 'Admin'){
                        ?>  
                        <li class="panel panel-default dropdown">
                            <a data-toggle="collapse" href="#dropdown-table">
                                <span class="icon fa fa-table"></span><span class="title">Post Data</span>
                            </a>
                            <!-- Dropdown level 1 -->
                            <div id="dropdown-table" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul class="nav navbar-nav">
<!--                                        <li><a href="#/staff_type">Staff Type</a></li>-->
                                        <li><a href="#/staff_list">Post Revenue</a></li>
                                        <li><a href="#/staff_payroll">Post Expense</a></li>
<!--                                        <li><a href="#/staff_report">Staff Report</a></li>-->
<!--                                        <li><a href="#/payroll_report">Payroll Report</a></li>-->
                                    </ul>
                                </div>
                            </div>
                        </li>
                                <li class="panel panel-default dropdown">
                                    <a data-toggle="collapse" href="#dropdown-icon">
                                        <span class="icon fa fa-archive"></span><span class="title">Report</span>
                                    </a>
                                    <!-- Dropdown level 1 -->
                                    <div id="dropdown-icon" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul class="nav navbar-nav">
                                                <li><a href="#/account_type">Expend Report</a></li>
                                                <li><a href="#/chart_account">Revenue Report</a></li>
                                                <li><a href="#/journal_entry">Income Statement</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="panel panel-default dropdown">
                                    <a data-toggle="collapse" href="#dropdown-user">
                                        <span class="icon fa fa-user"></span><span class="title">User</span>
                                    </a>
                                    <!-- Dropdown level 1 -->
                                    <div id="dropdown-user" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul class="nav navbar-nav">
                                                <li><a href="#/user">List User</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>


<!--                        <li class="panel panel-default dropdown">-->
<!--                            <a data-toggle="collapse" href="#dropdown-doctor">-->
<!--                                <span class="icon fa fa-folder"></span><span class="title">Setup Doctor</span>-->
<!--                            </a>-->
<!--                            <!-- Dropdown level 1 -->-->
<!--                            <div id="dropdown-doctor" class="panel-collapse collapse">-->
<!--                                <div class="panel-body">-->
<!--                                    <ul class="nav navbar-nav">-->
<!--                                        <li><a href="#/doctor_type">Doctor Type</a></li>-->
<!--                                        <li><a href="#/doctor_list">Doctor List</a></li>-->
<!--                                        <li><a href="#/doctor_expense">Doctor Expense</a></li>-->
<!--                                        <li><a href="#/doctor_report">Doctor Report</a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <li class="panel panel-default dropdown">-->
<!--                            <a data-toggle="collapse" href="#dropdown-form">-->
<!--                                <span class="icon fa fa-file-text-o"></span><span class="title">Setup Vendor</span>-->
<!--                            </a>-->
<!--                            <!-- Dropdown level 1 -->-->
<!--                            <div id="dropdown-form" class="panel-collapse collapse">-->
<!--                                <div class="panel-body">-->
<!--                                    <ul class="nav navbar-nav">-->
<!--                                        <li><a href="#/vendor_type">Vendor Type</a></li>-->
<!--                                        <li><a href="#/vendor_list">Vendor List</a></li>-->
<!--                                        <li><a href="#/pay_bill">Vendor Payment(A/P)</a></li>-->
<!--                                        <li><a href="#/account_payable_summary">Vendor Payment Report</a></li>-->
<!--                                        <li><a href="#/vendor_balance">Vendor Balance Report</a></li>-->
<!--<!--                                        <li><a href="#/purchase_order">Purchase Order</a></li>-->-->
<!--                                        <li><a href="#/purchase">Purchase</a></li>-->
<!--                                        <li><a href="#/report_purchase">Purchase Detail Report</a></li>-->
<!--                                        <li><a href="#/report_purchase_summary">Purchase Summary Report</a></li>-->
<!--<!--                                        <li><a href="#/account_payable">Account Payable Report</a></li>-->-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <!-- Dropdown-->-->
<!--                        <li class="panel panel-default dropdown">-->
<!--                            <a data-toggle="collapse" href="#component-example">-->
<!--                                <span class="icon fa fa-cubes"></span><span class="title">Setup Item</span>-->
<!--                            </a>-->
<!--                            <!-- Dropdown level 1 -->-->
<!--                            <div id="component-example" class="panel-collapse collapse">-->
<!--                                <div class="panel-body">-->
<!--                                    <ul class="nav navbar-nav">-->
<!--                                        <li><a href="#/product_type">Product Type</a></li>-->
<!--                                        <li><a href="#/product_list">Product List</a></li>-->
<!--                                        <li><a href="#/stock_out">Stock Out</a></li>-->
<!--                                        <li><a href="#/stock_report">Stock Report</a></li>-->
<!--                                        <li><a href="#/report_sale">Stock Out Detail Report</a></li>-->
<!--                                        <li><a href="#/report_sale_summary">Stock Out Summary Report</a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <!-- Dropdown-->-->
<!--                        <li class="panel panel-default dropdown">-->
<!--                            <a data-toggle="collapse" href="#dropdown-example">-->
<!--                                <span class="icon fa fa-slack"></span><span class="title">Report</span>-->
<!--                            </a>-->
<!--                            <!-- Dropdown level 1 -->-->
<!--                            <div id="dropdown-example" class="panel-collapse collapse">-->
<!--                                <div class="panel-body">-->
<!--                                    <ul class="nav navbar-nav">-->
<!--                                        <li><a href="#/journal_report">Journal</a></li>-->
<!--                                        <li><a href="#/account_payable">Account Payable</a></li>-->
<!--                                        <li><a href="#/ledger_report">General Ledger</a></li>-->
<!--                                        <li><a href="#/cash_flow">Cash Flow</a></li>-->
<!--                                        <li><a href="#/income_statement">Income Statement</a></li>-->
<!--                                        <li><a href="#/balance_sheet">Balance Sheet</a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <!-- Dropdown-->-->

                        <?php 
                            }
                        ?>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </nav>
        </div>
        <!-- Main Content -->
        <div class="container-fluid">
            <div class="side-body padding-top">