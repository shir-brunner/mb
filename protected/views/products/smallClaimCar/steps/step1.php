<div class="section-group">
    <div class="section">
        <div class="section-title m-b-13">צד הנתבע</div>
        <div class="recurring" data-min-items="1" data-name="defendants">
            <div class="recurring-sample m-b-23 bb-gray" data-for="defendants">
                <div class="m-b-17">
                    <div class="outline-button square m-r-7 recurring-remove-button" data-for="defendants">-</div>
                    <input type="text" name="phone" data-validations="required" class="input-136 m-l-8" placeholder="טלפון" />
                    <input type="text" data-validations="required" name="name" class="input-154" placeholder="שם מלא" />
                    <select name="gender">
                        <option value="male">מר</option>
                        <option value="female">גב.</option>
                        <option value="other">אחר</option>
                    </select>
                </div>
                <div class="m-b-17">
                    <a class="fs-12 display-none company-link" href="https://ica.justice.gov.il/GenericCorporarionInfo/SearchCorporation?unit=8" target="_blank">בדוק באתר רשם החברות</a>
                    <input type="text" data-placeholder="idType" data-validations="required" name="id" class="input-221" placeholder="<?php echo GxHtml::encode('תעודת זהות / ח"פ / ע.מ. / ע.ר.'); ?>" />
                    <select name="idType" class="id-type">
                        <option value="id">ת.ז.</option>
                        <option value="company">ח.פ.</option>
                        <option value="am">ע.מ.</option>
                        <option value="ar">ע.ר.</option>
                    </select>
                </div>
                <div>
                    <input type="text" name="zipCode" class="input-130" placeholder="מיקוד" />
                    <input type="text" data-validations="required" name="city" class="input-130" placeholder="עיר" />
                    <input type="text" data-validations="required" name="street" class="input-130" placeholder="רחוב ומס' בית" />
                </div>
                <div class="section-text m-b-17">
                    <a class="fs-12 pull-left" target="_blank" href="https://mypost.israelpost.co.il/%D7%A9%D7%99%D7%A8%D7%95%D7%AA%D7%99%D7%9D/%D7%90%D7%99%D7%AA%D7%95%D7%A8-%D7%9E%D7%99%D7%A7%D7%95%D7%93/">בדוק מיקוד בדואר ישראל</a>
                    <div class="clearfix"></div>
                </div>
                <div class="section-text m-b-15">
                    מיהו הנתבע ביחס לרכב?
                </div>
                <div class="checkbox-list m-b-14" data-name="relations">
                    <label>
                        <input type="checkbox" data-toggles="other-text" />
                        <span>אחר</span>
                    </label>
                    <label>
                        <input type="checkbox" />
                        <span>חברת ביטוח</span>
                    </label>
                    <label>
                        <input type="checkbox" />
                        <span>נהג הרכב</span>
                    </label>
                    <label>
                        <input type="checkbox"" />
                        <span>בעל הרכב</span>
                    </label>
                </div>
                <div>
                    <textarea class="height-50 m-b-17 display-none other-text" name="otherRelation" placeholder="תאר מיהו הנתבע ביחס לרכב"></textarea>
                </div>
            </div>
            <div class="recurring-add-button cp" data-for="defendants">
                <span class="section-text m-r-10">הוסף נתבע</span>
                <div class="outline-button square">+</div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="section-title m-b-13">צד התובע</div>
        <div class="recurring" data-min-items="1" data-name="prosecutors">
            <div class="recurring-sample m-b-23 bb-gray" data-for="prosecutors">
                <div class="m-b-17">
                    <div class="outline-button square m-r-7 recurring-remove-button" data-for="prosecutors">-</div>
                    <input type="text" name="phone" data-validations="required" class="input-136 m-l-8" placeholder="טלפון" />
                    <input type="text" data-validations="required fullName" name="name" class="input-154" placeholder="שם מלא" />
                    <select name="gender">
                        <option value="male">מר</option>
                        <option value="female">גב.</option>
                    </select>
                </div>
                <div class="m-b-17">
                    <input type="text" data-validations="required id" name="id" class="input-418" placeholder="תעודת זהות" />
                </div>
                <div>
                    <input type="text" name="zipCode" class="input-130" placeholder="מיקוד" />
                    <input type="text" data-validations="required" name="city" class="input-130" placeholder="עיר" />
                    <input type="text" data-validations="required" name="street" class="input-130" placeholder="רחוב ומס' בית" />
                </div>
                <div class="section-text m-b-17">
                    <a class="fs-12 pull-left m-l-9" target="_blank" href="https://mypost.israelpost.co.il/%D7%A9%D7%99%D7%A8%D7%95%D7%AA%D7%99%D7%9D/%D7%90%D7%99%D7%AA%D7%95%D7%A8-%D7%9E%D7%99%D7%A7%D7%95%D7%93/">בדוק מיקוד בדואר ישראל</a>
                    <div class="clearfix"></div>
                </div>
                <div class="section-text m-b-15">
                    מיהו התובע ביחס לרכב?
                </div>
                <div class="checkbox-list m-b-14" data-name="relations">
                    <label>
                        <input type="checkbox" data-toggles="other-text" />
                        <span>אחר</span>
                    </label>
                    <label>
                        <input type="checkbox" />
                        <span>נהג הרכב</span>
                    </label>
                    <label>
                        <input type="checkbox"" />
                        <span>בעל הרכב</span>
                    </label>
                </div>
                <div>
                    <textarea class="height-50 m-b-17 display-none other-text" name="otherRelation" placeholder="תאר מיהו התובע ביחס לרכב"></textarea>
                </div>
            </div>
            <div class="recurring-add-button cp" data-for="prosecutors">
                <span class="section-text m-r-10">הוסף תובע</span>
                <div class="outline-button square">+</div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var $form = $('#form');
        $form.on('change', '.id-type', function() {
            var $companyLink = $(this).parents('.recurring-item').find('.company-link');

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