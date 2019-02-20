<div class="section-group">
    <div class="section">
        <div class="section-title m-b-14">מסמכים רלוונטים לתביעה</div>
        <div id="car-files" class="m-b-17">
            <div class="upload-area vt" data-name="eHagasha" data-limit="1">
                <div class="inline-block upload-button button fs-16">הוספת אישור אי הגשה</div>
                <div class="files fs-16"></div>
            </div>
            <div class="upload-area m-t-17 vt m-l-10" data-name="carLicense" data-limit="3">
                <div class="inline-block upload-button button fs-16">הוספת רשיון רכב</div>
                <div class="files fs-16"></div>
            </div>
            <div class="upload-area m-t-17 vt" data-name="appraiserReport" data-limit="1">
                <div class="inline-block upload-button button fs-16">הוספת דו"ח שמאי</div>
                <div class="files fs-16"></div>
            </div>
            <div class="upload-area m-t-17 m-l-10 vt" data-name="receipt" data-limit="1">
                <div class="inline-block upload-button button fs-16">הוספת קבלות תיקון רכב</div>
                <div class="files fs-16"></div>
            </div>
            <div class="upload-area m-t-17 m-l-10 vt" data-name="otherFiles">
                <div class="inline-block upload-button button fs-16">הוספת תמונות</div>
                <div class="files fs-16"></div>
            </div>
        </div>
        <div class="bt-gray m-t-17">
            <div class="section-text m-t-24">
                באיזו עיר תוגש התביעה?
                <input type="text" data-validations="required" name="courtCity" class="input-130 m-l-0" placeholder=""/>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="section-title m-b-14">הוצאות</div>
        <div class="section-text m-b-14">
            נזק ישיר
            <input type="number" min="0" name="directDamageCost" class="input-90" placeholder="<?php echo GxHtml::encode('סכום בש"ח'); ?>"/>
            שכ"ט שמאי
            <input type="number" min="0" name="appraiserCost" class="input-90" placeholder="<?php echo GxHtml::encode('סכום בש"ח'); ?>"/>
        </div>
        <div class="section-text m-b-14">
            הפסד ימי עבודה
            <input type="number" min="0" name="workDaysLoss" class="input-110 m-l-10" placeholder="<?php echo GxHtml::encode('כמה ימים?'); ?>"/>
            <input type="number" min="0" name="workDaysLossCost" class="input-110 m-r-0" placeholder="<?php echo GxHtml::encode('סכום בש"ח'); ?>"/>
        </div>
        <div class="section-text m-b-14">
            עוגמת נפש
            <input type="number" min="0" name="aggravationCost" class="input-90" placeholder="<?php echo GxHtml::encode('סכום בש"ח'); ?>"/>
        </div>
        <div class="recurring" data-name="moreExpenses" data-default="0">
            <div class="recurring-sample" data-for="moreExpenses">
                <div class="m-b-14">
                    <div class="outline-button recurring-remove-button square" data-for="moreExpenses">-</div>
                    <input type="number" min="0" name="cost" class="input-90" placeholder="<?php echo GxHtml::encode('סכום בש"ח'); ?>"/>
                    <input type="text" name="description" placeholder="<?php echo GxHtml::encode('תיאור ההוצאה'); ?>"/>
                </div>
            </div>
            <div class="recurring-add-button cp" data-for="moreExpenses">
                <span class="section-text m-r-10">הוצאות נוספות</span>
                <div class="outline-button square">+</div>
            </div>
        </div>
        <div class="m-t-17 bt-gray">
            <div class="section-sub-title m-t-17">פרטי התקשרות</div>
            <div class="section-text m-b-17">
                הזן את פרטיך וניצור עמך קשר כשהמסמך יהיה מוכן
            </div>
            <div class="m-b-17">
                <input type="text" name="discountCode" class="input-130" placeholder="קוד מנוי"/>
                <input type="text" name="userEmail" data-validations="required email" class="input-130" placeholder="אימייל"/>
                <input type="text" name="userName" data-validations="required fullName" class="input-130" placeholder="שם מלא"/>
            </div>
        </div>
    </div>
</div>