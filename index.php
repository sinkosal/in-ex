<?php
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
require(DIR_WS_INCLUDES . 'template_top.php');
?>
<div class="contentContainer" data-ng-app="main" data-ng-controller="main_ctrl">
    <div class="row">
        <div class="col-md-12" id="role" data-role="<?php echo $_SESSION['role'];?>">
            <div data-ui-view=""></div>
        </div>
    </div>
</div>
<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

