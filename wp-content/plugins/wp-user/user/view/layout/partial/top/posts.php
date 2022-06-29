<div class="wpuser_posts" id="wpuser_posts">
  <div class="row">
    <?php
    global $current_user;

    $user_id = (isset($_GET['user_id']) && !empty($_GET['user_id'])) ? $_GET['user_id'] : $current_user->ID;


        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'author'	=> $user_id ,
        );

        $post_query = new WP_Query($args);
    if($post_query->have_posts() ) {
      while($post_query->have_posts() ) {
        $post_query->the_post();
        ?>
        <div id="post-<?php echo get_the_ID();?>" class="wpuser-post col-sm-6 col-md-4 col-xl-3 mb-3">
        <a href="/docs/4.1/examples/album/">
          <?php if ( has_post_thumbnail() ) {
               $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),  'post-thumbnail',true);
               $post_thumbnail = $image[0];
             }else{
               $post_thumbnail = WPUSER_PLUGIN_ASSETS_URL.'/images/wpuser-plus-no-post-gray-color.jpg';
             }
            ?>
          <img class="img-thumbnail mb-3" src="<?php echo  $post_thumbnail ?>" alt="Album screenshot" width="960" height="600">
          <h2 title="wpuser_title"><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>
        </a>
        <div class="entry wpuser_content">
        <?php   echo wp_trim_words(get_the_content(), 10, '...' ); ?>
        </div>
        <p class="text-right">
        <a href="<?php the_permalink() ?>" target="_blank">Read more </a>
      </p>
      </div>
        <?php
      }
    }else{
      ?>
        <p class="row col-md-offset-1">
      <?php _e('No Posts Found','wpuser'); ?>
    </p>
      <?php

    }
    ?>

  </div>

</div>
