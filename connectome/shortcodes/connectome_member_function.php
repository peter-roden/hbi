<?php if (!isset($_SESSION)) { session_start(); } ?>
<?php
	
	function connectome_member_function () {		
		
		$html = '';
		
		if (empty($_GET['id'])) {
	
			$html .= '<form id="connectome-member-desktop" class="connectome-member" enctype="multipart/form-data">';
						
				$html .= '<div class="desktop">';
					
					$html .= '<div class="row">';	
						
						$html .= '<div class="one-quarter">';
							$html .= '<div class="label">Upload Headshot*</div>';
								$html .= '<div class="field">';
									$html .= '<input type="file" id="image" name="image" onchange="readURL(this);" />';
									$html .= '<img id="image-preview" src="https://brain.harvard.edu/wp-content/uploads/choose-file.png">'; 
								$html .= '</div>';
						$html .= '</div>';								
						
						$html .= '<script type="text/javascript">';
							$html .= 'jQuery(document).ready(function($){'; 
								$html .= 'window.readURL = function(input) {';
									$html .= 'if (input.files && input.files[0]) {';
										$html .= 'var reader = new FileReader();';
										$html .= 'reader.onload = function (e) {';
											$html .= '$("#image-preview").attr("src", e.target.result);';
										$html .= '};';
										$html .= 'reader.readAsDataURL(input.files[0]);';
									$html .= '}';
								$html .= '}';
							$html .= '});';
						$html .= '</script>';
					
						$html .= '<div class="three-quarters">';
	
							$html .= '<div class="one-half extra-padding">';
								$html .= '<div class="label">First Name*</div>';
								$html .= '<div class="field"><input type="text" class="fname" id="fname" name="fname"></div>';
							$html .= '</div>';
									
							$html .= '<div class="one-half extra-padding last">';
								$html .= '<div class="label">Last Name*</div>';
								$html .= '<div class="field"><input type="text" id="lname" name="lname"></div>';
							$html .= '</div>';	
							
						$html .= '</div>';			

						$html .= '<div class="three-quarters">';
	
										
							$html .= '<div class="one-third extra-padding">';
								$html .= '<div class="label">Email*</div>';
								$html .= '<div class="field"><input type="email" id="email" name="email"></div>';
							$html .= '</div>';			
							
							$html .= '<div class="one-third extra-padding">';
							$html .= '<div class="label">Affiliation*</div>';									
							$taxonomies=get_taxonomies('',''); 
							foreach($taxonomies as $taxonomy){
								if ($taxonomy->name == 'connectome_affiliations') {
									$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
									$html .= '<select name="affiliation" id="affiliation" class="required">';
										$html .= '<option value="">Select Affiliation...</option>';
										foreach($terms as $term){
											$html .= '<option value="' . $term->name  . '">' . $term->name . '</option>';
										}
									$html .= '</select>';
								}
							}
							$html .= '</div>';
									
							$html .= '<div class="one-third extra-padding last">';
								$html .= '<div class="label">Role*</div>';
							    $taxonomies=get_taxonomies('',''); 
							    foreach($taxonomies as $taxonomy){
								    if ($taxonomy->name == 'connectome_roles') {
									    $terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
								        $html .= '<select name="role" id="role" class="required">';
											$html .= '<option value="">Select Role...</option>';
									        foreach($terms as $term){
										        $html .= '<option value="' . $term->name  . '">' . $term->name . '</option>';
									        }
								        $html .= '</select>';
								    }
							    }
							$html .= '</div>';
	
						$html .= '</div>';		
						
						$html .= '<div class="three-quarters">';
									
							$html .= '<div class="one-third extra-padding">';
								$html .= '<div class="label">Personal Email<span class="optional">OPTIONAL</span></div>';
								$html .= '<div class="field"><input type="email" id="personal_email" name="personal_email"></div>';
							$html .= '</div>';	
							
							$html .= '<div class="two-thirds extra-padding last">';
								$html .= '<div class="label"><span class="instructions">We ask for your personal email so that we may connect with you after you graduate or complete your postdoc. Providing this email is optional, and it would not be displayed on your Connectome page.</span></div>';
							$html .= '</div>';
							
						$html .= '</div>';		

						$html .= '<div class="three-quarters">';				
	
							$html .= '<div class="one-third extra-padding">';
								$html .= '<div class="label">Lab Name*</div>';
								$html .= '<div class="field"><input type="text" id="lab_name" name="lab_name"></div>';
							$html .= '</div>';
									
							$html .= '<div class="one-third extra-padding">';
								$html .= '<div class="label">Lab Location*</div>';
								$html .= '<div class="field"><input type="text" id="lab_location" name="lab_location"></div>';
							$html .= '</div>';
									
							$html .= '<div class="one-third extra-padding last">';
								$html .= '<div class="label">Lab Website URL<span class="optional">OPTIONAL</span></div>';
								$html .= '<div class="field"><input type="url" id="lab_url" name="lab_url"></div>';
							$html .= '</div>';
	
						$html .= '</div>';
									
					$html .= '</div>';
							
					$html .= '<div class="row">';
					
						$html .= '<div class="one-quarter extra-padding">';
							$html .= '<div class="label">Choose Your Research Interests*</div>';
						    $taxonomies=get_taxonomies('',''); 
						    foreach($taxonomies as $taxonomy){
							    if ($taxonomy->name == 'connectome_research_interests') {
								    $terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
							        foreach($terms as $term){
								        $html .= '<input type="checkbox" id="research_interests[]" name="research_interests[]" value="' . $term->id . '" class="required"><span class="checkbox-name">' . $term->name . '</span><br>';
							        }
							    }
						    }
						$html .= '</div>';
					
						$html .= '<div class="one-half">';
	
							$html .= '<div class="extra-padding">';
								$html .= '<div class="label">Research Interest Message*</div>';
								$html .= '<textarea id="research_message" name="research_message" row="5"></textarea>';
							$html .= '</div>';
	
							$html .= '<div class="label">Biographic Info<span class="optional">OPTIONAL</span></div>';
							$html .= '<textarea id="biographic_info" name="biographic_info" row="5"></textarea>';
					
						$html .= '</div>';
					
						$html .= '<div class="one-quarter last">';
						
							$html .= '<div class="extra-padding">';							
								$html .= '<div class="label">Personal Website URL<span class="optional">OPTIONAL</span></div>';
								$html .= '<div class="field"><input type="url" id="personal_url" name="personal_url"></div>';
							$html .= '</div>';
						
							$html .= '<div class="extra-padding">';
								$html .= '<div class="label">Pubmed Link<span class="optional">OPTIONAL</span></div>';
								$html .= '<div class="field"><input type="url" id="pubmed_link" name="pubmed_link"></div>';
							$html .= '</div>';
						
						$html .= '</div>';
				
					$html .= '</div>';	
					
					$html .= '<div class="row acceptance">';
					
						$html .= '<input type="checkbox" id="acceptance" name="acceptance" value="Y" class="required">';
						$html .= 'I give permission for my information to be shared with the Harvard Community. I understand I have the ability to change and/or delete my information at any time.  In addition, I understand the information I add is stored only in the HBI Connectome, changes to my Connectome profile, including my name, status and other details do not change my HarvardKey or words to that effect. OR SOMETHING...';
				
					$html .= '</div>';		
								
					$html .= '<div class="row last-row">';
														
						$html .= '<div class="buttons">';
							$html .= '<div class="submit"><input type="submit" value="Submit"></div>';
							$html .= '<div class="submit"><a id="clear" onclick="clearForm(this);">Clear</div>';
							$html .= '<div class="submit grey"><a id="clear">Delete</div>';
						$html .= '</div>';
						
						$html .= '<script type="text/javascript">';
							$html .= 'jQuery(document).ready(function($){'; 
								$html .= 'window.clearForm = function(input) {';
									$html .= '$("#connectome-member-desktop").trigger("reset");';
									$html .= '$("#connectome-member-desktop label").removeClass("error");';
									$html .= '$("#connectome-member-desktop label[id*=-error]").text("");';
									$html .= '$("#image-preview").attr("src","https://brain.harvard.edu/wp-content/uploads/choose-file.png");';
								$html .= '}';
							$html .= '});';
						$html .= '</script>';						
					
					$html .= '</div>';			
					
				$html .= '</div>';	
	
			$html .= '</form>';	
			
			$html .= '<div id="errors"></div>';	
			$html .= '<div id="response" class="response" style="display:none">';
				// $html .= '<p>Your submission is uploading.  It may take a few moments.  Please be patient...</p>';
			$html .= '</div>';	
			$html .= '<div id="member-results"></div>';	 

		} else {
			
			$id = $_GET['id'];
			
			$args = array(
				'p' 			=> $id,
			    'post_type' 	=> 'any'
			);
		
			$query = new WP_Query($args);
			
			$count = 0;
			if ($query->have_posts()) {
				while($query->have_posts()) {
					$query->the_post();
					
					$html .= '<form id="connectome-member-desktop" class="connectome-member" enctype="multipart/form-data">';
							
					$html .= '<div class="desktop">';
						
						$html .= '<div class="row">';	
							
							$html .= '<div class="one-quarter">';
								$html .= '<div class="label">Upload Headshot*</div>';
									$html .= '<div class="field">';
										$html .= '<input type="file" id="image" name="image" onchange="readURL(this);" />';
										$html .= '<img id="image-preview" src="' .  get_the_post_thumbnail_url($id,'full') . '">'; 
									$html .= '</div>';
							$html .= '</div>';								
							
							$html .= '<script type="text/javascript">';
								$html .= 'jQuery(document).ready(function($){'; 
									$html .= 'window.readURL = function(input) {';
										$html .= 'if (input.files && input.files[0]) {';
											$html .= 'var reader = new FileReader();';
											$html .= 'reader.onload = function (e) {';
												$html .= '$("#image-preview").attr("src", e.target.result);';
											$html .= '};';
											$html .= 'reader.readAsDataURL(input.files[0]);';
										$html .= '}';
									$html .= '}';
								$html .= '});';
							$html .= '</script>';
						
							$html .= '<div class="three-quarters">';
		
								$html .= '<div class="one-half extra-padding">';
									$html .= '<div class="label">First Name*</div>';
									$html .= '<div class="field"><input type="text" class="fname" id="fname" name="fname" value="' . get_field("fname")  . '"></div>';
								$html .= '</div>';
										
								$html .= '<div class="one-half extra-padding last">';
									$html .= '<div class="label">Last Name*</div>';
									$html .= '<div class="field"><input type="text" class="lname" id="lname" name="lname" value="' . get_field("lname")  . '"></div>';
								$html .= '</div>';	
								
							$html .= '</div>';			
	
							$html .= '<div class="three-quarters">';
		
											
								$html .= '<div class="one-third extra-padding">';
									$html .= '<div class="label">Email*</div>';
									$html .= '<div class="field"><input type="text" class="email" id="email" name="email" value="' . get_field("email")  . '"></div>';
								$html .= '</div>';			
								
								$html .= '<div class="one-third extra-padding">';
								$html .= '<div class="label">Affiliation*</div>';									
								$taxonomies=get_taxonomies('',''); 
								foreach($taxonomies as $taxonomy){
									if ($taxonomy->name == 'connectome_affiliations') {
										$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
										$affiliations = wp_get_object_terms ($id, 'connectome_affiliations');
										$html .= '<select name="affiliation" id="affiliation" class="required">';
											foreach($terms as $term){
												foreach ($affiliations as $affiliation) {
												if ($term->name == $affiliation->name) {
														$html .= '<option value="' . $term->name  . '" selected>' . $term->name . '</option>';
													} else {													
														$html .= '<option value="' . $term->name  . '">' . $term->name . '</option>';
													}
												}
											}
										$html .= '</select>';
									}
								}
								$html .= '</div>';
										
								$html .= '<div class="one-third extra-padding last">';
									$html .= '<div class="label">Role*</div>';
									$taxonomies=get_taxonomies('',''); 
									foreach($taxonomies as $taxonomy){
										if ($taxonomy->name == 'connectome_roles') {
											$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
											$roles = wp_get_object_terms ($id, 'connectome_roles');
											$html .= '<select name="role" id="role" class="required">';
												$html .= '<option value="">Select Role...</option>';
												foreach($terms as $term){
													foreach ($roles as $role) {
														if ($term->name == $role->name) {
															$html .= '<option value="' . $term->name  . '" selected>' . $term->name . '</option>';
														} else {
															$html .= '<option value="' . $term->name  . '">' . $term->name . '</option>';
														}
													}
												}
											$html .= '</select>';
										}
									}
								$html .= '</div>';
		
							$html .= '</div>';		
							
							$html .= '<div class="three-quarters">';
										
								$html .= '<div class="one-third extra-padding">';
									$html .= '<div class="label">Personal Email<span class="optional">OPTIONAL</span></div>';
									$html .= '<div class="field"><input type="email" id="personal_email" name="personal_email" value="' . get_field("personal_email")  . '"></div>';
								$html .= '</div>';	
								
								$html .= '<div class="two-thirds extra-padding last">';
									$html .= '<div class="label"><span class="instructions">We ask for your personal email so that we may connect with you after you graduate or complete your postdoc. Providing this email is optional, and it would not be displayed on your Connectome page.</span></div>';
								$html .= '</div>';
								
							$html .= '</div>';		
	
							$html .= '<div class="three-quarters">';				
		
								$html .= '<div class="one-third extra-padding">';
									$html .= '<div class="label">Lab Name*</div>';
									$html .= '<div class="field"><input type="text" id="lab_name" name="lab_name" value="' . get_field("lab_name")  . '"></div>';
								$html .= '</div>';
										
								$html .= '<div class="one-third extra-padding">';
									$html .= '<div class="label">Lab Location*</div>';
									$html .= '<div class="field"><input type="text" id="lab_location" name="lab_location" value="' . get_field("lab_location")  . '"></div>';
								$html .= '</div>';
										
								$html .= '<div class="one-third extra-padding last">';
									$html .= '<div class="label">Lab Website URL<span class="optional">OPTIONAL</span></div>';
									$html .= '<div class="field"><input type="url" id="lab_url" name="lab_url" value="' . get_field("lab_url")  . '"></div>';
								$html .= '</div>';
		
							$html .= '</div>';
										
						$html .= '</div>';
								
						$html .= '<div class="row">';
						
							$html .= '<div class="one-quarter extra-padding">';
								$html .= '<div class="label">Choose Your Research Interests*</div>';
								$taxonomies=get_taxonomies('',''); 
								foreach($taxonomies as $taxonomy){
									if ($taxonomy->name == 'connectome_research_interests') {
										$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
										$research_interests = wp_get_object_terms ($id, 'connectome_research_interests');
										foreach($terms as $term){			
											$found = FALSE;
											foreach ($research_interests as $research_interest) {	
												if ($term->name == $research_interest->name) {
													$found = TRUE;
													$html .= '<input type="checkbox" id="research_interests[]" name="research_interests[]" value="' . $term->id . '" class="required" checked><span class="checkbox-name">' . $term->name . '</span><br>';
												} 
											}
											if (!$found) {
												$html .= '<input type="checkbox" id="research_interests[]" name="research_interests[]" value="' . $term->id . '" class="required"><span class="checkbox-name">' . $term->name . '</span><br>';												
											}
										}
									}
								}
							$html .= '</div>';
						
							$html .= '<div class="one-half">';
		
								$html .= '<div class="extra-padding">';
									$html .= '<div class="label">Research Interest Message*</div>';
									$html .= '<textarea id="research_message" name="research_message" row="5">' . get_field('research_message') . ' </textarea>';
								$html .= '</div>';
		
								$html .= '<div class="label">Biographic Info<span class="optional">OPTIONAL</span></div>';
								$html .= '<textarea id="biographic_info" name="biographic_info" row="5">' . get_field('biographic_info') . '</textarea>';
						
							$html .= '</div>';
						
							$html .= '<div class="one-quarter last">';
							
								$html .= '<div class="extra-padding">';							
									$html .= '<div class="label">Personal Website URL<span class="optional">OPTIONAL</span></div>';
									$html .= '<div class="field"><input type="url" id="personal_url" name="personal_url" value="' . get_field("personal_url")  . '"></div>';
								$html .= '</div>';
							
								$html .= '<div class="extra-padding">';
									$html .= '<div class="label">Pubmed Link<span class="optional">OPTIONAL</span></div>';
									$html .= '<div class="field"><input type="url" id="pubmed_link" name="pubmed_link" value="' . get_field("pubmed_link")  . '"></div>';
							
							$html .= '</div>';
					
						$html .= '</div>';	
						
						$html .= '<div class="row acceptance">';
						
							$html .= '<input type="checkbox" id="acceptance" name="acceptance" value="Y" class="required">';
							$html .= 'I give permission for my information to be shared with the Harvard Community. I understand I have the ability to change and/or delete my information at any time.  In addition, I understand the information I add is stored only in the HBI Connectome, changes to my Connectome profile, including my name, status and other details do not change my HarvardKey or words to that effect. OR SOMETHING...';
					
						$html .= '</div>';		
									
						$html .= '<div class="row last-row">';
															
							if ($id) { $html .= '<input type="hidden" id="id" name="id" value="' . $id . '">'; }
													
							$html .= '<div class="buttons">';
								$html .= '<div class="submit"><input type="submit" value="Submit"></div>';
								$html .= '<div class="submit"><a id="clear" onclick="clearForm(this);">Clear</div>';
								$html .= '<div class="submit grey"><a id="delete">Delete</div>';
							$html .= '</div>';
							
							$html .= '<script type="text/javascript">';
								$html .= 'jQuery(document).ready(function($){'; 
									$html .= 'window.clearForm = function(input) {';
										$html .= '$("#connectome-member-desktop").trigger("reset");';
										$html .= '$("#connectome-member-desktop label").removeClass("error");';
										$html .= '$("#connectome-member-desktop label[id*=-error]").text("");';
										$html .= '$("#image-preview").attr("src","https://brain.harvard.edu/wp-content/uploads/choose-file.png");';
									$html .= '}';
								$html .= '});';
							$html .= '</script>';	
						
							$html .= '<script type="text/javascript">'; 
								$html .= 'jQuery(document).ready(function($) {';	
									$html .= '$("#delete").click(function() {';
										$html .= '$("body").loadingModal({'; 
											$html .= 'position: "auto",';
											$html .= 'text: "Deleting your Connectome profile. This may take a moment or so.  Please wait...",';
											$html .= 'color: "#4498AF",';
											$html .= 'opacity: "0.7",';
											$html .= 'backgroundColor: "rgb(0,0,0)",';
											$html .= 'animation: "fadingCircle"';
										$html .= '});';
										$html .= 'var id = "' . $_GET['id'] . '";';
										$html .= 'var data = {';
											$html .= "'action': 'connectome_member_delete',";
											$html .= "'id': id,";
											$html .= "'nonce': frontEndAjax.nonce";
										$html .= '};';
										    $html .= '$.post(frontEndAjax.ajaxurl, data, function(result) {';
												$html .= '$("body").loadingModal("destroy");';
												$html .= 'window.location.href = "http://brain.harvard.edu/connectome/member/";';
		 									$html .= '});';
										$html .= 'return false;';
									$html .= '});';		
								$html .= '});';
							$html .= '</script>'; 													
						
						$html .= '</div>';			
						
					$html .= '</div>';	
		
				$html .= '</form>';	
				
				$html .= '<div id="errors"></div>';	
				$html .= '<div id="response" class="response" style="display:none">';
					// $html .= '<p>Your submission is uploading.  It may take a few moments.  Please be patient...</p>';
				$html .= '</div>';	
				$html .= '<div id="member-results"></div>';	 
	
				}				
			}
					
					
		}
						
	
		$html .= '<script type="text/javascript">'; 
			
			$html .= 'jQuery(document).ready(function($){'; 
		
				$html .= '$.validator.addMethod("filesize", function (value, element, param) {';
					$html .= 'return this.optional(element) || (element.files[0].size <= param)';
				$html .= '}, function(size){';
					$html .= 'return "Please enter a smaller image. Maximum size is " + filesize(size,{exponent:2,round:1}) + ".";';
				$html .= '});';
    
			    $html .= '$("#connectome-member-desktop").validate({';
			        $html .= 'rules: {';
			            $html .= 'image: {';
			                if (empty($_GET['id'])) { $html .= 'required: true,'; }
							$html .= 'extension: "png|jpg|jpeg",';
							$html .= 'filesize: 5000 * 1024';
			            $html .= '},';
			            $html .= 'fname: {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'lname: {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'email: {';
			                $html .= 'required: true,';
			                $html .= 'email: true';
			            $html .= '},';
			            $html .= 'affiliation: {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'role: {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'personal_email: {';
			                $html .= 'email: true';
			            $html .= '},';
			            $html .= 'lab_name: {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'lab_location: {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'labsite_url: {';
			                $html .= 'url: true';
			            $html .= '},';
			            $html .= '"research_interests[]": {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'research_message: {';
			                $html .= 'required: true';
			            $html .= '},';
			            $html .= 'personal_url: {';
			                $html .= 'url: true';
			            $html .= '},';
			            $html .= 'pubmed_url: {';
			                $html .= 'url: true';
			            $html .= '},';
			            $html .= 'acceptance: {';
			                $html .= 'required: true';
			            $html .= '}';
			        $html .= '},';
			        $html .= 'messages: {';
			            $html .= 'image: {';
							if (empty($_GET['id'])) { $html .= 'required: "Please enter your Headshot.",'; }
			                $html .= 'extension: "Please enter a PNG or JPEG image.",';
			                $html .= 'filesize: "Please enter a smaller image.  Maximum size is 5MB."';
			            $html .= '},';
			            $html .= 'fname: {';
			                $html .= 'required: "Please enter your First Name."';
			            $html .= '},';
			            $html .= 'lname: {';
			                $html .= 'required: "Please enter your Last Name."';
			            $html .= '},';
			            $html .= 'email: {';
			                $html .= 'required: "Please enter your Email Address.",';
			                $html .= 'email: "Please enter a valid Email Address."';
			            $html .= '},';
			            $html .= 'affiliation: {';
			                $html .= 'required: "Please enter your Affiliation."';
			            $html .= '},';
			            $html .= 'role: {';
			                $html .= 'required: "Please enter your Role."';
			            $html .= '},';
			            $html .= 'personal_email: {';
			                $html .= 'email: "Please enter a valid email address."';
			            $html .= '},';
			            $html .= 'lab_name: {';
			                $html .= 'required: "Please enter your Lab Name."';
			            $html .= '},';
			            $html .= 'lab_location: {';
			                $html .= 'required: "Please enter your Lab Location."';
			            $html .= '},';
			            $html .= 'labsite_url: {';
			                $html .= 'url: "Please enter a valid URL."';
			            $html .= '},';
			            $html .= '"research_interests[]": {';
			                $html .= 'required: "Please enter your Research Interests."';
			            $html .= '},';
			            $html .= 'research_message: {';
			                $html .= 'required: "Please enter your Research Message."';
			            $html .= '},';
			            $html .= 'personal_url: {';
			                $html .= 'url: "Please enter a valid URL."';
			            $html .= '},';
			            $html .= 'pubmed_url: {';
			                $html .= 'url: "Please enter a valid URL."';
			            $html .= '},';
			            $html .= 'acceptance: {';
			                $html .= 'required: "Please indicate your Acceptance of the Terms & Conditions."';
			            $html .= '} ';       
			        $html .= '},';
					$html .= 'errorPlacement: function(label, element) {';
						$html .= 'label.addClass("error-message");';
						$html .= 'element.parent().append(label);';
					$html .= '},';
					$html .= 'submitHandler: function(form) {';
						$html .= '$("#response").show();';
						$html .= '$("body").loadingModal({'; 
							$html .= 'position: "auto",';
							$html .= 'text: "Submitting your Connectome profile. This may take a moment or so.  Please wait...",';
							$html .= 'color: "#4498AF",';
							$html .= 'opacity: "0.7",';
							$html .= 'backgroundColor: "rgb(0,0,0)",';
							$html .= 'animation: "fadingCircle"';
						$html .= '});';
					    $html .= 'var formData = new FormData(form);';
 						$html .= 'formData.append("action", "connectome_member_form");';
 						$html .= 'formData.append("nonce", frontEndAjax.nonce);';
						$html .= 'formData.append("image", $("#image")[0].files[0]);';
					    $html .= '$.ajax({';
					    	$html .= 'url: frontEndAjax.ajaxurl,';
					        $html .= 'type: "POST",';
					        $html .= 'data:  formData,';
						    $html .= 'mimeType: "multipart/form-data",';
						    $html .= 'contentType: false,';
						    $html .= 'processData: false,';
					        $html .= 'success: function(data){';
								$html .= '$("body").loadingModal("destroy");';
					            $html .= 'window.location.href = "https://brain.harvard.edu/connectome/success/";';
					        $html .= '}';        
					    $html .= '});';
						$html .= 'return false;';
					$html .= '}';		    
			    $html .= '});';

			$html .= '});';
		$html .= '</script>';   
	
	return $html;


	}
?>
