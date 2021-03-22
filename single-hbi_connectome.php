<?php 

get_header(); 

echo '<div id="main-content">';
	
	echo '<div class="container">';
	
		echo '<div id="content-area" class="clearfix">';
?>

	<div class="et_pb_section et_pb_section_0 et_section_regular">
		<div class="et_pb_row et_pb_row_0">
			<div class="et_pb_column et_pb_column_4_4 et_pb_column_0  et_pb_css_mix_blend_mode_passthrough et-last-child">
			<div class="et_pb_module et_pb_image et_pb_image_0">
				<span class="et_pb_image_wrap "><img loading="lazy" src="https://brain.harvard.edu/wp-content/uploads/connectome-banner.png" alt="" title="connectome-banner" height="auto" width="auto" srcset="https://brain.harvard.edu/wp-content/uploads/connectome-banner.png 2160w, https://brain.harvard.edu/wp-content/uploads/connectome-banner-1280x327.png 1280w, https://brain.harvard.edu/wp-content/uploads/connectome-banner-980x250.png 980w, https://brain.harvard.edu/wp-content/uploads/connectome-banner-480x123.png 480w" sizes="(min-width: 0px) and (max-width: 480px) 480px, (min-width: 481px) and (max-width: 980px) 980px, (min-width: 981px) and (max-width: 1280px) 1280px, (min-width: 1281px) 2160px, 100vw" class="wp-image-5926"></span>
			</div>
		</div>
		</div>
	</div>
	
	<div class="et_pb_section et_pb_section_2 et_section_regular">
		<div class="et_pb_row et_pb_row_1">
			<div class="et_pb_column et_pb_column_1_2 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough">
				<div class="et_pb_module et_pb_code et_pb_code_0">
					<div class="et_pb_code_inner">
						<?php echo do_shortcode ("[connectome-count]"); ?>
					</div>
				</div>
			</div> 
			<div class="et_pb_column et_pb_column_1_2 et_pb_column_2  et_pb_css_mix_blend_mode_passthrough et-last-child et_pb_column_empty">
			</div>
		</div>
		<div class="et_pb_row et_pb_row_2">
			<div class="et_pb_column et_pb_column_4_4 et_pb_column_3  et_pb_css_mix_blend_mode_passthrough et-last-child">
				<div class="et_pb_module et_pb_text et_pb_text_0  et_pb_text_align_left et_pb_bg_layout_light">
					<div class="et_pb_text_inner"><h1>HBI Connectome.<h1></div>
				</div> 
			</div>
		</div> 
	</div>

	<?php
		while ( have_posts() ) : the_post();
			$shortcode = '[connectome-top-buttons ' .
								'b1_label="LOGOUT" b1_link="http://brain.harvard.edu/connectome/"' . ' ' .
								'b2_label="PROFILE" b2_link="http://brain.harvard.edu/connectome/member?id=' . get_the_id() . '"' . ' ' .
								'b3_label="JOIN CONNECTOME" b3_link="http://brain.harvard.edu/connectome/"' .
						   ']';
			$profile_link = "http://brain.harvard.edu/connectome/member?id=" . get_the_id();
		endwhile; 
	?>	
	
	<div class="et_pb_section et_pb_section_4 et_section_regular">
		<div class="et_pb_row et_pb_row_4">
			<div class="et_pb_column et_pb_column_4_4 et_pb_column_5  et_pb_css_mix_blend_mode_passthrough et-last-child">
				<div class="et_pb_module et_pb_code et_pb_code_2">
					<div class="et_pb_code_inner">					
						<?php echo do_shortcode ($shortcode); ?>					
					</div>
				</div>
			</div>
		</div>
	</div>	

