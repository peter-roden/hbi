<?php

// Enqueue Connectome theme scripts 
function connectome_theme_scripts() {	
	wp_enqueue_script( 'connectomejs',  get_stylesheet_directory_uri() . '/connectome/js/connectome.js', array('jquery'), true );
	wp_enqueue_style ('connectomecss',  get_stylesheet_directory_uri() . '/connectome/style.css'); 	      
}
add_action( 'wp_enqueue_scripts', 'connectome_theme_scripts' );

// Connectome shortcodes 
include(get_stylesheet_directory() . '/connectome/shortcodes.php');

// Member Form
function connectome_member_form() { 

	check_ajax_referer('ajax_nonce', 'nonce');

	$html 	= '';

	$id 			= trim(htmlentities(strip_tags($_POST['id'])));
	$fname			= trim(htmlentities(strip_tags($_POST['fname'])));
	$lname			= trim(htmlentities(strip_tags($_POST['lname'])));
	$email			= trim(htmlentities(strip_tags($_POST['email'])));
	$affiliation	= trim(htmlentities(strip_tags($_POST['affiliation'])));
	$role			= trim(htmlentities(strip_tags($_POST['role'])));
	$personal_email	= trim(htmlentities(strip_tags($_POST['personal_email'])));
	$lab_name		= trim(htmlentities(strip_tags($_POST['lab_name'])));
	$lab_location	= trim(htmlentities(strip_tags($_POST['lab_location'])));
	$lab_url		= trim(htmlentities(strip_tags($_POST['lab_url'])));
	$research_interests	= $_POST['research_interests'];
	$research_message	= trim(htmlentities(strip_tags($_POST['research_message'])));
	$biographic_info	= trim(htmlentities(strip_tags($_POST['biographic_info'])));
	$personal_url	= trim(htmlentities(strip_tags($_POST['personal_url'])));
	$pubmed_link	= trim(htmlentities(strip_tags($_POST['pubmed_link'])));
	$acceptance		= trim(htmlentities(strip_tags($_POST['acceptance'])));

	$title = $fname . ' ' . $lname;
	if (!$id) {
		$post = array(
			'post_title'    => $title,
			'post_status'   => 'pending',
			'post_type'   	=> 'hbi_connectome'
		);
		$post_id = wp_insert_post($post);
	} else {
		$post_id = $id;
		wp_update_post(array(
			'ID'    		=>  $post_id,
			'post_status'   =>  'pending'
		));
	}
	
	update_field('fname', $fname, $post_id );
	update_field('lname', $lname, $post_id );
	update_field('email', $email, $post_id );
	update_field('personal_email', $personal_email, $post_id );	
	update_field('lab_name', $lab_name, $post_id );		
	update_field('lab_location', $lab_location, $post_id );		
	update_field('lab_url', $lab_url, $post_id );	
	update_field('research_message', $research_message, $post_id );	
	update_field('biographic_info', $biographic_info, $post_id );
	update_field('personal_url', $personal_url, $post_id );
	update_field('pubmed_link', $pubmed_link, $post_id );
	update_field('acceptance', $acceptance, $post_id );
	
	$a = get_term_by('name', $affiliation, 'connectome_affiliations');
	$a_id = (int) $a->term_id;	
	wp_set_object_terms ($post_id, $a_id,'connectome_affiliations');
		
	$r = get_term_by('name', $role, 'connectome_roles');
	$r_id = (int) $r->term_id;	
	wp_set_object_terms ($post_id, $r_id,'connectome_roles');
		
	foreach ($research_interests as $research_interest) {
		$ri_ids[] = (int) $research_interest;
	}
	wp_set_object_terms ($post_id, $ri_ids,'research_interests');
	
// echo $affiliation . '<br>';
// echo '<pre>';
// print_r($a_ids);
// echo '</pre>';	
// echo $role . '<br>';
// echo '<pre>';
// print_r($r_ids);
// echo '</pre>';	
// echo '<pre>';
// print_r($research_interests);
// echo '</pre>';	
// echo '<pre>';
// print_r($ri_ids);
// echo '</pre>';	


	if ($_FILES['image']['size'] > 0) {
	
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	
		$attach_id = media_handle_upload('image', $post_id);
		update_post_meta($post_id,'_thumbnail_id',$attach_id);
		set_post_thumbnail($post_id, $attach_id);
	
	}

	$post_link = admin_url( 'post.php?post='. $post_id .'&action=edit', 'https' ); 
	
	$to = 'peter.roden@gmail.com, michael@epitomestudio.com';
	$subject = 'Connectome Member Uploaded for Review';
	$body = $title . ' was submitted for review (' . $post_link . ')';	
	$headers[] = 'From: Connectome <connectome@brain.harvard.edu>';
	$headers[] = 'bcc: peter.roden@gmail.com';
	wp_mail($to, $subject, $body, $headers );


	$html .= '<div class="response">';
		$html .= 'Thank you.  Your submission will be reviewed and posted.';
	$html .= '</div>';	

	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_connectome_member_form', 'connectome_member_form' );
add_action( 'wp_ajax_connectome_member_form', 'connectome_member_form' );

// Member Delete
function connectome_member_delete() { 

	check_ajax_referer('ajax_nonce', 'nonce');

	$html 	= '';

	if(!empty($_POST)) {
		$id = trim(htmlentities(strip_tags($_POST['id'])));
		$result = wp_delete_post($id);
	}

	$html .= '<div class="response">';
		$html .= 'Thank you.  Your Connectome profile has been deleted.';
	$html .= '</div>';	

	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_connectome_member_delete', 'connectome_member_delete' );
add_action( 'wp_ajax_connectome_member_delete', 'connectome_member_delete' );

// Display Connectome Alphabetical
function display_connectome_alphabetical(){ 
	
	check_ajax_referer('ajax_nonce', 'nonce');
	
	$html 	= '';
	$letter = esc_html($_POST['letter']);
	$count 	= 0;

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
		while( $the_query->have_posts() ) {
			$the_query->the_post(); 
		
			$first_letter = substr(get_field(lname),0,1);
			if ($letter == $first_letter) {	$count++; }
			if ($letter == 'ALL') { $count++; }			
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
		while( $the_query->have_posts() ) {
			$the_query->the_post(); 

			$permalink 	= get_permalink();
			
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
		
			$first_letter = substr(get_field(lname),0,1);
			if ($letter == $first_letter) {		
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);		
			}
			if ($letter == 'ALL') {
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);
			}
		}			
		
		echo '</div>';
	
		$html .= paginate_landing_rows ('connectome-search-results');

	}

	
	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_display_connectome_alphabetical', 'display_connectome_alphabetical' );
add_action( 'wp_ajax_display_connectome_alphabetical', 'display_connectome_alphabetical' );

// Display Connectome Research
function display_connectome_research_area() { 
	
	check_ajax_referer('ajax_nonce', 'nonce');
	
	$html 			= '';
	$research_area 	= esc_html($_POST['research_area']);
	$count 			= 0;

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'search_name',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
	
			$research_area_found = FALSE;
			$term_array = array();
			$terms = get_field('research_areas');
			if($terms){
				foreach( $terms as $term ) {
					$term_array = get_term_by('id', $term, 'research_areas');
					if ($term_array->name == $research_area) {$research_area_found = TRUE; }
				}
			}

			if ($research_area_found) { $count++; }

		}			

	}
	
	$html .= '<div class="count">Displaying ' . $count . ' search results below.</div>';

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'search_name',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
	
			$research_area_found = FALSE;
			$term_array = array();
			$terms = get_field('research_areas');
			if($terms){
				foreach( $terms as $term ) {
					$term_array = get_term_by('id', $term, 'research_areas');
					if ($term_array->name == $research_area) {$research_area_found = TRUE; }
				}
			}

			if ($research_area_found) {
			
				$results	= TRUE;
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
			
				$first_letter = substr(get_field(search_name),0,1);
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);
			}
		}
		
		$html .= paginate_landing_rows ('connectome-search-results');			

	}
	
	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_display_connectome_research_area', 'display_connectome_research_area' );
