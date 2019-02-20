<?php
    $baseUrl = Yii::app()->request->getBaseUrl();
    $isIndexPage = Yii::app()->controller->id == 'public' && Yii::app()->controller->action->id == 'index';
    $isTermsPage = Yii::app()->controller->id == 'public' && Yii::app()->controller->action->id == 'terms';
    $addBottomLine = $isIndexPage || $isTermsPage;
?>
<div id="top-bar">
    <div class="container <?php echo $addBottomLine ? 'bottom-line' : ''; ?>">
        <div id="top-search">
            <div class="cell"><input type="text" /></div>
            <div class="cell"><img src="<?php echo $baseUrl; ?>/images/public/search.png" /></div>
        </div>
        <a href="<?php echo Yii::app()->createUrl(''); ?>"><img id="logo" src="<?php echo $baseUrl; ?>/images/public/logo.svg" /></a>
        <div id="nav">
            <a class="item" href="#contact" >צור קשר</a>
            <a class="item" href="#about" >מי אנחנו</a>
            <a class="item" href="#services" >שירותים משפטיים</a>
            <a class="item" href="#how-it-works" >איך זה עובד</a>
            <a class="item" href="#login" >החשבון שלי</a>
        </div>
    </div>
</div>

<div class="dialog" id="login-dialog">
    <div class="container text-center">
        <div class="content">
            <i class="fa fa-close close-button"></i>
            <h3>התחברות לחשבון האישי</h3>
            <div class="sep sep1"></div>
            <div class="field" data-field-name="email">
                <input type="email" id="login-email" placeholder="אימייל" />
                <div class="error"></div>
            </div>
            <div class="field" data-field-name="password">
                <input type="password" id="login-password" placeholder="סיסמא" />
                <div class="error"></div>
            </div>
            <div id="login-button" class="button primary">התחבר</div>
            <div class="sep sep2"></div>
            <h4>עדיין אין לך חשבון אישי?</h4>
            <div id="register-button" class="button primary">הרשמה רגילה</div>
            <div id="facebook-button" class="button">הרשמה באמצעות Facebook</div>
            <div id="google-button" class="button">הרשמה באמצעות Google</div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $window = $(window);
        var $topBar = $('#top-bar');
        var $nav = $('#nav');
        var $sections = $('section');
        var $loginDialog = $('#login-dialog');
        var $loginEmail = $('#login-email');
        var $loginPassword = $('#login-password');
        var $loginButton = $('#login-button');
        var isIndexPage = <?php echo $isIndexPage ? 'true' : 'false'; ?>;

        if(window.location.hash == '#login')
        {
            loginDialog();
        }
        else if(window.location.hash)
        {
            var $section = $(window.location.hash);
            $section.length && $('html, body').scrollTop($section.offset().top - $topBar.height());
        }

        $window.on('scroll', function() {
            $window.scrollTop() > 0 ? $topBar.addClass('scrolled') : $topBar.removeClass('scrolled');

            if(isIndexPage)
            {
                $sections.each(function() {
                    if(($window.scrollTop() + $window.height() / 2) > $(this).offset().top)
                    {
                        $nav.find('a').removeClass('active');
                        $nav.find('a[href="#' + $(this).attr('id') + '"]').addClass('active');
                    }
                });
            }
        });

        $nav.on('click', '.item', function() {
            var href = $(this).attr('href');
            if(href == '#login')
            {
                loginDialog();
                return false;
            }
            else
            {
                var $section = $(href);
                if($section.length)
                {
                    $('html, body').animate({
                        scrollTop: $section.offset().top - $topBar.height()
                    }, 2000);
                }
                else
                {
                    window.location = '<?php echo Yii::app()->createUrl(''); ?>' + href;
                }
            }
        });

        $loginButton.on('click', function() {
            if($loginButton.hasClass('disabled'))
            {
                return;
            }

            if(!$loginEmail.val())
            {
                $loginEmail.focus();
                return;
            }

            if(!$loginPassword.val())
            {
                $loginPassword.focus();
                return;
            }

            $loginButton.html('<i class="fa fa-spinner fa-spin"></i>').addClass('disabled');
            $loginDialog.find('.error').hide();

            $.ajax({
                url: "<?php echo Yii::app()->createUrl('public/login'); ?>",
                method: 'POST',
                cache: false,
                data: {
                    email: $loginEmail.val(),
                    password: $loginPassword.val(),
                    remember_me: 1
                },
                success: function(data) {
                    var response = $.parseJSON(data);

                    if(response.status == 'OK')
                    {
                        window.location = response.body.redirectUrl;
                    }
                    else if(response.status == 'ERROR')
                    {
                        $.each(response.body.errors, function(key, error) {
                            $loginDialog.find('.field[data-field-name="' + key + '"]').find('.error').html(error).show();
                        });

                        $loginButton.html('התחבר').removeClass('disabled');
                    }
                }
            });
        });

        $loginEmail.on('keyup', function(e) { e.keyCode == 13 && $loginButton.trigger('click'); });
        $loginPassword.on('keyup', function(e) { e.keyCode == 13 && $loginButton.trigger('click'); });

        $loginDialog.on('click', '.close-button', function() {
            $loginDialog.fadeOut('fast');
        });

        function loginDialog()
        {
            $loginDialog.fadeIn('fast');
            $loginEmail.focus();
        }

        $(document).on('keyup', function(e) {
            if(e.keyCode == 27)
            {
                $loginDialog.fadeOut('fast');
            }
        });
    });
</script>