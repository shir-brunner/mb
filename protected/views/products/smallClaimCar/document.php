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
    $text11 = 'כי התאונה אירעה כדלקמן';
    $text12 = 'כי התאונה אירעה';

    $prosecutors = $fields['prosecutors'];
    $prosecutorsCount = count($prosecutors);
    $prosecutorsTitle = '';
    $prosecutorsTitleWithoutHa = '';

    if($prosecutorsCount == 1 && $prosecutors[0]['gender'] == 'male')
    {
        $prosecutorsTitle = 'התובע';
        $prosecutorsTitleWithoutHa = 'תובע';
    }
    else if($prosecutorsCount == 1 && $prosecutors[0]['gender'] == 'female')
    {
        $prosecutorsTitle = 'התובעת';
        $prosecutorsTitleWithoutHa = 'תובעת';
    }
    else if($prosecutorsCount > 1)
    {
        $genders = array_unique(array_map(function($prosecutor) { return $prosecutor['gender']; }, $prosecutors));
        if(count($genders) > 1 || $genders[0] == 'male')
        {
            if(in_array('male', $genders))
            {
                $prosecutorsTitle = 'התובעים';
                $prosecutorsTitleWithoutHa = 'תובעים';
            }
            else
            {
                $prosecutorsTitle = 'התובעות';
                $prosecutorsTitleWithoutHa = 'תובעות';
            }
        }
        else if($genders[0] == 'female')
        {
            $prosecutorsTitle = 'התובעות';
            $prosecutorsTitleWithoutHa = 'תובעות';
        }
    }

    $defendants = $fields['defendants'];
    $defendantsCount = count($defendants);
    $defendantsTitle = '';

    if($defendantsCount == 1 && $defendants[0]['gender'] == 'male')
    {
        $defendantsTitle = 'הנתבע';
    }
    else if($defendantsCount == 1 && ($defendants[0]['gender'] == 'female' || $defendants[0]['gender'] == 'other'))
    {
        $defendantsTitle = 'הנתבעת';
    }
    else if($defendantsCount > 1)
    {
        $genders = array_unique(array_map(function($defendant) { return $defendant['gender']; }, $defendants));
        if(count($genders) > 1 || $genders[0] == 'male')
        {
            if(in_array('male', $genders))
            {
                $defendantsTitle = 'הנתבעים';
            }
            else
            {
                $defendantsTitle = 'הנתבעות';
            }
        }
        else if($genders[0] == 'female')
        {
            $defendantsTitle = 'הנתבעות';
        }
    }

    $total = 0;

    if(!empty($fields['directDamageCost'])) { $total += intval($fields['directDamageCost']); }
    if(!empty($fields['appraiserCost'])) { $total += intval($fields['appraiserCost']); }
    if(!empty($fields['workDaysLossCost'])) { $total += intval($fields['workDaysLossCost']); }
    if(!empty($fields['aggravationCost'])) { $total += intval($fields['aggravationCost']); }

    $expenses = array(
        array('name' => 'עלות תיקון הרכב', 'cost' => empty($fields['directDamageCost']) ? 0 : $fields['directDamageCost']),
        array('name' => 'שכ"ט שמאי', 'cost' => empty($fields['appraiserCost']) ? 0 : $fields['appraiserCost']),
        array('name' => 'הפסד ימי עבודה', 'cost' => empty($fields['workDaysLossCost']) ? 0 : $fields['workDaysLossCost']),
        array('name' => 'עוגמת נפש', 'cost' => empty($fields['aggravationCost']) ? 0 : $fields['aggravationCost']),
    );

    if(isset($fields['moreExpenses']) && is_array($fields['moreExpenses']))
    {
        foreach($fields['moreExpenses'] as $expense)
        {
            if(isset($expense['cost']) && is_numeric($expense['cost']))
            {
                $total += intval($expense['cost']);
                $expenses[] = array('name' => $expense['description'], 'cost' => empty($expense['cost']) ? 0 : $expense['cost']);
            }
        }
    }

    foreach($prosecutors as $key => &$prosecutor)
    {
        $prosecutor['title'] = $prosecutor['gender'] == 'male' ? 'תובע' : 'תובעת';

        if($prosecutorsCount > 1)
        {
            $prosecutor['order'] = $key + 1;
            $prosecutor['title'] .= ' ' . $prosecutor['order'];
        }
        else //add "ha" to title if no numbering
        {
            $prosecutor['title'] = 'ה' . $prosecutor['title'];
        }
    }

    unset($prosecutor);

    foreach($defendants as $key => &$defendant)
    {
        $defendant['title'] = $defendant['gender'] == 'male' ? 'נתבע' : 'נתבעת';

        if($defendantsCount > 1)
        {
            $defendant['order'] = $key + 1;
            $defendant['title'] .= ' ' . $defendant['order'];
        }
        else //add "ha" to title if no numbering
        {
            $defendant['title'] = 'ה' . $defendant['title'];
        }
    }

    unset($defendant);

    $defendantDrivers = array_filter($defendants, function($defendant) {
        return in_array('נהג הרכב', isset($defendant['relations']) ? $defendant['relations'] : array());
    });
    $defendantDriver = isset($defendantDrivers[0]) ? $defendantDrivers[0] : null;

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
        <div style="font-weight: bold; text-decoration: underline; width: 170px; float: right;"><?php echo $prosecutorsTitle; ?>:</div>
        <div style="float: right; margin-bottom: 30px;">
            <?php
                foreach($prosecutors as $key => $prosecutor)
                {
                    echo '<div>';
                    if($prosecutorsCount > 1)
                    {
                        echo '<div style="float: right; width: 15px;">' . $prosecutor['order'] . '. </div>';
                    }

                    echo '<div style="margin-bottom: 30px; float: right;">';
                    echo '    <div style="font-weight: bold;">';
                    echo '        ' . GxHtml::encode($prosecutor['name']) . ', ';
                    echo          $text5 . ' ' . GxHtml::encode($prosecutor['id']);
                    echo '    </div>';
                    echo '    <div>';
                    echo          GxHtml::encode($prosecutor['street'] . ' ' . GxHtml::encode($prosecutor['city']));
                    echo          empty($prosecutor['zipCode']) ? '' : (', ' . GxHtml::encode($prosecutor['zipCode']));
                    echo '    </div>';

                    if(!empty($prosecutor['phone']))
                    {
                        echo '<div>' . $text6 . ': ' . GxHtml::encode($prosecutor['phone']) . '</div>';
                    }

                    echo '</div>';
                    echo '<div style="clear: both;"></div>';
                    echo '</div>';
                }
            ?>
            <div style="text-align: center; width: 170px; font-weight: bold;"><?php echo $text7; ?></div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div>
        <div style="font-weight: bold; text-decoration: underline; width: 170px; float: right;"><?php echo $defendantsTitle; ?>:</div>
        <div style="float: right;">
            <?php
                foreach($defendants as $key => $defendant)
                {
                    echo '<div>';
                    if($defendantsCount > 1)
                    {
                        echo '<div style="float: right; width: 15px;">' . $defendant['order'] . '. </div>';
                    }
                    echo '<div style="margin-bottom: 30px; float: right;">';
                    echo '    <div style="font-weight: bold;">';

                    $idType = $defendant['idType'] == 'id' ? $text5 : ($defendant['idType'] == 'company' ? 'ח.פ.' : ($defendant['idType'] == 'am' ? 'ע.מ.' : ($defendant['idType'] == 'ar' ? 'ע.ר.' : $text5)));

                    echo '        ' . GxHtml::encode($defendant['name']) . ', ';
                    echo          $idType . ' ' . GxHtml::encode($defendant['id']);
                    echo '    </div>';
                    echo '    <div>';
                    echo          GxHtml::encode($defendant['street'] . ' ' . GxHtml::encode($defendant['city']));
                    echo          empty($defendant['zipCode']) ? '' : (', ' . GxHtml::encode($defendant['zipCode']));
                    echo '    </div>';

                    if(!empty($defendant['phone']))
                    {
                        echo '<div>' . $text6 . ': ' . GxHtml::encode($defendant['phone']) . '</div>';
                    }

                    echo '</div>';
                    echo '<div style="clear: both;"></div>';
                    echo '</div>';
                }
            ?>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="font-weight: bold;">
        <?php echo $text8; ?>: <?php echo number_format($total); ?> <?php echo $text9; ?>
    </div>
    <div style="font-size: 26px; text-decoration: underline; text-align: center; font-weight: bold; line-height: 43px;"><span style="border-bottom: 1px double black;"><?php echo $text10; ?></span></div>
    <div style="text-align: justify;">
        <ol>
            <?php
                $rowMarginBottom = 15;
                $clauseCount = 0;
                $firstRound = true;
                foreach($prosecutors as $prosecutor)
                {
                    $clauseCount++;
                    $text = '';
                    if($prosecutor['gender'] == 'female')
                    {
                        $text = $prosecutor['title'] . ' היתה';
                        $theDriver = 'הנהגת';
                    }
                    else //male
                    {
                        $text = $prosecutor['title'] . ' היה';
                        $theDriver = 'הנהג';
                    }

                    $isCarOwner = is_array($prosecutor['relations']) && in_array('בעל הרכב', $prosecutor['relations']);
                    $isDriver = is_array($prosecutor['relations']) && in_array('נהג הרכב', $prosecutor['relations']);

                    echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                    echo $text . ' ';
                    echo 'בכל הזמנים הרלוונטים ';

                    if($isCarOwner && $isDriver) {
                        echo 'הבעלים ו' . $theDriver . ' ברכב';
                        if(!$firstRound) {
                            echo ' הנפגע.';
                        }
                    } else if($isCarOwner) {
                        if($firstRound) {
                            echo 'הבעלים של רכב';
                        } else {
                            echo 'הבעלים של הרכב הנפגע.';
                        }
                    } else if($isDriver) {
                        if($firstRound) {
                            echo $theDriver . ' ברכב';
                        } else {
                            echo $theDriver . ' ברכב הנפגע.';
                        }
                    } else { //other
                        echo ' ' . unknownText($prosecutor['otherRelation']);
                        if(!$firstRound) {
                            echo '.';
                        }
                    }

                    if($firstRound)
                    {
                        echo ' מסוג ';
                        echo $fields['damagedCarType'];
                        echo ' שמספרו ';
                        echo $fields['damagedCarNumber'];
                        echo '.';
                        echo ' (להלן: "הרכב הנפגע").';
                        $firstRound = false;
                    }

                    echo '</li>';
                }

                $firstRound = true;
                foreach($defendants as $defendant)
                {
                    $clauseCount++;
                    $text = '';
                    if($defendant['gender'] == 'female')
                    {
                        $text = $defendant['title'] . ' היתה';
                        $theDriver = 'הנהגת';
                    }
                    else if($defendant['gender'] == 'male')
                    {
                        $text = $defendant['title'] . ' היה';
                        $theDriver = 'הנהג';
                    }
                    else //company
                    {
                        $text = $defendant['title'] . ' היתה';
                        $theDriver = 'הנהגת';
                    }

                    $isCarOwner = is_array($defendant['relations']) && in_array('בעל הרכב', $defendant['relations']);
                    $isDriver = is_array($defendant['relations']) && in_array('נהג הרכב', $defendant['relations']);
                    $isCompany = is_array($defendant['relations']) && in_array('חברת ביטוח', $defendant['relations']);

                    echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                    echo $text . ' ';
                    echo 'בכל הזמנים הרלוונטים ';

                    if($isCarOwner && $isDriver) {
                        echo 'הבעלים ו' . $theDriver . ' ברכב';
                        if(!$firstRound) {
                            echo ' הפוגע';
                        }
                    } else if($isCarOwner) {
                        if($firstRound) {
                            echo 'הבעלים של רכב';
                        } else {
                            echo 'הבעלים של הרכב הפוגע';
                        }
                    } else if($isDriver) {
                        if ($firstRound) {
                            echo $theDriver . ' ברכב';
                        } else {
                            echo $theDriver . ' ברכב הפוגע';
                        }
                    } else if($isCompany) {
                        echo 'חברת ביטוח המבטחת את ';
                        if ($firstRound) {
                            echo 'הרכב';
                        } else {
                            echo 'הרכב הפוגע';
                        }
                    } else { //other
                        echo ' ' . unknownText($defendant['otherRelation']);
                    }

                    if($firstRound)
                    {
                        echo ' מסוג ';
                        echo $fields['hittingCarType'];
                        echo ' שמספרו ';
                        echo $fields['hittingCarNumber'];
                        echo '.';
                        echo ' (להלן: "הרכב הפוגע")';
                        $firstRound = false;
                    }

                    if($isCompany)
                    {
                        if(!empty($fields['hittingCarPolicy']))
                        {
                            echo ', ';
                            echo 'והאחראית לתשלום הנזק מכח הוראות הפוליסת ביטוח שמספרה ';
                            echo GxHtml::encode($fields['hittingCarPolicy']);
                            echo ' ו/או מכוח חוק חוזה ביטוח תשמ"א-1981.';
                        }
                        else
                        {
                            echo '.';
                        }
                    }
                    else
                    {
                        echo '.';
                    }

                    echo '</li>';
                }

                $clauseCount++;
                // ------ li ------ //
                $willClaim = $prosecutorsTitle == 'התובע' ? 'יטען' : ($prosecutorsTitle == 'התובעת' ? 'תטען' : ($prosecutorsTitle == 'התובעים' ? 'יטענו' : ($prosecutorsTitle == 'התובעות' ? 'יטענו' : 'יטענו')));
                echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                echo $prosecutorsTitle . ' ';
                echo $willClaim . ' ';
                echo $text11 . ':';
                echo '<div style="font-weight: bold; margin-top: ' . $rowMarginBottom . 'px;">';
                echo 'ביום ' . (empty($fields['accidentDate']) ? unknownText('אין תאריך') : $fields['accidentDate']);
                echo ' בסמוך לשעה ' . (empty($fields['accidentTime']) ? unknownText('אין שעה') : $fields['accidentTime']) . ' ';
                echo empty($fields['accidentDescription']) ? unknownText('לא הוכנס תיאור של התאונה') : unknownText($fields['accidentDescription'], 'blue');
                echo ' (להלן: "התאונה").';
                echo '</div>';
                echo '</li>';

                $clauseCount++;
                // ------ li ------ //
                $faultTitle = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'באשמתו' : 'באשמתה';
                $theDriver = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'הנהג' : 'הנהגת';
                $noticed = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'הבחין' : 'הבחינה';
                $couldAndMust = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'יכול וחייב היה' : 'יכלה וחייבת הייתה';
                $droveCar = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'נהג את רכבו' : 'נהגה את רכבה';
                $warned = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'התריע' : 'התריעה';
                $drove = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'נהג' : 'נהגה';
                $pulled = !$defendantDriver || $defendantDriver['gender'] == 'male' ? 'הסיט את רכבו' : 'הסיטה את רכבה';

                echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                echo '    <span style="font-weight: bold;">';
                echo            $prosecutorsTitle . ' ';
                echo            $willClaim . ' ';
                echo            $text12 . ' ' . $faultTitle . ' הבלעדית של ' . $theDriver . ' ברכב הפוגע אשר:';
                echo '    </span>';
                echo '    <div style="padding-right: 20px; margin-top: ' . $rowMarginBottom . 'px;">';
                echo        $clauseCount . '.1 ';
                echo '      <span style="font-weight: bold;">';
                echo        unknownText('יש למלא ', 'blue') . 'בהתאם לתקנה ' . unknownText('יש למלא', 'blue') . ' לתקנות התעבורה';
                echo        '</span>';
                echo '    </div>';
                echo '    <div style="padding-right: 20px; margin-top: ' . $rowMarginBottom . 'px;">';
                echo        $clauseCount . '.2 ';
                echo        'לא ' . $noticed . ' ברכב הנפגע למרות ש' . $couldAndMust . ' לעשות כן.';
                echo '    </div>';
                echo '    <div style="padding-right: 20px; margin-top: ' . $rowMarginBottom . 'px;">';
                echo        $clauseCount . '.3 ';
                echo        $droveCar . ' בחוסר זהירות מוחלט.';
                echo '    </div>';
                echo '    <div style="padding-right: 20px; margin-top: ' . $rowMarginBottom . 'px;">';
                echo        $clauseCount . '.4 ';
                echo        'לא ' . $warned . ' מפני הסכנה למרות ש' . $couldAndMust . ' לעשות כן.';
                echo '    </div>';
                echo '    <div style="padding-right: 20px; margin-top: ' . $rowMarginBottom . 'px;">';
                echo        $clauseCount . '.5 ';
                echo        'לא ' . $drove . ' כפי שנהג סביר ומיומן היה נוהג בנסיבות העניין.';
                echo '    </div>';
                echo '    <div style="padding-right: 20px; margin-top: ' . $rowMarginBottom . 'px;">';
                echo        $clauseCount . '.6 ';
                echo        'לא ' . $pulled . ' על מנת למנוע התאונה, על אף כי ' . $couldAndMust . ' לעשות כן.';
                echo '    </div>';
                echo '</li>';

                $clauseCount++;
                // ------ li ------ //
                echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                echo '    <span style="font-weight: bold;">';
                echo            'בעקבות התאונה נגרמו ל' . $prosecutorsTitleWithoutHa . ' הנזקים כדלקמן:';
                echo '    </span>';
                echo '    <div style="width: 260px; padding-right: 20px;">';

                $num = 1;
                foreach($expenses as $expense)
                {
                    if(empty($expense['cost']))
                    {
                        continue;
                    }

                    echo '    <div style="margin-top: ' . $rowMarginBottom . 'px;">';
                    echo '        <div style="width: 160px; float: right;">';
                    echo            $clauseCount . '.' . $num++ . ' ' . $expense['name'] . ' -';
                    echo          '</div>';
                    echo '        <div style="float: right; width: 50px;">' . number_format($expense['cost']) . '</div>';
                    echo '        <div style="float: right;">ש"ח</div>';
                    echo '        <div style="clear: both;"></div>';
                    echo '    </div>';
                }

                echo '        <div>_____________________________</div>';
                echo '        <div style="font-weight: bold; float: right; width: 160px;">סה"כ -</div>';
                echo '        <div style="font-weight: bold; float: right; width: 50px;">' . number_format($total) . '</div>';
                echo '        <div style="font-weight: bold; float: right;">ש"ח</div>';
                echo '        <div style="clear: both;"></div>';
                echo '    </div>';
                echo '</li>';

                $clauseCount++;
                // ------ li ------ //
                $owe = $defendantsTitle == 'הנתבע' ? 'חב' : ($defendantsTitle == 'הנתבעת' ? 'חבה' : ($defendantsTitle == 'הנתבעים' ? 'חבים' : ($defendantsTitle == 'הנתבעות' ? 'חבות' : 'חבים')));
                $to = $prosecutorsTitle == 'התובע' ? 'לו' : ($prosecutorsTitle == 'התובעת' ? 'לה' : ($prosecutorsTitle == 'התובעים' ? 'להם' : ($prosecutorsTitle == 'התובעות' ? 'להן' : 'להם')));
                $states = $prosecutorsTitle == 'התובע' ? 'מצהיר' : ($prosecutorsTitle == 'התובעת' ? 'מצהירה' : ($prosecutorsTitle == 'התובעים' ? 'מצהירים' : ($prosecutorsTitle == 'התובעות' ? 'מצהירות' : 'מצהירים')));
                $submitted = $prosecutorsTitle == 'התובע' ? 'הגיש' : ($prosecutorsTitle == 'התובעת' ? 'הגישה' : ($prosecutorsTitle == 'התובעים' ? 'הגישו' : ($prosecutorsTitle == 'התובעות' ? 'הגישו' : 'הגישו')));
                $andToCharge = $defendantsTitle == 'הנתבע' ? 'ולחייבו' : ($defendantsTitle == 'הנתבעת' ? 'ולחייבה' : ($defendantsTitle == 'הנתבעים' ? 'ולחייבם' : ($defendantsTitle == 'הנתבעות' ? 'ולחייבן' : 'ולחייבם')));

                echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                echo    $prosecutorsTitle . ' ';
                echo    $willClaim . ' כי ';
                echo    $defendantsTitle . ' ';
                echo    $owe . ' ';
                echo    $to . ' ';
                echo    'את מלוא סכום התביעה, בצירוף ריבית והצמדה כחוק, מיום הגשת התביעה ועד למועד התשלום בפועל.';
                echo '</li>';

                $clauseCount++;
                // ------ li ------ //
                echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                echo    'לבית המשפט סמכות מקומית ועניינית לדון בתביעה.';
                echo '</li>';

                $clauseCount++;
                // ------ li ------ //
                echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                echo    $prosecutorsTitle . ' ';
                echo    $states . ' ';
                echo    'כי לא ';
                echo    $submitted . ' ';
                echo    'בשנה זו יותר מחמש תביעות בבימ"ש זה.';
                echo '</li>';

                $clauseCount++;
                // ------ li ------ //
                echo '<li style="margin-bottom: ' . $rowMarginBottom . 'px;">';
                echo    'אשר על כן, מתבקש בית המשפט הנכבד לזמן את ';
                echo    $defendantsTitle . ' ';
                echo    'לדין ';
                echo    $andToCharge . ' ';
                echo    ' בסך ';
                echo    number_format($total) . ' ';
                echo    'ש"ח בצירוף הפרשי ריבית והצמדה וכן הוצאות משפט, לרבות אגרת הגשת התביעה.';
                echo '</li>';
            ?>
        </ol>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <div style="text-align: left;">_____________</div>
        <div style="text-align: left;"><?php echo $prosecutorsTitle; ?></div>
    </div>
</div>