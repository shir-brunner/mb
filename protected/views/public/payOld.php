<?php
    $templateTotal = intval($request->template->template_price);
    $extraInfo = $request->extraInfo();
    $discount = isset($extraInfo['discount']) ? $extraInfo['discount'] : null;

    if($discount)
    {
        $templateTotal -= ($templateTotal / 100 * intval($discount['percent']));
    }

    $totalPrice = $templateTotal;
?>
<section class="page-header page-header-xs">
    <div class="container">
        <h1 class="text-right">תשלום</h1>
    </div>
</section>

<section>
    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered table-vertical-middle" dir="rtl">
                <thead>
                    <tr>
                        <th class="text-right">פריט</th>
                        <th class="text-right">מחיר</th>
                        <th class="text-right">כמות</th>
                        <?php if($discount) { ?>
                            <th class="text-right">הנחה</th>
                        <?php } ?>
                        <th class="text-right">סה"כ לתשלום</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo GxHtml::encode($request->template->template_name); ?></td>
                        <td><?php echo number_format($request->template->template_price); ?> ₪</td>
                        <td>1</td>
                        <?php if($discount) { ?>
                            <td><span class="badge badge-primary"><?php echo $discount['percent']; ?>%-</span></td>
                        <?php } ?>
                        <td><?php echo number_format($templateTotal); ?> ₪</td>
                    </tr>
                    <?php
                        foreach($request->requestFields as $requestField)
                        {
                            if($requestField->field->field_type_id != FieldType::CHECK_BOX)
                            {
                                continue;
                            }

                            $extraInfo = $requestField->field->extraInfo();
                            if(!isset($extraInfo['chargeAmount']) || intval($extraInfo['chargeAmount']) <= 0
                                || !intval($requestField->request_field_value))
                            {
                                continue;
                            }

                            echo '<tr>';
                            echo '    <td>' . GxHtml::encode($requestField->field->field_name) . '</td>';
                            echo '    <td>' . number_format(intval($extraInfo['chargeAmount'])) . ' ₪</td>';
                            echo '    <td>1</td>';

                            $total = intval($extraInfo['chargeAmount']);
                            if($discount)
                            {
                                echo '    <td><span class="badge badge-primary">' . $discount['percent'] . '%-</span></td>';
                                $total -= $total / 100 * intval($discount['percent']);
                            }

                            echo '    <td>' . number_format($total) . ' ₪</td>';
                            echo '</tr>';

                            $totalPrice += $total;

                            if(!isset($extraInfo['addFee']) || !intval($extraInfo['addFee']) || !isset($extraInfo['feeAmount']) || intval($extraInfo['feeAmount']) <= 0)
                            {
                                continue;
                            }

                            $feeType = isset($extraInfo['feeType']) && $extraInfo['feeType'] == 'fixed' ? 'fixed' : 'percent';
                            $totalFee = 0;

                            if($feeType == 'percent')
                            {
                                $feeFields = isset($extraInfo['feeFields']) && is_array($extraInfo['feeFields']) ? $extraInfo['feeFields'] : array();
                                $feeRequestFields = array_filter($request->requestFields, function($requestField) use ($feeFields) {
                                    return in_array($requestField->field->field_name, $feeFields);
                                });

                                $sum = array_sum(array_map(function($feeRequestField) {
                                    return $feeRequestField->request_field_value;
                                }, $feeRequestFields));

                                $totalFee = $sum / 100 * intval($extraInfo['feeAmount']);
                            }
                            else if($feeType == 'fixed')
                            {
                                $totalFee = intval($extraInfo['feeAmount']);
                            }

                            echo '<tr>';
                            echo '    <td>אגרה</td>';
                            echo '    <td>' . number_format($totalFee) . ' ₪</td>';
                            echo '    <td>1</td>';

                            if($discount)
                            {
                                echo '<td></td>';
                            }

                            echo '    <td>' . number_format($totalFee) . ' ₪</td>';
                            echo '</tr>';

                            $totalPrice += $totalFee;
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="<?php echo $discount ? '3' : '2'; ?>"></td>
                        <td style="background: #f4f4f4;"><strong>סה"כ לתשלום</strong></td>
                        <td style="background: #f4f4f4;"><strong><?php echo number_format($totalPrice); ?> ₪</strong></td>
                    </tr>
                </tfoot>
            </table>

            <button class="btn btn-primary"><i class="fa fa-chevron-left"></i> לתשלום</button>
        </div>
    </div>
</section>