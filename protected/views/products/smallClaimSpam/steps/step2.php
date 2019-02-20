<div class="section bb-gray m-b-15">
    <div class="section-text m-b-15">לאן נשלחו הודעות הספאם?</div>
    <div class="checkbox-list m-b-14" data-name="relations">
        <input type="text" name="email" class="input-221 email-input" placeholder="כתובת האימייל" />
        <label>
            <input type="checkbox" data-toggles="email-input" />
            <span>לאימייל</span>
        </label>
        <input type="text" name="phone" class="input-221 phone-input" placeholder="מספר הנייד" />
        <label>
            <input type="checkbox" data-toggles="phone-input" />
            <span>לנייד</span>
        </label>
    </div>
    <div class="email-input m-b-17">
        <div class="section-text m-t-17">
            האם אי פעם מסרת לנתבע/ת את כתובת האימייל שלך?
            <select name="gaveEmail">
                <option value="no">לא</option>
                <option value="yes">כן</option>
            </select>
        </div>
        <div class="section-text m-t-14 display-none if-yes">
 האם נתת הסכמה מפורשת בכתב ומראש לנתבע/ת לשלוח לך את הודעת הספאם לאימייל?
            <select name="agreedEmail">
                <option value="no">לא</option>
                <option value="yes">כן</option>
            </select>
        </div>
    </div>
    <div class="phone-input m-b-17">
        <div class="section-text m-t-17">
            האם אי פעם מסרת לנתבע/ת את כתובת הנייד שלך?
            <select name="gavePhone">
                <option value="no">לא</option>
                <option value="yes">כן</option>
            </select>
        </div>
        <div class="section-text m-t-14 display-none if-yes">
            האם נתת הסכמה מפורשת בכתב ומראש לנתבע/ת לשלוח לך את הודעת הספאם לנייד?
            <select name="agreedPhone">
                <option value="no">לא</option>
                <option value="yes">כן</option>
            </select>
        </div>
    </div>
</div>
<div class="section">
    <div class="section-sub-title">פירוט ההודעות</div>
    <div class="recurring bb-gray" data-name="messages" data-default="1" data-min-items="1">
        <div class="recurring-sample m-b-15" data-for="messages">
            <div class="section-text m-b-15 all-vertical-top">
                <span>באיזה תאריך התקבלה ההודעה?</span>
                <input type="text" name="date" readonly="readonly" class="dp text-center past-only" placeholder="בחר/י תאריך" />
                <span>לאן?</span>
                <select name="toWhere">
                    <option value="phone">לנייד</option>
                    <option value="email">לאימייל</option>
                </select>

                <div class="upload-area inline-block" data-name="screenShots" data-limit="1">
                    <div class="button inline-block upload-button">העלה צילום מסך</div>
                    <div class="files fs-16 short"></div>
                </div>

                <div class="outline-button m-r-22 square recurring-remove-button" data-for="messages">-</div>
            </div>
        </div>
        <div class="recurring-add-button cp m-t-17 m-b-17" data-for="messages">
            <span class="section-text m-r-10">הוסף הודעה</span>
            <div class="outline-button square">+</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-sub-title m-t-17">האם ביקשת שיפסיקו לשלוח לך הודעות? (פעולת "הסר")</div>
    <div class="recurring" data-name="removalRequests" data-default="1" data-min-items="1">
        <div class="recurring-sample m-b-15 m-t-14" data-for="removalRequests">
            <div class="section-text m-b-15 all-vertical-top">
                באיזה תאריך ביקשת "הסר"?
                <input type="text" name="date" readonly="readonly" class="dp text-center past-only" placeholder="בחר/י תאריך" />

                <div class="upload-area inline-block" data-name="screenShots" data-limit="1">
                    <div class="button inline-block upload-button">העלה צילום מסך</div>
                    <div class="files fs-16 short"></div>
                </div>

                <label>
                    <input type="checkbox" name="moreMessagesCame" />
                    האם המשיכו להגיע הודעות לאחר מכן?
                </label>
                <div class="outline-button m-r-22 square recurring-remove-button" data-for="removalRequests">-</div>
            </div>
        </div>
        <div class="recurring-add-button cp m-t-17 m-b-17" data-for="removalRequests">
            <span class="section-text m-r-10">הוסף תאריך</span>
            <div class="outline-button square">+</div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.email-input, .phone-input').each(function() {
            var $select = $(this).find('select');
            var $ifYes = $(this).find('.if-yes');

            $select.on('change', function() {
                $(this).val() == 'yes' ? $ifYes.slideDown() : $ifYes.slideUp();
            })

            setTimeout(function() {
                $select.val() == 'yes' && $ifYes.show();
            }, 100);
        });
    });
</script>