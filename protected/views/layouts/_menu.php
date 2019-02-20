<?php
    $items = array();

    $items[] = array('label' => 'View Site', 'icon' => 'fa-eye', 'url' => Yii::app()->createUrl(''));
    $items[] = array('label' => 'Products', 'icon' => 'fa-shopping-cart', 'url' => Yii::app()->createUrl('product/index'));
    $items[] = array('label' => 'Requests', 'icon' => 'fa-hand-pointer-o', 'url' => Yii::app()->createUrl('request/index'));
    $items[] = array('label' => 'Contacts', 'icon' => 'fa-address-book', 'url' => Yii::app()->createUrl('contact/index'));
    $items[] = array('label' => 'Sign Out', 'icon' => 'fa-sign-out', 'url' => Yii::app()->createUrl('public/logout'));

    echo MenuRenderer::render($items);
?>

<script>
    $(document).ready(function () {
        $('#side-menu').metisMenu();
    });
</script>