add_action( 'wp_ajax_display_connectome_research_area', 'display_ connectome_research_area' );


// Display Connectome Research
function display_connectome_research() { 
	
	check_ajax_referer('ajax_nonce', 'nonce');
	
	$html 				= '';
	$research_group 	= $_POST['research_group'];
	$count				= 0;

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();

			$research_interests 	= wp_get_object_terms (get_the_id(), 'connectome_research_interests');	
			
			$research_groups_found 	= FALSE;		
			foreach ($research_interests as $research_interest) {
				if (strcmp(htmlspecialchars_decode($research_group), htmlspecialchars_decode($research_interest->name)) == 0) {
					$research_groups_found = TRUE;
				} 
			}
			if ($research_groups_found) { $count++; }
		}			
	}
	wp_reset_query();
		
	if ($count > 0) {
		$html .= '<div class="count">Displaying ' . $count . ' search results below.</div>';
	} else {
		$html .= '<div class="count">No results found.</div>';
	}

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
	
			$research_interests 	= wp_get_object_terms (get_the_id(), 'connectome_research_interests');	
			
			$research_groups_found 	= FALSE;		
			foreach ($research_interests as $research_interest) {
				if (strcmp(htmlspecialchars_decode($research_group), htmlspecialchars_decode($research_interest->name)) == 0) {
					$research_groups_found = TRUE;
				} 
			}
			if ($research_groups_found) { $count++; }

			if ($research_groups_found) {
			
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
				
				$first_letter = substr(get_field(search_name),0,1);
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);

			}
		}			

		$html .= paginate_landing_rows ('connectome-search-results');			

	}
	wp_reset_query();
	
	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_display_connectome_research', 'display_connectome_research' );
