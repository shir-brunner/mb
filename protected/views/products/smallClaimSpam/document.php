<?php
$fields = json_decode($request->request_fields, true);

function unknownText($text, $color = 'red')
{
    return '<span style="color: ' . $color . '; font-weight: bold;">' . GxHtml::encode($text) . '</span>';
}

$text1 = 'בבית המשפט לתביעות קטנות';
$text2 = 'ת.ק:';
$text5 = 'ת.ז.';
$text6 = 'טלפון';
$text7 = '- נ ג ד -';
$text8 = 'סכום התביעה';
$text9 = 'ש"ח + אגרת בימה"ש';
$text10 = '&nbsp;כ &nbsp;ת &nbsp;ב &nbsp;&nbsp; &nbsp;ת &nbsp;ב &nbsp;י &nbsp;ע &nbsp;ה';

$prosecutorTitle = '';

if(empty($fields['prosecutorGender']) || $fields['prosecutorGender'] == 'male')
{
    $prosecutorTitle = 'תובע';
}
else
{
    $prosecutorTitle = 'תובעת';
}

$defendantTitle = '';

if(empty($fields['defendantGender']) || $fields['defendantGender'] == 'male')
{
    $defendantTitle = 'נתבע';
}
else
{
    $defendantTitle = 'נתבעת';
}

$email = isset($fields['email']) ? $fields['email'] : '';
$phone = isset($fields['phone']) ? $fields['phone'] : '';

$messages = isset($fields['messages']) && is_array($fields['messages']) ? array_filter($fields['messages'], function($message) { return !empty($message['date']); }) : array();
$messagesCount = count($messages);
$total = $messagesCount * 1000;

$prosecutorGender = isset($fields['prosecutorGender']) ? $fields['prosecutorGender'] : '';
$prosecutorName = isset($fields['prosecutorName']) ? $fields['prosecutorName'] : '';
$prosecutorId = isset($fields['prosecutorId']) ? $fields['prosecutorId'] : '';
$prosecutorStreet = isset($fields['prosecutorStreet']) ? $fields['prosecutorStreet'] : '';
$prosecutorCity = isset($fields['prosecutorCity']) ? $fields['prosecutorCity'] : '';
$prosecutorZipCode = isset($fields['prosecutorZipCode']) ? $fields['prosecutorZipCode'] : '';
$prosecutorPhone = isset($fields['prosecutorPhone']) ? $fields['prosecutorPhone'] : '';

$defendantGender = isset($fields['defendantGender']) ? $fields['defendantGender'] : '';
$defendantIdType = isset($fields['defendantIdType']) ? $fields['defendantIdType'] : '';
$defendantIdTypeTitle = $defendantIdType == 'id' ? $text5 : ($defendantIdType == 'company' ? 'ח.פ.' : ($defendantIdType == 'am' ? 'ע.מ.' : ($defendantIdType == 'ar' ? 'ע.ר.' : $text5)));
$defendantName = isset($fields['defendantName']) ? $fields['defendantName'] : '';
$defendantId = isset($fields['defendantId']) ? $fields['defendantId'] : '';
$defendantStreet = isset($fields['defendantStreet']) ? $fields['defendantStreet'] : '';
$defendantCity = isset($fields['defendantCity']) ? $fields['defendantCity'] : '';
$defendantZipCode = isset($fields['defendantZipCode']) ? $fields['defendantZipCode'] : '';
$defendantPhone = isset($fields['defendantPhone']) ? $fields['defendantPhone'] : '';

$gaveEmail = isset($fields['gaveEmail']) && $fields['gaveEmail'] == 'yes';
$agreedEmail = isset($fields['agreedEmail']) && $fields['agreedEmail'] == 'yes';
$gavePhone = isset($fields['gavePhone']) && $fields['gavePhone'] == 'yes';
$agreedPhone = isset($fields['agreedPhone']) && $fields['agreedPhone'] == 'yes';

$rowMarginTop = 15;
$clauseCount = 0;

