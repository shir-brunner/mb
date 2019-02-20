<div id="category-page">
    <div id="top-bar-space"></div>
    <section id="banner" style="background: url('<?php echo $category->category_image_url; ?>');">
        <div class="container">
            <div class="space"></div>
            <div class="text">
                <div class="header"><?php echo GxHtml::encode($category->category_name); ?></div>
                <div class="sub-header"><?php echo GxHtml::encode($category->category_description); ?></div>
            </div>
        </div>
    </section>
    <section id="steps-section">
        <div class="container">
            <div class="steps-container">
                <div class="steps">
                    <div class="step">
                        <div class="circle">3</div>
                        <div class="text">שלם ושלח לעריכה</div>
                    </div>
                    <div class="step">
                        <div class="circle">2</div>
                        <div class="text">מילוי השאלון</div>
                    </div>
                    <div class="step active">
                        <div class="circle">1</div>
                        <div class="text">בחירת מוצר</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="products">
                <?php
                foreach($category->products as $product)
                {
                    if(!$product->product_published)
                    {
                        continue;
                    }

                    echo '<a href="' . Yii::app()->createUrl('public/product', array('id' => $product->getPrimaryKey())) . '">';
                    echo '  <img class="product" src="' . $product->product_icon_url . '" data-src="' . $product->product_icon_url. '" data-hover="' . $product->product_hover_icon_url . '" />';
                    echo '</a>';
                }
                ?>
            </div>
        </div>
    </section>
</div>

<script>
    $(function() {
        $('#nav .item[href="#services"]').addClass('active');

        $('.products').on('mouseenter', '.product', function() {
            $(this).attr('src', $(this).attr('data-hover'));
        }).on('mouseleave', '.product', function() {
            $(this).attr('src', $(this).attr('data-src'));
        });
    });
</script>