add_action( 'wp_ajax_display_connectome_research', 'display_connectome_research' );


// Display Connectome Affiliation
function display_connectome_affiliation() { 
	
	check_ajax_referer('ajax_nonce', 'nonce');
	
	$html 				= '';
	$affiliation_group 	= str_replace("\'", '', $_POST['affiliation_group']);
	$count				= 0;

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();

			$affiliation_groups_found = FALSE;
			$affiliations = wp_get_object_terms (get_the_id(), 'connectome_affiliations');			
			foreach ($affiliations as $affiliation) {	
				if (strcmp(htmlspecialchars_decode($affiliation_group), htmlspecialchars_decode($affiliation->name)) == 0) {
					$affiliation_groups_found = TRUE;
				} 
			}
			if ($affiliation_groups_found) { $count++; }
		}			
	}
	wp_reset_query();
			
	if ($count > 0) {
		$html .= '<div class="count">Displaying ' . $count . ' search results below.</div>';
	} else {
		$html .= '<div class="count">No results found.</div>';
	}

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
	
			$affiliation_groups_found = FALSE;
			$affiliations = wp_get_object_terms (get_the_id(), 'connectome_affiliations');			
			foreach ($affiliations as $affiliation) {	
				if (strcmp(htmlspecialchars_decode($affiliation_group), htmlspecialchars_decode($affiliation->name)) == 0) {
					$affiliation_groups_found = TRUE;
				} 
			}

			if ($affiliation_groups_found) {
			
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
				
				$first_letter = substr(get_field(search_name),0,1);
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);

			}
		}			

		$html .= paginate_landing_rows ('connectome-search-results');			

	}
	wp_reset_query();
	
	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_display_connectome_affiliation', 'display_connectome_affiliation' );
add_action( 'wp_ajax_display_connectome_affiliation', 'display_connectome_affiliation' );


// Display Connectome Affiliation
function display_connectome_role() { 
	
	check_ajax_referer('ajax_nonce', 'nonce');
	
	$html 				= '';
	$role_group		 	= str_replace("\'", '', $_POST['role_group']);
	$count				= 0;

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();

			$role_groups_found = FALSE;
			$roles = wp_get_object_terms (get_the_id(), 'connectome_roles');			
			foreach ($roles as $role) {	
				if (strcmp(htmlspecialchars_decode($role_group), htmlspecialchars_decode($role->name)) == 0) {
					$role_groups_found = TRUE;
				} 
			}
			if ($role_groups_found) { $count++; }
		}			
	}
	wp_reset_query();
			
	if ($count > 0) {
		$html .= '<div class="count">Displaying ' . $count . ' search results below.</div>';
	} else {
		$html .= '<div class="count">No results found.</div>';
	}

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
	
			$role_groups_found = FALSE;
			$roles = wp_get_object_terms (get_the_id(), 'connectome_roles');			
			foreach ($roles as $role) {	
				if (strcmp(htmlspecialchars_decode($role_group), htmlspecialchars_decode($role->name)) == 0) {
					$role_groups_found = TRUE;
				} 
			}

			if ($role_groups_found) {
			
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
				
				$first_letter = substr(get_field(search_name),0,1);
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);

			}
		}			

		$html .= paginate_landing_rows ('connectome-search-results');			

	}
	wp_reset_query();
	
	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_display_connectome_role', 'display_connectome_role' );
add_action( 'wp_ajax_display_connectome_role', 'display_connectome_role' );