$addEmail = !$gaveEmail && !empty($email);
$addPhone = !$gavePhone && !empty($phone);
?>
<div style="font-family: david; padding: 94px; font-size: 16px; direction: rtl; line-height: 26px;">
    <div>
        <div style="float: left; width: 140px; font-weight: bold;">
            <?php echo $text2; ?><br />
        </div>
        <div style="float: right; width: 180px; font-weight: bold;">
            <?php echo $text1; ?><br />
            <?php echo isset($fields['courtCity']) ? $fields['courtCity'] : ''; ?>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="margin-top: 30px;">
        <div style="font-weight: bold; text-decoration: underline; width: 170px; float: right;">ה<?php echo $prosecutorTitle; ?>:</div>
        <div style="float: right; margin-bottom: 30px;">
            <div style="margin-bottom: 30px;">
                <div style="font-weight: bold;">
                    <?php echo GxHtml::encode($prosecutorName); ?>,
                    <?php echo $text5 . ' ' . GxHtml::encode($prosecutorId); ?>
                </div>
                <div>
                    <?php echo GxHtml::encode($prosecutorStreet . ' ' . $prosecutorCity); ?><?php echo empty($prosecutorZipCode) ? '' : (', ' . $prosecutorZipCode); ?>
                </div>
                <?php
                    if(!empty($prosecutorPhone))
                    {
                        echo '<div>' . $text6 . ': ' . GxHtml::encode($prosecutorPhone) . '</div>';
                    }
                ?>
            </div>
            <div style="text-align: center; width: 170px; font-weight: bold;"><?php echo $text7; ?></div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div>
        <div style="font-weight: bold; text-decoration: underline; width: 170px; float: right;">ה<?php echo $defendantTitle; ?>:</div>
        <div style="float: right;">
            <div style="margin-bottom: 30px;">
                <div style="font-weight: bold;">
                    <?php echo GxHtml::encode($defendantName); ?>,
                    <?php echo $defendantIdTypeTitle . ' ' . GxHtml::encode($defendantId); ?>
                </div>
                <div>
                    <?php echo GxHtml::encode($defendantStreet . ' ' . $defendantCity); ?><?php echo empty($defendantZipCode) ? '' : (', ' . $defendantZipCode); ?>
                </div>
                <?php
                    if(!empty($defendantPhone))
                    {
                        echo '<div>' . $text6 . ': ' . GxHtml::encode($defendantPhone) . '</div>';
                    }
                ?>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="font-weight: bold;">
        <?php echo $text8; ?>: <?php echo number_format($total); ?> <?php echo $text9; ?>
    </div>
    <div style="font-size: 26px; text-decoration: underline; text-align: center; font-weight: bold; line-height: 43px; margin-top: <?php echo $rowMarginTop; ?>px;"><span style="border-bottom: 1px double black;"><?php echo $text10; ?></span></div>
    <div style="text-align: justify;">
        <div style="margin-top: <?php echo $rowMarginTop; ?>px;">
            <span>עניינה של תובענה עקרונית זו היא במעשי ובמחדלי</span>
            <span> ה<?php echo $defendantTitle; ?>, </span>
            <span>שעיקרם במשלוח דברי פרסומת</span>
            <span> ל<?php echo $prosecutorTitle; ?>, </span>
            <span>ללא</span>
            <span> <?php echo $prosecutorGender == 'male' ? 'הסכמתו' : 'הסכמתה'; ?> </span>
            <span>ובניגוד להוראות סעיף 30א לחוק התקשורת (בזק ושירותים), התשמ"ב – 1982 (להלן: "</span>
            <span style="font-weight: bold;">חוק הספאם</span>
            <span>") ובניגוד לחוק הגנת הפרטיות, ותוך</span>
            <span> ש<?php echo $defendantGender == 'male' ? 'הוא' : 'היא'; ?> </span>
            <span>לכל הפחות</span>
            <span> <?php echo $defendantGender == 'male' ? 'מתרשל' : 'מתרשלת'; ?> </span>
            <span> ו<?php echo $defendantGender == 'male' ? 'מפר' : 'מפרה'; ?> </span>
            <span>חובה חקוקה</span>
            <span> ו<?php echo $defendantGender == 'male' ? 'נוהג' : 'נוהגת'; ?> </span>
            <span>בחוסר תום לב.</span>
        </div>
        <?php if($addEmail || $addPhone) { ?>
            <div>
                <span>מדובר בעניין עקרוני מאחר</span>
                <span> שה<?php echo $defendantTitle; ?> </span>
                <span> <?php echo $defendantGender == 'male' ? 'שולח' : 'שולחת'; ?> </span>
                <span> ל<?php echo $prosecutorTitle; ?> </span>
                <span>פרסומות, </span>
                <span>על אף שאף פעם לא</span>
                <span> <?php echo $prosecutorGender == 'male' ? 'מסר' : 'מסרה'; ?> </span>
                <span> <?php echo $defendantGender == 'male' ? 'לו' : 'לה'; ?> </span>
                <span> את </span>
                <?php if($addEmail && $addPhone) { ?>
                    <span> כתובת המייל </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'שלו' : 'שלה'; ?> </span>
                    <span> ואת מספר הנייד </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'שלו' : 'שלה'; ?></span>
                <?php } else if ($addEmail) { ?>
                    <span> כתובת המייל </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'שלו' : 'שלה'; ?></span>
                <?php } else if ($addPhone) { ?>
                    <span> מספר הנייד </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'שלו' : 'שלה'; ?></span>
                <?php } ?>
                <span>, ובוודאי שלא </span>
                <span> <?php echo $prosecutorGender == 'male' ? 'נתן' : 'נתנה'; ?> </span>
                <span> <?php echo $defendantGender == 'male' ? 'לו' : 'לה'; ?> </span>
                <span> רשות לשלוח </span>
                <span> <?php echo $prosecutorGender == 'male' ? 'אליו' : 'אליה'; ?> </span>
                <span> פרסומות, וצריך לעשות הכל כדי להפסיק את התופעה המטרידה הזו.</span>
            </div>
            <div>
                <span>לפי סעיף 30א(י)(3) לחוק הספאם, המטרה היא לעקור את התופעה הזו מהשורש, וכשבית המשפט קובע הפיצוי הוא צריך לקחת בחשבון את היקף ההפרה, לעודד נמענים כמו </span>
                <span> ה<?php echo $prosecutorTitle; ?> </span>
                <span>לממש זכויותיהם, ולהרתיע מפרסמים מלהמשיך להפר את החוק, ללא קשר לגובה הנזק.</span>
            </div>
        <?php } ?>
        <div style="font-weight: bold; text-decoration: underline; margin-top: <?php echo $rowMarginTop; ?>px;">הצדדים:</div>
        <ol>
            <li>
                <span>ה<?php echo $prosecutorTitle; ?> </span>
                <span> <?php echo $prosecutorGender == 'male' ? 'הינו' : 'הינה'; ?> </span>
                <span> הבעלים של </span>
                <?php if($email && $phone) { ?>
                    <span>מכשיר סלולארי נייד שמספרו </span>
                    <span> <?php echo GxHtml::encode($phone); ?> </span>
                    <span> (להלן: "הנייד") </span>
                    <span> ותיבת מייל שכתובתה </span>
                    <span> <?php echo GxHtml::encode($email); ?> </span>
                    <span>(להלן: "המייל").</span>
                <?php } else if($email) { ?>
                    <span> תיבת מייל שכתובתה </span>
                    <span> <?php echo GxHtml::encode($email); ?> </span>
                    <span>(להלן: "המייל").</span>
                <?php } else if($phone) { ?>
                    <span>מכשיר סלולארי נייד שמספרו </span>
                    <span> <?php echo GxHtml::encode($phone); ?> </span>
                    <span>(להלן: "הנייד").</span>
                <?php } ?>
            </li>
            <li>
                <span>ה<?php echo $defendantTitle; ?> </span>
                <span> <?php echo $defendantGender == 'male' ? 'הינו' : 'הינה'; ?> </span>
                <span> <?php echo unknownText('יש להשלים', 'blue'); ?>, </span>
                <span> <?php echo $defendantGender == 'male' ? 'שמקדם' : 'שמקדמת'; ?> </span>
                <span> את העסקים </span>
                <span> <?php echo $defendantGender == 'male' ? 'שלו' : 'שלה'; ?> </span>
                <span> בין היתר באמצעות משלוח דואר זבל </span>
                <span>ל<?php echo $prosecutorTitle; ?></span>
                <?php if((!$agreedEmail && $email) || (!$agreedPhone && $phone)) { ?>
                    <span> ללא </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'הסכמתו' : 'הסכמתה'; ?> </span>
                    <span> המפורשת מראש ובכתב.</span>
                <?php } else { ?>
                    <span>.</span>
                <?php } ?>
            </li>
        </ol>
        <div style="font-weight: bold; text-decoration: underline; margin-top: <?php echo $rowMarginTop; ?>px;">פרטי התביעה:</div>
        <ol start="3">
            <li>
                <?php
                    foreach($messages as $message)
                    {
                        echo '<li>';
                        echo '  <span>ביום </span>';
                        echo '  <span>' . $message['date']. ' </span>';
                        echo '  <span>' . ($prosecutorGender == 'male' ? 'קיבל התובע' : 'קיבלה התובעת') . ' </span>';
                        echo '  <span> בניגוד </span>';
                        echo '  <span>' . ($prosecutorGender == 'male' ? 'להסכמתו' : 'להסכמתה') . ' </span>';
                        echo '  <span> הודעת פרסומת, המופצת באופן מסחרי, </span>';
                        echo '  <span>' . ($defendantGender == 'male' ? 'מהנתבע' : 'מהנתבעת') . '.</span>';
                        echo '</li>';
                    }
                ?>
            </li>
            <li>
                <span>סה"כ </span>
                <span> <?php echo $prosecutorGender == 'male' ? 'קיבל התובע' : 'קיבלה התובעת'; ?> </span>
                <span> <?php echo $defendantGender == 'male' ? 'מהנתבע' : 'מהנתבעת'; ?> </span>
                <?php if($messagesCount == 1) { ?>
                    <span> הודעה אחת המופצת באופן מסחרי.</span>
                <?php } else { ?>
                    <span> <?php echo $messagesCount; ?> </span>
                    <span> הודעות המופצות באופן מסחרי.</span>
                <?php } ?>
            </li>
            <ul style="margin-top: <?php echo $rowMarginTop; ?>px;">
                <?php if($messagesCount == 1) { ?>
                    <li style="font-weight: bold; list-style-type: disc;">מצ"ב צילום מסך של הודעת הספאם.</li>
                <?php } else { ?>
                    <li style="font-weight: bold; list-style-type: disc;">מצ"ב צילומי מסך של הודעות הספאם.</li>
                <?php } ?>
            </ul>
            <li style="margin-top: <?php echo $rowMarginTop; ?>px;">
                <span>כפי שניתן לראות בנספחים, </span>
                <span> <?php echo $messagesCount == 1 ? 'הפרסומת כללה' : 'הפרסומות כללו'; ?> </span>
                <span>מסר המופץ באופן מסחרי, שמטרתו לעודד את הנמען לרכישת מוצר או שירות, או לעודד הוצאת כספים בדרך אחרת. תוכנם של דברי הפרסומת הנ"ל ממש מפרסמים, ובוודאי עשויים לפרסם את עסקיהם או לקדם את</span>
                <span> <?php echo $defendantGender == 'male' ? 'מטרותיו' : 'מטרותיה'; ?> </span>
                <span> של </span>
                <span>ה<?php echo $defendantTitle; ?>.</span>
            </li>
            <?php if($addEmail) { ?>
                <li>
                    <span> ה<?php echo $prosecutorTitle; ?> </span>
                    <span> אף פעם לא </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'מסר' : 'מסרה'; ?> </span>
                    <span>ל<?php echo $defendantTitle; ?> </span>
                    <span> את כתובת המייל אליה שלחו את </span>
                    <span> <?php echo $messagesCount == 1 ? 'הפרסומת' : 'הפרסומות'; ?>, </span>
                    <span>ובוודאי </span>
                    <span style="font-weight: bold;"> שלא <?php echo $prosecutorGender == 'male' ? 'נתן' : 'נתנה'; ?> </span>
                    <span style="font-weight: bold;"> הסכמה מפורשת בכתב מראש </span>
                    <span> לשלוח </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'אליו' : 'אליה'; ?> </span>
                    <span>פרסומות.</span>
                </li>
            <?php } ?>
            <?php if($addPhone) { ?>
                <li>
                    <span> ה<?php echo $prosecutorTitle; ?> </span>
                    <span> אף פעם לא </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'מסר' : 'מסרה'; ?> </span>
                    <span>ל<?php echo $defendantTitle; ?> </span>
                    <span> את מספר הנייד אליו שלחו את </span>
                    <span> <?php echo $messagesCount == 1 ? 'הפרסומת' : 'הפרסומות'; ?>, </span>
                    <span>ובוודאי </span>
                    <span style="font-weight: bold;"> שלא </span>
                    <span style="font-weight: bold;"> <?php echo $prosecutorGender == 'male' ? 'נתן' : 'נתנה'; ?> </span>
                    <span style="font-weight: bold;"> הסכמה מפורשת בכתב מראש </span>
                    <span> לשלוח </span>
                    <span> <?php echo $prosecutorGender == 'male' ? 'אליו' : 'אליה'; ?> </span>
                    <span>פרסומות.</span>
                </li>
            <?php } ?>
            <li>
                <span>בנוסף, לפי סעיף </span>
                <span style="font-weight: bold;">30א(י)(5)</span>
                <span>לחוק התקשורת, קיימת</span>
                <span style="font-weight: bold;">חזקה </span>
                <span>שהמפרסמים הפרו את החוק ביודעין.</span>
            </li>
            <!--Code from here is not auto generated-->
            <li>
                לפי סעיף 30א(ד) לחוק התקשורת הודעת הסירוב תינתן בשתי דרכים: בכתב או בדרך שבה שוגר דבר הפרסומת, לפי בחירת הנמען. מדובר ברשימה סגורה של דרכים.
            </li>
            <li>
                התובע/ת ביקש מהנתבע/ת ביום _____________ שיפסיק/שתפסיק לשלוח לו/ה הודעות המופצות באופן מסחרי.
                <ul style="margin-top: <?php echo $rowMarginTop; ?>px; margin-bottom: <?php echo $rowMarginTop; ?>px;"><li style="font-weight: bold; list-style-type: disc;">מצ"ב צילום מסך של הבקשה להסרה.</li></ul>
            </li>
            <li>
                גם לאחר שהתובע/ת ביקשה מהנתבע/ת להפסיק לשלוח לה הודעות המופצות באופן מסחרי, המשיך/ה הנתבע/ת לשלוח לתובע/ת הודעות המופצות באופן מסחרי.
                <ul style="margin-top: <?php echo $rowMarginTop; ?>px; margin-bottom: <?php echo $rowMarginTop; ?>px;"><li style="font-weight: bold; list-style-type: disc;">מצ"ב צילום מסך של ההודעות שנשלחו לתובע/ת גם לאחר הבקשה להסרה.</li></ul>
            </li>
            <li>
                במקרה הספציפי של התובע/ת בו בכלל לא נתן מראש את הסכמתו המפורשת לשלוח אליו/ה פרסומות, כמו שנקבע בשתי החלטות של בית המשפט המחוזי בתל-אביב שמפורסמות באתר של איגוד האינטרנט הישראלי, בהחלטה מיום 27/12/2012 ברת"ק 21858-06-12 ובהחלטה מיום 17/03/2013 ברת"ק 17771-12-12, החלטות שפורסמו באתר האינטרנט של איגוד האינטרנט הישראלי, אם התובע/ת לא מבקש/ת להפסיק לשלוח אליו/ה את הפרסומות זה לא פועל לחובתו/ה, זאת מאחר ומעולם לא אישר/ה לנתבע/ת לשלוח אליו/ה הפרסומות.
            </li>
            <li>
                הפצת הפרסומת כאמור מהווה הפרה של הוראות החוק. משלוח מסרים מסחריים כאמור מערער את האמון, הביטחון והנוחות בשימוש בטכנולוגיה, לציבור בכלל ולתובע/ת בפרט וכמובן מהוה מטרד. בכלל זה, נדרש התובע/ת להשחית משאבים וזמן על-מנת להתמודד את דברי הפרסומת ששיגר/ה אליו/ה הנתבע/ת.
            </li>
            <li>
                בהתאם להוראות סעיף 30א (י) (1) לחוק, רשאי בית המשפט הנכבד לפסוק לתובע/ת פיצויים ללא הוכחת נזק, בסכום של עד 1,000 ₪ בשל כל דבר פרסומת שקיבל/ה מהנתבע/ת בניגוד להוראות החוק.
            </li>
            <li>
                החוק קובע כי כאשר שבית המשפט פוסק את הפיצוי הוא לא יתחשב בגובה הנזק שנגרם לנמען כתוצאה מביצוע ההפרה.
            </li>
            <li>
                הנתבע/ת פעל/ה לכל הפחות ברשלנות ו/או בחוסר תום לב. הנתבע/ת הפר/ה גם את חוק הגנת הפרטיות ופקודת הנזיקין, כאשר הפרו חובה חקוקה, התרשלו, הטרידו את התובע/ת וכאשר השתמשו ללא אישור בפרט אישי שלו/ה.
            </li>
            <li>
                אשר על כן, מתבקש בית המשפט הנכבד לחייב את הנתבע/ת לשלם לתובע/ת סך של <?php echo number_format($total) . ' ש"ח'; ?> בגין <?php echo $messagesCount; ?> הודעות הפרסומת שהתובע/ת קיבל/ה מהם.
            </li>
            <li>
                בנוסף לחייב את הנתבע/ת הוצאות משפט, ריבית והצמדה, ו/או כל סעד אחר שבית המשפט ימצא.
            </li>
            <li>
                לבית המשפט הנכבד הסמכות העניינית והמקומית לדון בתביעה.
            </li>
            <li>
                אני מצהיר כי בשנה האחרונה לא הגשתי בבית משפט זה יותר מחמש תביעות.
            </li>
        </ol>
        <p></p>
        <p></p>
        <div style="text-align: left;">_____________</div>
        <div style="text-align: left;">ה<?php echo $prosecutorTitle; ?></div>
    </div>
</div>