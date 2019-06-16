<?php
$grid_number = $attributes['grid'];
?>
<div class="ip-gallery-item <?php echo 'grid-' . absint($grid_number) ?>"
     data-post-id="<?php echo esc_attr($data['id']) ?>">

    <img src="<?php echo esc_url($data['thumbnail']) ?>"
         class="gallery-image" alt="">

    <div class="ip-gallery-item-type" style="visibility: hidden;">

        <span class="visually-hidden"> <?php esc_html_e('Gallery', 'mb-instagram-pack'); ?></span><i class="fa fa-clone"
                                                                                                     aria-hidden="true"></i>

    </div>
    <?php

    $hide_post_like = $attributes['hide_like_count'];

    $hide_comment_count = $attributes['hide_comment_count'];

    ?>
    <div class="ip-gallery-item-info">


        <ul>
            <?php
            if ('no' === $hide_post_like) {
                ?>

                <li class="ip-gallery-item-likes"><span
                            class="visually-hidden"><?php echo esc_html('Likes', 'mb-instagram-pack') ?>:</span><i
                            class="fa fa-heart"
                            aria-hidden="true"></i>
                    <?php echo absint($data['likes']) ?>
                </li>
            <?php }
            if ('no' === $hide_comment_count) {
                ?>
                <li class="ip-gallery-item-comments"><span
                            class="visually-hidden"><?php echo esc_html('Comments', 'mb-instagram-pack') ?>:</span><i
                            class="fa fa-comment"
                            aria-hidden="true"></i> <?php echo absint($data['comments']) ?>
                </li>
            <?php } ?>
        </ul>

        <a class="link" href="<?php echo esc_url_raw($data['link']) ?>" target="_blank"></a>

    </div>

</div>