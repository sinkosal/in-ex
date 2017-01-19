<?php
	require('includes/application_top.php');
	require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);
	require(DIR_WS_INCLUDES . 'template_top.php');
?>
<?php
  if ($messageStack->size('account') > 0) {
    echo $messageStack->output('account');
  }
?>
<div 
	class="contentContainer"
	data-ng-app="main"
>
	<div class="row">
		<div class="col-md-12">
			<div data-ui-view=""></div>
		</div>
	</div>
</div>
<textarea style="display: none;"></textarea>
<?php
require(DIR_WS_INCLUDES . 'template_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

<script>tinymce.init({ selector:'textarea' });</script>
<!-- custom file -->

<script 
	type="text/javascript"
	src="js/ng/app/account/main.js"
></script>
<script 
	type="text/javascript"
	src="js/ng/app/account/config/route.js"
></script>
<script 
	type="text/javascript"
	src="js/ng/app/account/controller/account_ctrl.js"
></script>
<script 
	type="text/javascript"
	src="js/ng/app/account/controller/manage_ctrl.js"
></script>
<script 
	type="text/javascript"
	src="js/ng/app/core/restful/restful.js"
></script>
<script 
	type="text/javascript"
	src="js/ng/app/account/services/services.js"
></script>
<script 
	type="text/javascript"
	src="js/ng/app/account/directive/popup.js"
></script>
<script 
	type="text/javascript"
	src="js/ng/app/account/directive/location.js"
></script>
<script
	type="text/javascript"
	src="js/ng/app/core/directive/number.js"
></script>
<script
	type="text/javascript"
	src="js/ng/app/account/directive/account.js"
></script>
<script
	type="text/javascript"
	src="js/ng/app/account/directive/image.js"
></script>
