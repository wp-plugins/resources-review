<?php get_header(); ?>
<?php global $post; ?>
<div id="resources">
    <article id="post-<?php the_ID(); ?>" <?php post_class('section group'); ?>>
        <div id="resource-thumnial" class="resource-col span_1_of_3">
            <?php 
            if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                the_post_thumbnail('large');
            } 
            ?>
        </div>
      
        <div class="resource-col span_2_of_3">
            <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3><br />
              <?php        
            $allRatings = get_post_meta( $post->ID, '_cmb_repeat_group', true );
            $items = array();
            foreach ( (array) $allRatings as $key => $avRating ) { 
                if ( isset( $avRating['rating'] ) )
                    $items[] = esc_html( $avRating['rating'] );
            }
            $average = array_sum($items) / count($items); 
        ?>
        <p>Overall Rating:<span class="stars"><?php echo $average; ?></span></p>
            <div id="resource-content">
                <?php the_content(); ?>
            </div>
        </div>
        
        <div class="resource-categories">
            <?php 
            
            $entries = get_post_meta( $post->ID, '_cmb_repeat_group', true );
          
            foreach ( (array) $entries as $key => $entry ) {
            
                $img = $title = $desc =  $rating = '';
            
                if ( isset( $entry['title'] ) )
                    $title = esc_html( $entry['title'] );
                    
                if ( isset( $entry['rating'] ) )
                    $rating = esc_html( $entry['rating'] );
            
                if ( isset( $entry['description'] ) )
                    $desc = wpautop( $entry['description'] );
            
                if ( isset( $entry['image_id'] ) ) {            
                    $img = wp_get_attachment_image( $entry['image_id'], 'share-pick', null, array(
                        'class' => 'small',
                    ) );
                } ?>
                <div class="section group">
                    <?php if ( isset( $entry['image_id'] ) ) {  ?>
                    <div class="resource-col span_1_of_4">
                        <?php echo $img; ?>
                    </div>
                    <?php } ?>
                    
                    <div class="resource-col span_<?php if ( isset( $entry['image_id'] ) ) { echo "3"; } else { echo "4"; } ?>_of_4">
                        <h3><?php echo $title; ?></h3><br />
                        <p><?php echo $title; ?> Rating:<span class="stars"><?php echo $rating; ?></span></p>
                        <?php echo $desc; ?>
                    </div>
                </div>
             <?php } ?>
        </div>
    </article>
</div>
<?php get_footer(); ?>