<?php
			while ( have_posts() ) : the_post();
										
				$size = array (333,333);
				$image = wp_get_attachment_image_url( get_post_thumbnail_id( get_the_id() ), $size);		
				if( $image ) { $image = '<img src="' . $image . '">'; }
				
				$name 				= get_field('fname') . ' ' . get_field('lname');
				$email				= '<a href="mailto:' . get_field('email') . '">' . get_field('email') . '</a>';
				$terms				= wp_get_object_terms (get_the_id(), 'connectome_roles');
				foreach ($terms as $roles) { $role = $roles->name; }
				$terms			= wp_get_object_terms (get_the_id(), 'connectome_affiliations');
				foreach ($terms as $affiliations) { $affiliation = $affiliations->name; }
				$role_affiliation	= $role . ' / ' . $affiliation; 
				$lab_name 			= get_field('lab_name');
				$websites 			= '';
				if (get_field('lab_url')) { 
					$websites .= 'Lab Website: ' . '<a href="' . get_field('lab_url') . '" target="_blank">' . get_field('lab_url') . '</a>';
				}
				if ((get_field('lab_url')) && (get_field('personal_url'))) { $websites .= ' / '; }
				if (get_field('personal_url')) { 
					$websites .= 'Personal Website: ' . '<a href="' . get_field('personal_url') . '" target="_blank">' . get_field('personal_url') . '</a>';
				}
				$lab_location 	= 'Location: ' . get_field('lab_location');
				if (get_field('pubmed_link')) {	
					$pubmed = 'PubMed Link: ' . '<a href="' . get_field('pubmed_link') . '">' . get_field('pubmed_link') .'</a>'; 
				}
				$research 		= '<span>Research Interests</span>' . '<br>' . get_field ('research_message');
				if (get_field('biographic_info')) { $bio = '<span>Personal Bio</span>' . '<br>' . get_field ('biographic_info'); }
				$research_interests = '<span>Areas of Research Interest</span>' . '<br>';
				$research_interests .= '<div class="areas">';
					$r = wp_get_object_terms (get_the_id(), 'connectome_research_interests');			
					foreach ($r as $research_interest) {	
						 $research_interests .= '<div class="area">' . $research_interest->name . '</div>';
					}
				$research_interests .= '</div>';		
				
				echo '<div class="connectome-post flex-box">';

					echo '<div class="one_third">';
						echo $image;
						echo '<div class="email">'  . $email . '</div>';
					echo '</div>';

					echo '<div class="two_third et_column_last">';
						echo '<div class="name">'  . $name . '</div>';
						echo '<div class="role-affiliation">'  . $role_affiliation . '</div>';
						echo '<div class="lab">';
							echo $lab_name . '<br>';
							echo $websites . '<br>';
							echo $lab_location;
						echo '</div>';
						if ($pubmed) { echo '<div class="pubmed">'  . $pubmed . '</div>'; }
						echo '<div class="research">'  . $research . '</div>';
						if ($bio) { echo '<div class="bio">'  . $bio . '</div>'; }
						echo '<div class="research-interests">'  . $research_interests . '</div>';
						
					echo '</div><div class="clear"></div>';

				echo '</div>';

				echo '<div id="connectome-top-buttons" class="connectome-post-return">';
					echo '<a href="https://brain.harvard.edu/connectome/">BACK TO CONNECTOME</a>'; 
				echo '</div>';
				
				echo '<div class="flex-box">';
					echo '<div class="one_third">';
						echo '&nbsp;';
					echo '</div>';
					echo '<div class="two_third et_column_last">';
						$prev = get_permalink( get_adjacent_post(false,'',false)->ID );
						$next = get_permalink( get_adjacent_post(false,'',true)->ID );					
						echo '<div class="connectome-post-nav">';
							if ($prev != get_the_permalink()) {
								echo '<div class="prev"><a class="nav" href="' . $prev . '">' . '< PREVIOUS' . '</a></div>';
							}
							if ($next != get_the_permalink()) {
								echo '<div class="next"><a class="nav" href="' . $next . '">' . 'NEXT >' . '</a></div>';
							}
						echo '</div>';
					echo '</div>';
				echo '<div>';
				
				
			endwhile; 
	?>			
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>