// Display Text Search
function display_connectome_search_text() { 
	
	check_ajax_referer('ajax_nonce', 'nonce');
	
	$html 	= '';
	$search = esc_html($_POST['search']);
	$count  = 0;

	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);

	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		$results = FALSE;
		while($query->have_posts()) {
			$query->the_post();
			
			$found = FALSE;
			if (stripos(get_field('fname'),$search) > -1) 				{ $found = TRUE; }
			if (stripos(get_field('lname'),$search) > -1) 				{ $found = TRUE; }
			if (stripos(get_field('lab_name'),$search) > -1) 			{ $found = TRUE; }
			if (stripos(get_field('lab_location'),$search) > -1) 		{ $found = TRUE; }
			if (stripos(get_field('research_message'),$search) > -1) 	{ $found = TRUE; }
			if (stripos(get_field('biographic_info'),$search) > -1) 	{ $found = TRUE; }
			
			if ($found) { $count++; }
			
		}
		
	}
		
	if ($count > 0) {
		$html .= '<div class="count">Displaying ' . $count . ' search results below.</div>';
	} else {
		$html .= '<div class="count">No results found.</div>';
	}
		
	$args = array(
		'post_type'			=> 'hbi_connectome',
		'posts_per_page' 	=> -1,
		'post_status' 		=> 'publish',
		'meta_key'			=> 'lname',
		'orderby'			=> 'meta_value',
		'order'				=> 'ASC'
	);
	
	$query = new WP_Query($args);

	if ($query->have_posts()) {
		while($query->have_posts()) {
			$query->the_post();
	
			$found = FALSE;
			if (stripos(get_field('fname'),$search) > -1) 				{ $found = TRUE; }
			if (stripos(get_field('lname'),$search) > -1) 				{ $found = TRUE; }
			if (stripos(get_field('lab_name'),$search) > -1) 			{ $found = TRUE; }
			if (stripos(get_field('lab_location'),$search) > -1) 		{ $found = TRUE; }
			if (stripos(get_field('research_message'),$search) > -1) 	{ $found = TRUE; }
			if (stripos(get_field('biographic_info'),$search) > -1) 	{ $found = TRUE; }
	
			if ($found) {
			
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
				
				$first_letter = substr(get_field(search_name),0,1);
				$html .= connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink);

			}
		}			
	
		$html .= paginate_landing_rows ('connectome-search-results');			
	
	}
	wp_reset_query();
	// if (!$found) {$html .= '<br><br><span style="color:#000000">No results found for "' . $search . '".</span><br><br><br>';}

	echo $html;
	die();

}
add_action( 'wp_ajax_nopriv_display_connectome_search_text', 'display_connectome_search_text' );
add_action( 'wp_ajax_display_connectome_search_text', 'display_connectome_search_text' );

// Display Text Search
function connectome_display_row ($image, $name, $role_lab_affiliation, $excerpt, $permalink) { 
	
	$html = '';
    
	$html .= '<div class="row">';

		$html .= '<div class="et_pb_column et_pb_column_1_5 et_pb_css_mix_blend_mode_passthrough">';
		    $html .= '<div class="et_pb_module et_pb_image">';
		        $html .= $image;
		    $html .= '</div>';
	    $html .= '</div>';
		
		$html .= '<div class="et_pb_column et_pb_column_4_5 et_pb_css_mix_blend_mode_passthrough et-last-child">';
		    $html .= '<div class="et_pb_module et_pb_text et_pb_text_align_left">';
		        $html .= '<div class="et_pb_text_inner">';
					$html .= '<div class="name">' . $name  . '</div>';
					$html .= '<div class="lab">' . $role_lab_affiliation  . '</div>';
					$html .= '<div class="excerpt">' . $excerpt  . '</div>';
					$html .= '<div class="learn-more"><a href="' . $permalink . '" target="_blank">LEARN MORE &gt;</a></div>';
			    $html .= '</div>';
		    $html .= '</div>';
	    $html .= '</div>';
    
	$html .= '</div>';
	
	return $html;
	
}

// Connectome Single Page - Redirect to correct page for pagination
add_action( 'template_redirect', function() {
	if ( is_singular( 'hbi_connectome' ) ) {
		global $wp_query;
		$page = ( int ) $wp_query->get( 'page' );
		if ( $page > 1 ) {
			$wp_query->set( 'page', 1 );
			$wp_query->set( 'paged', $page );
		}
		remove_action( 'template_redirect', 'redirect_canonical' );
	}
}, 0 ); 

function connectome_pagination_link( $label = NULL, $dir = 'next', WP_Query $query = NULL ) {
	if ( is_null( $query ) ) {
		$query = $GLOBALS['wp_query'];
	}
	$max_page = ( int ) $query->max_num_pages;
	if ( $max_page <= 1 ) {
		return;
	}
	$paged = ( int ) $query->get( 'paged' );
	if ( empty( $paged ) ) {
		$paged = 1;
	}
	$target_page = $dir === 'next' ?  $paged + 1 : $paged - 1;
	if ( $target_page < 1 || $target_page > $max_page ) {
		return;
	}
	if ( null === $label ) {
		$label = __( 'Next Page &raquo;' );
	}

	$label = preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label );
	printf( '<a href="%s">%s</a>', get_pagenum_link( $target_page ), esc_html( $label ) );
}
