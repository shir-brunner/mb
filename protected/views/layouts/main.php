<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="en" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

		<?php
			$user = Yii::app()->user->getState('user') ? Yii::app()->user->getState('user') : new User();
			$baseUrl = Yii::app()->request->getBaseUrl();
			$clientScript = Yii::app()->clientScript;

			$cssVersion = Yii::app()->params['cssVersion'];
			//external css libraries
			$clientScript->registerCssFile($baseUrl . '/js/jquery-ui/jquery-ui.css');
			$clientScript->registerCssFile($baseUrl . '/css/normalize.css');
			$clientScript->registerCssFile($baseUrl . '/css/chosen.css');
			$clientScript->registerCssFile($baseUrl . '/css/toastr.min.css');
			$clientScript->registerCssFile($baseUrl . '/bootstrap/css/bootstrap.min.css');
			$clientScript->registerCssFile($baseUrl . '/css/jasny-bootstrap.min.css');
			$clientScript->registerCssFile($baseUrl . '/css/font-awesome.min.css');
			$clientScript->registerCssFile($baseUrl . '/css/custom.css?version=' . $cssVersion);
			$clientScript->registerCssFile($baseUrl . '/css/animate.css');
			$clientScript->registerCssFile($baseUrl . '/css/style.css?version=' . $cssVersion);
			$clientScript->registerCssFile($baseUrl . '/css/datatables.min.css');
			/*$clientScript->registerCssFile($baseUrl . '/css/summernote.css');*/
			$clientScript->registerCssFile($baseUrl . '/css/awesome-bootstrap-checkbox.css');
			$clientScript->registerCssFile($baseUrl . '/css/datepicker3.css');
			$clientScript->registerCssFile($baseUrl . '/css/clockpicker.css');
			$clientScript->registerCssFile($baseUrl . '/css/select2.min.css');
			$clientScript->registerCssFile($baseUrl . '/css/ion.rangeSlider.css');
			$clientScript->registerCssFile($baseUrl . '/css/ion.rangeSlider.skinFlat.css');
			$clientScript->registerCssFile($baseUrl . '/css/bootstrap-tour.min.css');
			$clientScript->registerCssFile($baseUrl . '/css/sweetalert.css');
			$clientScript->registerCssFile($baseUrl . '/css/cropper.min.css');

			$jsVersion = Yii::app()->params['jsVersion'];

			//external javascript libraries
			$clientScript->registerCoreScript('jquery');
			$clientScript->registerScriptFile($baseUrl . '/js/jquery-ui/jquery-ui.min.js');
			$clientScript->registerScriptFile($baseUrl . '/bootstrap/js/bootstrap.js');
			$clientScript->registerScriptFile($baseUrl . '/js/jasny-bootstrap.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/jquery.metisMenu.js');
			$clientScript->registerScriptFile($baseUrl . '/js/jquery.slimscroll.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/inspinia.js');
			$clientScript->registerScriptFile($baseUrl . '/js/pace.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/datatables.min.js');
			/*$clientScript->registerScriptFile($baseUrl . '/js/summernote.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/summernote-ext-print.js');
			$clientScript->registerScriptFile($baseUrl . '/js/summernote-ext-rtl.js');*/
			$clientScript->registerScriptFile($baseUrl . '/js/icheck.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/bootstrap-datepicker.js');
			$clientScript->registerScriptFile($baseUrl . '/js/clockpicker.js?version=' . $jsVersion);
			$clientScript->registerScriptFile($baseUrl . '/js/toastr.js');
			$clientScript->registerScriptFile($baseUrl . '/js/chosen.jquery.js');
			$clientScript->registerScriptFile($baseUrl . '/js/select2.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/ion.rangeSlider.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/jquery.dotdotdot.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/sweetalert.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/pluralize.js');
			$clientScript->registerScriptFile($baseUrl . '/js/jquery.print.js');
			$clientScript->registerScriptFile($baseUrl . '/js/dropzone.js');
			$clientScript->registerScriptFile($baseUrl . '/js/jquery.cookie.js?version=' . $jsVersion);
			$clientScript->registerScriptFile($baseUrl . '/js/clipboard.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/cropper.min.js');
			$clientScript->registerScriptFile($baseUrl . '/js/tinymce/tinymce.min.js');

			//mb scripts
			$clientScript->registerScriptFile($baseUrl . '/js/mb/config.js?version=' . $jsVersion);
			$clientScript->registerScriptFile($baseUrl . '/js/mb/core.js?version=' . $jsVersion);
			$clientScript->registerScriptFile($baseUrl . '/js/mb/upload.js?version=' . $jsVersion);

			//mb css files
			$clientScript->registerCssFile($baseUrl . '/css/mb/main.css?version=' . $cssVersion);
		?>

		<link rel="icon" href="<?php echo $baseUrl; ?>/website/img/favicon_green.png" />

		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>

	<body class="pace-done">

		<div id="wrapper">
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav metismenu" id="side-menu">

						<li class="nav-header">
							<div class="dropdown profile-element">
								<a href="#">
									<span><img alt="image" class="img-circle" id="menu-profile-picture" src="<?php echo $baseUrl; ?>/images/shir.jpg"></span>
									<span class="clear">
										<span class="block m-t-xs">
											<strong class="font-bold menu-user-name"><?php echo ucwords($user->getFullName()); ?></strong>
										</span>
									</span>
								</a>
							</div>
						</li>

						<?php $this->renderPartial('//layouts/_menu'); ?>
					</ul>
				</div>
			</nav>

			<div id="page-wrapper" class="gray-bg dashbard-1">
				<div class="row border-bottom">
					<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0;">

						<div class="navbar-header">
							<a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i></a>
							<div role="search" class="navbar-form-custom relative">
								<div class="form-group">
									<input type="text" placeholder="Search..." autocomplete="off" class="form-control" id="top-search" />
								</div>
								<div id="top-search-results" class="collapsable"></div>
							</div>
						</div>

						<ul class="nav navbar-top-links navbar-right text-right">
							<li class="hidden-xs hidden-sm hidden">
								<a class="support-button">
									<i class="fa fa-support text-danger" data-placement="bottom" tooltip title="Support"></i>
								</a>
							</li>

							<li class="hidden-xs">
								<a class="count-info" href="<?php echo Yii::app()->createUrl('user/settings'); ?>">
									<i class="fa fa-user" data-placement="bottom" tooltip title="Account Settings"></i>
								</a>
							</li>

							<li>
								<a class="count-info inbox-button" href="<?php echo Yii::app()->createUrl('chat/index'); ?>">
									<i class="fa fa-envelope" data-placement="bottom" tooltip title="Inbox"></i><span class="label label-warning hide new-messages-count">0</span>
								</a>
							</li>

							<li>
								<a class="count-info notifications-button" href="#">
									<i class="fa fa-bell" data-placement="bottom" tooltip title="Notifications"></i><span class="label label-danger hide new-notifications-count">0</span>
								</a>
							</li>
						</ul>

					</nav>
				</div>

				<div class="row wrapper" style="padding: 0px <?php echo $this->hasTopNavbar() ? 0 : 15; ?>px;">
					<?php echo $content; ?>
					<div class="footer">
						<div><strong>&copy; <?php echo date('Y'); ?> Some Copyright</strong></div>
					</div>
				</div>
			</div>
		</div>

		<div class="small-chats"></div>

		<script>
			var auth = {
				getUser: function() {
					return {
						id: <?php echo Yii::app()->user->id; ?>,
						name: '<?php echo Yii::app()->user->getState('user')->getFullName(); ?>',
						loginToken: '<?php echo Yii::app()->user->getState('loginToken')->login_token_hash; ?>',
					};
				},
			};

			$(document).ready(function() {
				$(this).on('paste', '[contenteditable]', function(e) {
					e.preventDefault();
					var text = (e.originalEvent || e).clipboardData.getData('text/plain');
					window.document.execCommand('insertText', false, text);
				});

				$(this).on('keypress', ':input[enter]', function(e) {
					if(e.keyCode == 13)
					{
						$('.' + $(this).attr('enter')).trigger('click');
					}
				});

				$(this).on('keypress', '.modal :input', function(e) {
					if(e.keyCode == 13)
					{
						$(this).parents('.modal').find('.ok-button').trigger('click');
					}
				});

				$('body').tooltip({
					selector: "[tooltip]",
					container: "body",
				});
			});

		</script>
	</body>
</html>