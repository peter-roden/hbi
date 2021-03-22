<?php if(!isset($_SESSION)) { session_start(); } ?>
<?php
	
	function connectome_search_all_function (){
		
		$count = 0;
		
		$args = array(
			'post_type'			=> 'hbi_connectome',
			'posts_per_page'	=> -1,
			'post_status' 		=> 'publish'
		);

		
		$the_query = new WP_Query($args);
	
		if( $the_query->have_posts() ) {			
			while( $the_query->have_posts() ) {
				$the_query->the_post(); 
				$count++;
			}
		}
		
		$html .= '<div class="count">Displaying ' . $count . ' search results below.</div>';

		$args = array(
			'post_type'			=> 'hbi_connectome',
			'posts_per_page'	=> -1,
			'post_status' 		=> 'publish',
			'meta_key'			=> 'lname',
			'orderby'			=> 'meta_value',
			'order'				=> 'ASC'
		);
		
		$the_query = new WP_Query($args);
	
		if( $the_query->have_posts() ) {
			
			$html .= '<div id="connectome-display-row">';
			
			while( $the_query->have_posts() ) {
				$the_query->the_post(); 
												
				$permalink 	= get_permalink();

				$image 		= get_field('image');
				$size 		= array("150","150");						
				$thumbnail 	= get_the_post_thumbnail_url (get_the_ID(), $size);
				$image		= '<span class="et_pb_image_wrap">' . 
								  '<a ' . 'href="' . $permalink . '">' . 
									  '<img src="' . $thumbnail . '">' . 
								  '</a>' . 
							  '</span>';
				
				$name	= get_field('fname') . ' ' . get_field('lname');
				$terms = wp_get_object_terms (get_the_id(), 'connectome_roles');
				foreach ($terms as $roles) { $role = $roles->name; }
				$terms = wp_get_object_terms (get_the_id(), 'connectome_affiliations');
				foreach ($terms as $affiliations) { $affiliation = $affiliations->name; }
				$lab = '<a href="' . get_field('lab_url') . '">' . get_field('lab_name') . '</a>';
				$role_lab_affiliation = $role . ' / ' . $lab . ' / ' . $affiliation; 
				$excerpt = substr(get_field('research_message'), 0, 300) . '...';	
	
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);				
					
			}
			
			$html .= '</div>';
			
			$html .= paginate_landing_rows ('connectome-display-row');
			
		}

		
		wp_reset_query();

	return $html;

	}
?>