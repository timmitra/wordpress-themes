<?php
/**
 * The custom one-page front page, kicks in automatically
 */

get_header(); ?>

<?php
global $more;
?>

	<div id="primary" class="content-area mtjc-page">
		<main id="main" class="site-main" role="main">

                    <section id="podcasts">
                            <div class="indent clear">
                                    <?php 
                                    $query = new WP_Query( 'pagename=podcast' );
                                    $podcasts_id = $query->queried_object->ID;

                                    // The Loop
                                    if ( $query->have_posts() ) {
                                            while ( $query->have_posts() ) {
                                                    $query->the_post();
                                                    $more = 0;
                                                    echo '<h2 class="section-title">MTJC ' . get_the_title() . '</h2>';
                                                    echo '<div class="entry-content">';
                                                    the_content();
                                                    echo '</div>';
                                            }
                                    }

                                    /* Restore original Post Data */
                                    wp_reset_postdata();

                                    $args = array(
                                            /*'post_type' => 'page',
                                            'post_parent' => $podcasts_id,*/
                                            'posts_per_page' => 6,
                                            'category_name' => 'podcasts'
                                    );
                                    $podcasts_query = new WP_Query( $args );

                                    // The Loop
                                    if ( $podcasts_query->have_posts() ) {

                                        echo '<hr />';
                                            echo '<ul class="podcasts-list">';
                                            while ( $podcasts_query->have_posts() ) {
                                                    $podcasts_query->the_post();
                                                    $more = 0;
                                                    echo '<li class="clear">';
                                                    echo '<p>';
                                                    echo '<a href="' . get_permalink() . '" title="Learn more about ' . get_the_title() . '">';
                                                    echo '<h3 class="podcasts-title">' . get_the_title() . '</h3>';
                                                    echo '</a>';
                                                    echo '<div class="podcasts-lede">';
                                                    the_content();
                                                    /*the_excerpt('Read More...');*/
                                                    echo '</div>';
                                                    echo '</p>';
                                                    echo '</li>';
                                                    
                                            }
                                            echo '</ul>';
                                    }

                                    /* Restore original Post Data */
                                    wp_reset_postdata();
                                    ?>
                            </div><!-- .indent -->
                    </section><!-- #podcasts -->
                    
                    <section id="testimonials">
                        <div class="indent">
                            <?php
                            $args = array(
                                'posts_per_page' => 3,
                                'orderby' => 'rand',
                                'category_name' => 'testimonials'
                            );
                            
                            $query = new WP_query( $args );
                            // The Loop
                            if ( $query->have_posts() ) {
                                    echo '<ul class="testimonials">';
                                    while ( $query->have_posts() ) {
                                            $query->the_post();
                                            $more = 0;
                                            echo '<li class="clear">';
                                            echo '<figure class="testimonial-thumb">';
                                            the_post_thumbnail('testimonial-mug');
                                            echo '</figure>';
                                            echo '<aside class="testimonial-text">';
                                            echo '<h3 class="testimonial-name">' . get_the_title() . '</h3>';
                                            echo '<div class="testimonial-excerpt">';
                                            the_content('');
                                            echo '</div>';
                                            echo '</aside>';
                                            echo '</li>';
                                    }
                                    echo '</ul>';
                            }

                            /* Restore original Post Data */
                            wp_reset_postdata();
                            ?>
                        </div>
                    </section>
                    

                    <section id="meet">
                            <div class="indent clear">
                                    <?php 
                                    $query = new WP_Query( 'pagename=about' );
                                    // The Loop
                                    if ( $query->have_posts() ) {
                                            while ( $query->have_posts() ) {
                                                    $query->the_post();
                                                    echo '<h2 class="section-title">' . get_the_title() . '</h2>';
                                                    echo '<div class="entry-content">';
                                                    the_content();
                                                    echo '</div>';
                                            }
                                    }

                                    /* Restore original Post Data */
                                    wp_reset_postdata();
                                    ?>
                            </div><!-- .indent -->
                    </section><!-- #meet -->

                    <section id="contact">
                            <div class="indent clear">
                                    <?php 
                                    $query = new WP_Query( 'pagename=contact' );
                                    // The Loop
                                    if ( $query->have_posts() ) {
                                            while ( $query->have_posts() ) {
                                                    $query->the_post();
                                                    echo '<h2 class="section-title">' . get_the_title() . '</h2>';
                                                    echo '<div class="entry-content">';
                                                    the_content();
                                                    echo '</div>';
                                            }
                                    }

                                    /* Restore original Post Data */
                                    wp_reset_postdata();
                                    ?>
                            </div><!-- .indent -->
                    </section><!-- #contact -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
