<?php $baseUrl = Yii::app()->request->getBaseUrl(); ?>
<section id="top-section">
    <div class="container">
        <div id="top-bar-space"></div>
        <div class="content">
            <div class="title">
                <h1>שירותים משפטיים ONLINE בהתאמה אישית ובליווי עורך דין</h1>
            </div>
            <div class="text-center">
                <div class="btn services-btn">השירותים שלנו</div>
            </div>
            <div class="text-center">
                <img class="down-arrow" src="<?php echo $baseUrl; ?>/images/public/down-arrow.png" />
            </div>
        </div>
    </div>
</section>

<section id="how-it-works">
    <div class="container">
        <div class="title text-blue">
            <h2>איך זה עובד?</h2>
        </div>
        <div class="sub-title">
            <h3>משפט בקליק הינו אתר ייחודי המאפשר תקשורת אינטראקטיבית פשוטה ומהירה עם עורך הדין.</h3>
        </div>
        <div class="steps">
            <div class="step">
                <div class="text-center"><div class="number">3</div></div>
                <div class="text-center">
                    <img src="<?php echo $baseUrl; ?>/images/public/edit_doc.png" />
                </div>
                <div class="header text-blue">
                    עריכת המסמך ע"י עורך דין
                </div>
                <div class="text">
                    עורך דין ייצור עבורכם את החוזה / הסכם המבוקש
                </div>
            </div>
            <div class="step">
                <div class="text-center"><div class="number">2</div></div>
                <div class="text-center">
                    <img src="<?php echo $baseUrl; ?>/images/public/question_fill.png" />
                </div>
                <div class="header text-blue">
                    מילוי השאלון
                </div>
                <div class="text">
                    ממלאים את השאלון עפ"י השדות הקיימים
                </div>
            </div>
            <div class="step">
                <div class="text-center"><div class="number">1</div></div>
                <div class="text-center">
                    <img src="<?php echo $baseUrl; ?>/images/public/product_pick.png" />
                </div>
                <div class="header text-blue">
                    בחירת המוצר
                </div>
                <div class="text">
בוחרים את סוג השירות בו אתם מעוניינים מהרשימה המוצעת באתר
                </div>
            </div>
        </div>
        <div class="text-center help-text">
            לאחר קבלת המסמך, תוכלו לשנות אותו כרצונכם בצורה אינטראקטיבית, פשוטה ונוחה עד לקבלת מסמך לשביעות רצונכם.
        </div>
    </div>
</section>

<section id="services">
    <div class="container">
        <div class="left-col">
            <?php
                foreach($this->getCategories() as $category)
                {
                    echo '<div class="item">';
                    echo '    <div class="texts">';
                    echo '        <div class="header">' . GxHtml::encode($category->category_name) . '</div>';
                    echo '        <div class="desc">' . GxHtml::encode($category->category_description) . '</div>';
                    echo '    </div>';
                    echo '    <a href="' . Yii::app()->createUrl('public/category', array('id' => $category->getPrimaryKey())) . '"><div class="button">' . GxHtml::encode($category->category_select_button_text) . '</div></a>';
                    echo '</div>';
                }
            ?>

        </div>
        <div class="space"></div>
        <div class="right-col">
            <div class="text">השירותים שלנו</div>
            <img src="<?php echo $baseUrl; ?>/images/public/our_services.png" />
        </div>
    </div>
</section>

<section id="about">
    <div class="container">
        <div class="left-col">
            <div class="text">
                משפט בקליק הינו אתר אשר הוקם ומופעל ע"י עו"ד ניסים רחמים.<br/>
                ניסים הינו המייסד והיו"ר של עמותת "חג שמח" המסייעת למשפחות נזקקות בפסח ובראש השנה כאשר העיקרון המנחה הינו <span class="bold">100% תרומה.</span> בעמותת "חג שמח" הכל (כולל הכל!) נעשה בהתנדבות מלאה ובאהבה גדולה. כחלק מהעשייה החברתית של ניסים הוא הקים אתר משפטי טכנולוגי שיאפשר לצרכן הישראלי לרכוש שירותים משפטיים לכל אזרח ואזרח בתמורה מידתית. קיצור משמעותי של זמן העבודה על כל שירות ושירות תוך שימוש בטכנולוגיות מתקדמות, מאפשרת לייצר מצב של WIN WIN.
            </div>
        </div>
        <div class="right-col">
            <div class="title">מי אנחנו?</div>
        </div>
    </div>
</section>

<section id="contact">
    <div class="container">
        <div class="left-col">
            <form id="contact-form">
                <input type="text" tabindex="2" placeholder="טלפון" name="phone" data-field-name="contact_phone" />
                <input type="text" tabindex="1" placeholder="שם מלא" name="name" data-field-name="contact_name" />
                <input type="email" tabindex="3" placeholder="אימייל" name="email" data-field-name="contact_email" />
                <textarea name="message" tabindex="4" placeholder="תוכן ההודעה" data-field-name="contact_message"></textarea>
                <div class="submit-button">שליחה</div>
            </form>
        </div>
        <div class="right-col">
            <div class="title">נשמח לשמוע מכם...</div>
            <div id="google-map"></div>
            <div class="address">
                רח' לינקולן 16 תל אביב, ת.ד. 51737 מיקוד 6713408<br />
                טלפון 077-5088588 | פקס 077-3179445 | <a href="mailto:contact@mishpatbeclick.com">contact@mishpatbeclick.com</a>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        var $contactForm = $('#contact-form');
        var $contactSubmitButton = $contactForm.find('.submit-button');

        $contactSubmitButton.on('click', function() {
            if($contactSubmitButton.hasClass('disabled'))
            {
                return false;
            }

            $contactSubmitButton.addClass('disabled');

            apiRequest(config.api.urls.contact.create, 'POST', $contactForm.serialize(), function(response) {
                if(response.status == 'OK')
                {
                    $contactForm.find(':input').val('');
                    alert('תודה על פנייתך, נחזור אליך בהקדם');
                }
                else if(response.status == 'ERROR')
                {
                    $.each(response.body.errors, function(key, error) {
                        alert(translateError(error[0]));
                    });
                }

                $contactSubmitButton.removeClass('disabled');
            });

            return false;
        });

        function translateError(error)
        {
            switch(error)
            {
                case 'Message cannot be blank.': return 'יש למלא את תוכן ההודעה';
                case 'Name cannot be blank.': return 'יש למלא שם מלא';
                case 'Email cannot be blank.': return 'יש למלא אימייל';
                case 'Phone cannot be blank.': return 'יש למלא טלפון';
                case 'Email is not a valid email address.': return 'האימייל שהוזן אינו תקין.';
            }

            return error;
        }

        $('#top-section').on('click', '.services-btn, .down-arrow', function() {
            $('#nav .item[href="#services"]').trigger('click');
        });
    });

    function initMap()
    {
        var location = { lat: 32.066597, lng: 34.782050 };
        var map = new google.maps.Map(document.getElementById('google-map'), {
            zoom: 12,
            center: location
        });

        new google.maps.Marker({ position: location, map: map });
    }
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCpWfMpV99_zJqAngyp6Z3rbtZbHRLZNk&callback=initMap&language=iw"></script>