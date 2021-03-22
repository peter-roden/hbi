<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">				
				
				<div class="et_pb_row et_pb_row_0">
					<div class="neurotopics-landing">
						<div class="et_pb_column et_pb_column_3_4 et_pb_css_mix_blend_mode_passthrough">
							<div class="et_pb_module et_pb_text et_pb_text_align_left">
								<div class="et_pb_text_inner">
									<div class="breadcrumb"><a class="breadcrumb" href="https://brain.harvard.edu">HOME</a> </div>
									<div class="label"><?php printf( __( 'Search Results for "%s"', 'Divi' ), '<span>' . get_search_query() . '</span>' ); ?></span></div>
								</div>
							</div>
						</div>
						<div class="et_pb_column et_pb_column_1_4 et_pb_css_mix_blend_mode_passthrough et-last-child">
							<div class="et_pb_module et_pb_text et_pb_text_align_right ">
								<div class="et_pb_text_inner">
									<div class="search-label">' '</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
<!--
				
				
				
			
					<?php if (is_search() ) : ?>
						<header class="blog-title">
							<h1><?php printf( __( 'Search Results for "%s"', 'Divi' ), '<span>' . get_search_query() . '</span>' ); ?></span></h1>
						</header>
					<?php endif; ?>	
-->
				
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					$post_format = et_pb_post_format(); ?>
				
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_pb_post_main_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					et_divi_post_format_content();

					if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
						if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
							printf(
								'<div class="et_main_video_container">
									%1$s
								</div>',
								$first_video
							);
						elseif ( ! in_array( $post_format, array( 'gallery' ) ) && 'on' === et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
							<a class="entry-featured-image-url" href="<?php the_permalink(); ?>">
								<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
							</a>
					<?php
						elseif ( 'gallery' === $post_format ) :
							et_pb_gallery_images();
						endif;
					} ?>

				<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) : ?>
					<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) : ?>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>

					<?php
						et_divi_post_meta();

						if ( 'on' !== et_get_option( 'divi_blog_style', 'false' ) || ( is_search() && ( 'on' === get_post_meta( get_the_ID(), '_et_pb_use_builder', true ) ) ) ) {
							truncate_post( 270 );
						} else {
							the_content();
						}
					?>
				<?php endif; ?>

					</article> <!-- .et_pb_post -->
			<?php
					endwhile;

					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>