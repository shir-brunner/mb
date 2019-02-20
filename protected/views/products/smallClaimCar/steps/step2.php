<div class="section-group m-b-24">
    <div class="section">
        <div class="section-title m-b-13">רכב פוגע</div>
        <div class="m-b-14">
            <input type="text" data-validations="required" name="hittingCarType" class="full-width" placeholder="סוג הרכב" />
        </div>
        <div class="m-b-14">
            <input type="text" data-validations="required" name="hittingCarNumber" class="full-width" placeholder="מספר הרכב" />
        </div>
        <div class="m-b-14">
            <input type="text" name="hittingCarPolicy" class="full-width" placeholder="מספר פוליסת צד ג' של הרכב הפוגע" />
        </div>
    </div>
    <div class="section">
        <div class="section-title m-b-13">רכב נפגע</div>
        <div class="m-b-14">
            <input type="text" data-validations="required" name="damagedCarType" class="full-width" placeholder="סוג הרכב" />
        </div>
        <div class="m-b-14">
            <input type="text" data-validations="required" name="damagedCarNumber" class="full-width" placeholder="מספר הרכב" />
        </div>
    </div>
</div>
<div class="section bt-gray">
    <div class="section-text m-t-24 m-b-24 text-center">
        באיזה תאריך אירעה התאונה?
        <input type="text" name="accidentDate" readonly="readonly" class="input-130 text-center cp dp past-only" placeholder="בחר/י תאריך">
        באיזו שעה אירעה התאונה?
        <input type="text" name="accidentTime" class="input-130 text-center" placeholder="HH:MM">
    </div>
</div>
<div class="section-group bt-gray p-t-24">
    <div class="section">
        <div>
            <textarea name="moreFacts" class="height-149 full-width" placeholder="עובדות נוספות"></textarea>
        </div>
    </div>
    <div class="section">
        <div>
            <textarea name="accidentDescription" class="height-149 full-width" placeholder="תיאור התאונה"></textarea>
        </div>
    </div>
</div>