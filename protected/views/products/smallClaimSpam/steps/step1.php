<div class="section-group">
    <div class="section">
        <div class="section-title m-b-13">צד הנתבע</div>
        <div class="m-b-17">
            <input type="text" name="defendantPhone" data-validations="required" class="input-136 m-l-8" placeholder="טלפון" />
            <input type="text" data-validations="required" name="defendantName" class="input-154" placeholder="שם מלא" />
            <select name="defendantGender">
                <option value="male">מר</option>
                <option value="female">גב.</option>
                <option value="other">אחר</option>
            </select>
        </div>
        <div class="m-b-17">
            <a class="fs-12 display-none" id="company-link" href="https://ica.justice.gov.il/GenericCorporarionInfo/SearchCorporation?unit=8" target="_blank">בדוק באתר רשם החברות</a>
            <input type="text" data-placeholder="defendantIdType" data-validations="required" name="defendantId" class="input-221" placeholder="<?php echo GxHtml::encode('תעודת זהות / ח"פ / ע.מ. / ע.ר.'); ?>" />
            <select name="defendantIdType" id="id-type">
                <option value="id">ת.ז.</option>
                <option value="company">ח.פ.</option>
                <option value="am">ע.מ.</option>
                <option value="ar">ע.ר.</option>
            </select>
        </div>
        <div>
            <input type="text" name="defendantZipCode" class="input-130" placeholder="מיקוד" />
            <input type="text" data-validations="required" name="defendantCity" class="input-130" placeholder="עיר" />
            <input type="text" data-validations="required" name="defendantStreet" class="input-130" placeholder="רחוב ומס' בית" />
        </div>
        <div class="section-text m-b-17">
            <a class="fs-12 pull-left" target="_blank" href="https://mypost.israelpost.co.il/%D7%A9%D7%99%D7%A8%D7%95%D7%AA%D7%99%D7%9D/%D7%90%D7%99%D7%AA%D7%95%D7%A8-%D7%9E%D7%99%D7%A7%D7%95%D7%93/">בדוק מיקוד בדואר ישראל</a>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="section">
        <div class="section-title m-b-13">צד התובע</div>
        <div class="m-b-17">
            <input type="text" name="prosecutorPhone" data-validations="required" class="input-136 m-l-8" placeholder="טלפון" />
            <input type="text" data-validations="required fullName" name="prosecutorName" class="input-154" placeholder="שם מלא" />
            <select name="prosecutorGender">
                <option value="male">מר</option>
                <option value="female">גב.</option>
            </select>
        </div>
        <div class="m-b-17">
            <input type="text" data-validations="required id" name="prosecutorId" class="input-427" placeholder="תעודת זהות" />
        </div>
        <div>
            <input type="text" name="prosecutorZipCode" class="input-130" placeholder="מיקוד" />
            <input type="text" data-validations="required" name="prosecutorCity" class="input-130" placeholder="עיר" />
            <input type="text" data-validations="required" name="prosecutorStreet" class="input-130" placeholder="רחוב ומס' בית" />
        </div>
        <div class="section-text m-b-17">
            <a class="fs-12 pull-left m-l-9" target="_blank" href="https://mypost.israelpost.co.il/%D7%A9%D7%99%D7%A8%D7%95%D7%AA%D7%99%D7%9D/%D7%90%D7%99%D7%AA%D7%95%D7%A8-%D7%9E%D7%99%D7%A7%D7%95%D7%93/">בדוק מיקוד בדואר ישראל</a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $idType = $('#id-type');
        var $companyLink = $('#company-link');

        $idType.on('change', function() {
            if($(this).val() == 'company')
            {
                $companyLink.show();
            }
            else
            {
                $companyLink.hide();
            }
        });
    });
</script>