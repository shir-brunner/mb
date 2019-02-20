<?php $description = GxHtml::encode('TODO: fill description'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo $this->pageTitle; ?></title>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

    <?php
    $baseUrl = Yii::app()->request->getBaseUrl();
    $clientScript = Yii::app()->clientScript;
    $cssVersion = Yii::app()->params['cssVersion'];
    $jsVersion = Yii::app()->params['jsVersion'];

    $clientScript->registerCssFile($baseUrl . '/css/public/bootstrap.min.css');
    $clientScript->registerCssFile($baseUrl . '/css/public/font-awesome.min.css');
    $clientScript->registerCssFile($baseUrl . '/js/public/jquery-ui/jquery-ui.css');
    $clientScript->registerCssFile($baseUrl . '/css/public/main.css');
    ?>

    <script type="text/javascript" src="<?php echo $baseUrl; ?>/js/public/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $baseUrl; ?>/js/public/jquery-ui/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo $baseUrl; ?>/js/public/jquery-ui/datepicker-he.js"></script>
</head>

<body>
    <?php $this->renderPartial('//layouts/_topBar'); ?>
    <?php echo $content; ?>
    <section id="footer">
        <div class="container">
            <div class="social-icons">
                <img class="icon" src="<?php echo $baseUrl; ?>/images/public/facebook.png" />
                <img class="icon" src="<?php echo $baseUrl; ?>/images/public/google.png" />
                <img class="icon" src="<?php echo $baseUrl; ?>/images/public/linked-in.png" />
            </div>
            <div class="copyright">כל הזכויות שמורות למשפט בקליק.</div>
        </div>
    </section>
    <script src="<?php echo $baseUrl; ?>/js/public/config.js"></script>
    <script src="<?php echo $baseUrl; ?>/js/public/core.js"></script>
    <script src="<?php echo $baseUrl; ?>/js/public/upload.js"></script>
</body>
</html>