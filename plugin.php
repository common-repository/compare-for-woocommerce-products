<?php
/**
* Plugin Name: Compare For WooCommerce Products

* Plugin URI: https://www.phoeniixx.com/product/compare-for-woocommerce-products/

* Description: This is a simple plugin which let your customers do the comparison between two or more products on a common popup window.

* Version: 1.1.1

* Text Domain: phoen_woo_compare

* Author: Phoeniixx

* Author URI: http://www.phoeniixx.com/

* License: GPL2

* WC requires at least: 2.6.0

* WC tested up to: 3.9.1
*/ 
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	session_start();
	
	add_action( 'wp_head', 'phoen_woo_compare_front_design_enqueue_script' );
	
	function phoen_woo_compare_front_design_enqueue_script(){
		
		wp_enqueue_style('phoen-front-page-style',plugin_dir_url(__FILE__).'assets/css/front_page.css'); 	
		
	}
	
	add_action('admin_head','phoen_backend_scripts');
	
	function phoen_backend_scripts(){
		
		wp_enqueue_script( 'jquery-ui-sortable' );
		
	}
	
	add_action('admin_menu', 'phoen_main_menu');
		
	function phoen_main_menu(){

		add_menu_page('Compare','Compare','manage_options','phoen_compare','',plugin_dir_url(__FILE__).'assets/images/logo.png',23);
	
		add_submenu_page( 'phoen_compare','Compare','compare','manage_options','phoen_compare','phoen_compare_function');
	
	} 
	
	function phoen_compare_function(){
		
		$phoen_data=(array)get_option('phoen_save_settings');
		
		$list_attributes=(array)get_option('list_attributes');
		
		$count_attr=count($list_attributes);
		
		 $button_type=$phoen_data['button_type'];
		 
		$button_text=$phoen_data['button_text'];
		
		$btn_on_single_page=$phoen_data['btn_on_single_page'];
		
		$btn_on_shop_page=$phoen_data['btn_on_shop_page'];
		
		$table_title_text=$phoen_data['table_title_text'];
		
		$show_field=$phoen_data['show_field'];

		$image_size=$phoen_data['image_size'];

		?>
		<div class="wrap">		
		
			<h1 class="phoen_head_text"><?php _e('General Settings','phoen_woo_compare'); ?></h1>
			
			<form novalidate="novalidate" method="post">	
			
				<table class="form-table">
				
					<tbody>
						<tr>
							<th scope="row"><label for="default_role"><?php _e('Link or Button','phoen_woo_compare'); ?></label></th>
							<td>
								<?php wp_nonce_field( 'save_settings', 'phoen_save_settings' ); ?>
								<select id="default_role" name="button_type">
									<option value="button" <?php if($button_type=='button'){echo 'selected'; } ?> ><?php _e('Button','phoen_woo_compare'); ?></option>
									<option value="link"	<?php if($button_type=='link'){echo 'selected'; } ?> ><?php _e('link','phoen_woo_compare'); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="default_role"><?php _e('Link/Button text','phoen_woo_compare'); ?></label></th>
							<td>
								<input type="text" class="regular-text" value="<?php echo $button_text;?>" id="blogname" name="button_text">
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="default_role"><?php _e('Show button in single product page','phoen_woo_compare'); ?></label></th>
							<td>
								<label for="users_can_register">
									<input type="checkbox" value="yes" name="btn_on_single_page" <?php if($btn_on_single_page=='yes'){echo 'checked'; } ?> />
									<?php _e(' Say if you want to show the button in the single product page.','phoen_woo_compare'); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="default_role"><?php _e('Show button in products list','phoen_woo_compare'); ?></label></th>
							<td>
								<label for="users_can_register">
									<input type="checkbox" value="yes" name="btn_on_shop_page" <?php if($btn_on_shop_page=='yes'){echo 'checked'; } ?> />
									<?php _e(' Say if you want to show the button in the products list.','phoen_woo_compare'); ?>
								</label>
							</td>
						</tr>											
					</tbody>
					
				</table>
				<hr>
				<h1 class="phoen_head_text"><?php _e('Table Settings','phoen_woo_compare'); ?></h1>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label for="default_role"><?php _e('Table title','phoen_woo_compare'); ?></label></th>
								<td>
									<label for="users_can_register">
										<input type="text" value="<?php if($table_title_text==''){echo 'Compare Now'; }else{ echo $table_title_text;} ?>" name="table_title_text" />
										<span class="description"><?php _e('Type the text to use for the table title.','phoen_woo_compare'); ?></span>
									</label>
								</td>
							</tr>	
							<tr>
								<th scope="row"><label for="default_role"><?php _e('Fields to show','phoen_woo_compare'); ?></label></th>
								<td>
									<p class="description"><?php _e('Select the fields to show in the comparison table (are included also the woocommerce attributes)','phoen_woo_compare'); ?></p>	
									<label for="users_can_register">
										<ul>
											<li>
											
												<input type="hidden" value="remove" name="show_field[remove]" />
												
												<input type="checkbox"  value="checked"  name="show_field[image]" <?php  if($show_field['image']=='checked'){ echo 'checked'; } ?> /><?php _e('Image','phoen_woo_compare'); ?>
											</li>
											<li>
												<input type="checkbox"  value="checked"  name="show_field[title]" <?php  if($show_field['title']=='checked'){ echo 'checked'; } ?> /><?php _e('Title','phoen_woo_compare'); ?>
											</li>
											<li>
												<input type="checkbox" value="checked" name="show_field[price]" <?php if($show_field['price']=='checked'){ echo 'checked'; } ?>  /><?php _e('Price','phoen_woo_compare'); ?>
											</li>
											<li>
												<input type="checkbox"  value="checked"  name="show_field[add_cart]" <?php if($show_field['add_cart']=='checked'){ echo 'checked' ;} ?> /><?php _e('Add to cart','phoen_woo_compare'); ?>
											</li>
											<li>
												<input type="checkbox"  value="checked" name="show_field[description]" <?php if($show_field['description']=='checked'){ echo 'checked' ;} ?> /><?php _e('Description','phoen_woo_compare'); ?>
											</li>
											<li>
												<input type="checkbox"  value="checked" name="show_field[quantity]" <?php if($show_field['quantity']=='checked'){ echo 'checked' ;} ?> /><?php _e('Availability','phoen_woo_compare'); ?>
											</li>
											<li>
												<input type="checkbox"  value="checked" name="show_field[weight]" <?php if($show_field['weight']=='checked'){ echo 'checked'; } ?> /><?php _e('Weight','phoen_woo_compare'); ?>
											</li>
											<li>
												<input type="checkbox" value="checked" name="show_field[dimensions]" <?php if($show_field['dimensions']=='checked'){ echo 'checked'; } ?> /><?php _e('Dimensions','phoen_woo_compare'); ?>
											</li>
										
											<?php
											for($i=0;$i<=$count_attr-1;$i++){
												?>
												<li>
												
													<input type="checkbox" value="checked" name="show_field[<?php echo $list_attributes[$i];?>]"  <?php if($show_field[$list_attributes[$i]]=='checked'){ echo 'checked' ;} ?> /><?php echo $list_attributes[$i];?>
													
												</li>	
												<?php	
											}
											?>
										</ul>
										
									</label>
									
								</td>
								
							</tr>	
							
						</tbody>
						
					</table>
				
				<p class="submit">
				
					<input id="submit" class="button button-primary" type="submit" value="Save Changes" name="save_setting" />
					
					<input id="submit" class="button button-primary" type="submit" value="Reset" name="reset" />
				
				</p>
				
			</form>
			
		</div>	
		<?php
		if(isset( $_POST['phoen_save_settings'])  && wp_verify_nonce( $_POST['phoen_save_settings'], 'save_settings' ) ){
			
			 $button_type=sanitize_text_field($_POST['button_type']);
			 
			$button_text=sanitize_text_field($_POST['button_text']);
			
			$btn_on_single_page=sanitize_text_field($_POST['btn_on_single_page']);
			
			$btn_on_shop_page=sanitize_text_field($_POST['btn_on_shop_page']);
			
			$table_title_text=sanitize_text_field($_POST['table_title_text']);
			
			$show_field=$_POST['show_field'];
			
			$data=array(
			'button_type'=>$button_type,
			'button_text'=>$button_text,
			'btn_on_single_page'=>$btn_on_single_page,
			'btn_on_shop_page'=>$btn_on_shop_page,
			'table_title_text'=>$table_title_text,
			'show_field'=>$show_field,
			);
			
			update_option('phoen_save_settings',$data);
			
			echo "<script type='text/javascript'>window.location.reload();</script>"; 
			
		}
		
	}
	$data=(array)get_option('phoen_save_settings');
	
	 $btn_on_single_page=$data['btn_on_single_page'];
	
	 
	
	if($btn_on_single_page=='yes'){
		add_action('woocommerce_after_add_to_cart_button', 'phoen_for_shop_page');	
	}
	$btn_on_shop_page=$data['btn_on_shop_page'];	
	if($btn_on_shop_page=='yes'){
		add_action('woocommerce_after_shop_loop_item', 'phoen_for_shop_page');
	}
	function phoen_for_shop_page(){
		
		session_start();
		
		$attributes = wc_get_attribute_taxonomies();
		
		foreach($attributes as $key =>$value){ 
		
		  $list_attributes[]=$attributes[$key]->attribute_name;	
		  
		}
		 update_option('list_attributes',$list_attributes); 
				
		$data=(array)get_option('phoen_save_settings');

		$show_field=$data['show_field'];
	
		$btn_type=$data['button_type'];
		
		$table_title_text=$data['table_title_text'];
		
		$button_text=$data['button_text'];
		
		$id=get_the_ID();
		
		$post=get_post_meta($id);
		
		echo '<div class="phoen_parent">';
		
			if($btn_type=='button'){	
			
				echo '<input type="hidden" value="'.$id.'">';
				
					?><input type="button" class="id" value="<?php if(empty($button_text)){ echo 'Compare'; }else{ echo $button_text; } ?>"> <?php
				
			}elseif($btn_type=='link'){
				
				echo '<input type="hidden" value="'.$id.'">';
				
				?><a href="javascript:void(0)" class="id"><?php if(empty($button_text)){ echo 'Compare'; }else{ echo $button_text; } ?></a><?php

			}
			
			?>
			<div class="phoen_product_details" id="<?php echo $id;?>" >
			<div class="phoen_cross_upper">
				<h3><?php if(empty($table_title_text)){ echo 'Compare Table'; }else{ echo $table_title_text; }?></h3>
				
			</div>	
			<div class="phoe_show_data_cross"><?php _e('X','phoen_woo_compare'); ?></div>
				<?php
				$test_data=$_SESSION['phoen_compare_data'];
				
				if(empty($test_data[0]) && count($test_data)==1){
					
					unset($test_data[0]);
					
					$test_data=array_values($test_data);	
					
					$_SESSION['phoen_compare_data']=$test_data;
					
				}
				?>
				<div id="phoen_upper_table">
				<table class="phoen_table">
				<tbody>
				<?php 
				
				$phoen_data=$_SESSION['phoen_compare_data'];
				
				foreach($show_field  as $key=>$value){
					?><tr class="phoen_<?php echo $key;?>"><th><?php echo $key;?></th><?php
					if(!empty($phoen_data)){
						foreach($phoen_data as $ke=>$val){
							if($key=='remove'){
								?><td class="product_<?php echo $val['id'];?>">
									<input type="hidden" value="<?php echo $val['id'];?>" />
									<input type="button" value="X" class="remove_compare" />
									</td>
								<?php
							}elseif($key=='image'){
								?><td class="product_<?php echo $val['id'];?>">
								<div class="phoen_img_div">
									<?php echo $val['image'];?>
								</div>
								</td>
								<?php
							}elseif($key=='title'){
								?><td class="product_<?php echo $val['id'];?>">
									<span><?php echo $val['title']; ?></span></td>
									<?php
							}elseif($key=='price'){
								?><td class="product_<?php echo $val['id'];?>">
									<del><?php if(!empty($val['regular'])){ echo '₹'.$val['regular']; } ?></del>
									<span><?php  if(!empty($val['price'])){ echo '₹'.$val['price']; }else{ echo $val['variation_price_html']; }?></span></td>
									<?php
							}elseif($key=='add_cart'){
								?><td class="product_<?php echo $val['id'];?>">
									<div class="phoen_add_to_cart"><?php if($val['type']=='single'){?><a class="phoen_add_cart_btn button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $val['id'];?>" data-quantity="1" href="/?add-to-cart=<?php echo $val['id'];?>" rel="nofollow"><?php _e('Add to cart','phoen_woo_compare'); ?></a><?php }else{ ?><a class="phoen_add_cart_btn button product_type_variable add_to_cart_button" data-product_id="<?php echo $val['id'];?>" data-quantity="1" href="<?php echo $val['url'];?>" rel="nofollow"><?php _e('Select options','phoen_woo_compare'); ?></a><?php } ?> </div>
									</td><?php
							}elseif($key=='description'){
								?><td class="product_<?php echo $val['id'];?>">
										<p><?php echo $val['description'];?></p>
								</td><?php
							}elseif($key=='quantity'){
								?><td class="product_<?php echo $val['id'];?>">
										<p><?php if(!empty($val['quantity'])){ echo $val['quantity'].' in stock'; }else{echo 'N/A'; }?></p>
								</td><?php
							}elseif($key=='weight'){
								?><td class="product_<?php echo $val['id'];?>">
										<p><?php if(!empty($val['weight'])){ echo $val['weight'].' gram'; }else{echo 'N/A'; }?></p>
								</td><?php
							}elseif($key=='dimensions'){
								?><td class="product_<?php echo $val['id'];?>">
										<p><?php if(!empty($val['dimensions'])){ echo $val['dimensions'];}else{echo 'N/A'; } ?></p>
								</td><?php
							}else{
								
								$attribute=$val['attributes'];
								if(!empty($attribute[$key])){
									$attr=implode(',', array_values($attribute[$key]));
									?><td class="product_<?php echo $val['id'];?>">
										<p><?php echo $attr; ?></p>
									</td><?php
									
								}else{
									?><td class="product_<?php echo $val['id'];?>">
											<p><?php _e('N/A','phoen_woo_compare'); ?></p>
									</td><?php
								}
								
							}
						} 
					} 
					
						
					 ?></tr><?php
				} ?></tbody></table></div>	
			</div>	
				
		</div>		
	
		<?php	
	}
	
	
	function phoen_for_product_page(){
		
		$data=(array)get_option('phoen_save_settings');
		
		$show_field=$data['show_field'];
		
		$btn_type=$data['button_type'];
		
		$id=get_the_ID();
		
		if($btn_type=='button'){	
		
			echo '<input type="hidden" value="'.$id.'">';
			
			echo '<input type="button" class="id" value="Compare">'; 
			
		}elseif($btn_type=='text'){
			
			echo '<input type="hidden" value="'.$id.'">';
			
			echo '<a href="javascript:void(0)" class="id">Compare</a>'; 
			
		}
		?>	
	
		<script type="text/javascript">
		
			jQuery('.id').click( function(){
				
				var value=jQuery(this).siblings('input:hidden').val();	
				
			});	
			
		</script>	
		<?php
	}
		
	add_action('woocommerce_after_add_to_cart_button', 'phoen_custom_js');	
	
	add_action('woocommerce_after_shop_loop', 'phoen_custom_js');
	
	function phoen_custom_js(){
		?>	
		<script type="text/javascript">
		
			jQuery(document).ready(function(){
				
				
				var value='';	
				
				jQuery('.id').click( function(){	
					
					value=jQuery(this).siblings('input:hidden').val();
					
					var phoen_ajax_add_compare = '<?php echo admin_url('admin-ajax.php') ;?>';
					
					jQuery.post(

						phoen_ajax_add_compare,
						{
						'action'	:  'phoen_add_compare',
						'data'	:  value,	
						},
						
						function(response){
							
							 var json = jQuery.parseJSON(response);
		
							if(json=== null ){
								 var no_data='';
							}else{
								
								var pricea=json.price;
								var regular=json.regular;
								var variation_price_html=json.variation_price_html;
								if( variation_price_html == ''){
									 var dams='<del>₹'+pricea+'</del><span>₹ '+regular+'</span>';
								}else{
									 var dams='<span>'+variation_price_html+'</span>';
								}
								var phoen_key='';
								  
								<?php
								$data=(array)get_option('phoen_save_settings');		
								$show_field=$data['show_field'];
								foreach($show_field as $key=>$vall){
									 
									if($key=='remove'){
										?>
										jQuery('tr.phoen_remove').append('<td class="product_'+json.id+'"><input type="button" value="X" class="remove_compare" /></td>');
										<?php
									}elseif($key=='image'){
										?>
										jQuery('tr.phoen_image').append('<td class="product_'+json.id+'"><div class="phoen_image">'+json.image+'</div></td>');
										<?php
									}elseif($key=='title'){
										?>
											jQuery('tr.phoen_title').append('<td class="product_'+json.id+'"><span>'+ json.title+'</span></td>');	
										<?php
									}elseif($key=='price'){
										?>
											jQuery('tr.phoen_price').append('<td class="product_'+json.id+'">'+ dams +'</td>');	
										<?php
									}elseif($key=='add_cart'){
										?> 
										var phoen_url=json.url;
										if (phoen_url === undefined){
											jQuery('tr.phoen_add_cart').append('<td class="product_'+json.id+'"><div class="phoen_add_to_cart"><a class="phoen_add_cart_btn button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'+json.id+'" data-quantity="1" href="/?add-to-cart='+json.id+'" rel="nofollow">Add to cart</a></div></td>');
										}else{
											jQuery('tr.phoen_add_cart').append('<td class="product_'+json.id+'"><div class="phoen_add_to_cart"><a class="phoen_add_cart_btn button product_type_variable add_to_cart_button" data-product_id="'+json.id+'" data-quantity="1" href="'+phoen_url+'" rel="nofollow">Select options</a></div></td>');
										}	
										<?php
									}elseif($key=='description'){
										?>
										jQuery('tr.phoen_description').append('<td class="product_'+json.id+'"><p>'+ json.desc +'</p></td>');	
										<?php
									}elseif($key=='quantity'){
										?>
										jQuery('tr.phoen_quantity').append('<td class="product_'+json.id+'"><p>'+ json.quantity +' in stock</p></td>');	
										<?php
									}elseif($key=='weight'){
										?>
										if(json.weight===''){
											jQuery('tr.phoen_weight').append('<td class="product_'+json.id+'"><p>N/A</p></td>');	
										}else{
											jQuery('tr.phoen_weight').append('<td class="product_'+json.id+'"><p>'+ json.weight +' gram</p></td>');
										}
										<?php
									}elseif($key=='dimensions'){
										?>
										var phoen_new_dimensions= json.dimensions;
										if(phoen_new_dimensions===undefined){
											jQuery('tr.phoen_dimensions').append('<td class="product_'+json.id+'"><p>N/A </p></td>');
										}else{
											jQuery('tr.phoen_dimensions').append('<td class="product_'+json.id+'"><p>'+phoen_new_dimensions +' </p></td>');	
										}
										<?php
									}else{
										?>
										var phoen_found_key='<?php echo $key;?>';
										
										phoen_key=json.phoen_found_key;
									
										if(phoen_key === undefined ){
											jQuery('tr.phoen_'+phoen_found_key+'').append('<td class="product_'+json.id+'"><p>N/A</p></td>');	
										}else{
											jQuery('tr.phoen_'+phoen_found_key+'').append('<td class="product_'+json.id+'"><p>'+phoen_key+'</p></td>');	
										}	 	
										<?php
									}
								}
								?>	
							}
						}
					);
					jQuery(this).parent('div').addClass('phoen_open_popup');
				
				}); 
				
				jQuery('.phoe_show_data_cross').click( function(){
					
					jQuery(this).parent().parent().removeClass('phoen_open_popup');
					location.reload();
					
					
				});
				  var pid='';
				jQuery('.remove_compare').click( function(){
					
					pid=jQuery(this).siblings('input:hidden').val();
					 var parent_class=jQuery(this).parent().attr('class');
					 jQuery('td.'+parent_class+'').fadeOut(3000).remove();	
					var phoen_ajax_del_compare = '<?php echo admin_url('admin-ajax.php') ;?>';
					
					 jQuery.post(
					
						phoen_ajax_del_compare,
						{
						'action'	:  'phoen_del_compare',
						'pid'	:  pid,	
						},
						function(response){	} 
					); 
					 if (jQuery('#phoen_upper_table > table > tbody > tr > td').length == 0){
								jQuery('table.phoen_table').css('display','none');
						 }
				})  
				jQuery(document).ajaxStop(function(){
					var pid='';
					jQuery('.remove_compare').click( function(){
						
						pid=jQuery(this).siblings('input:hidden').val();
						 var pclass=jQuery(this).parent().attr('class');
						 jQuery('td.'+pclass+'').remove();	
						var phoen_ajax_del_compare = '<?php echo admin_url('admin-ajax.php') ;?>';
						
						jQuery.post(
						
							phoen_ajax_del_compare,
							{
							'action'	:  'phoen_del_compare',
							'pid'	:  pid,	
							},
							function(response){
								 if (jQuery('#phoen_upper_table > table > tbody > tr > td').length == 0){
									 jQuery('table.phoen_table').css('display','none');
									  location.reload();
								}
							}
						); 
						
					}) 
				});
		});	
		</script>	
			<?php
	}
	
	add_action( 'wp_ajax_nopriv_phoen_del_compare', 'phoen_del_compare' );
	
	add_action( 'wp_ajax_phoen_del_compare', 'phoen_del_compare' );
	
	function phoen_del_compare(){
		
		session_start();
		
		$pid=sanitize_text_field($_POST['pid']);
		
		$phoen_compare_data=$_SESSION['phoen_compare_data'];
		
		foreach($phoen_compare_data as $key => $value){
			
			if($value['id']==$pid){
				
				 $phoen_pid=$key;

				unset($phoen_compare_data[$phoen_pid]);
			
				$phoen_finel = array_values($phoen_compare_data);
				
				$_SESSION['phoen_compare_data']=$phoen_finel;
			}
			
		}
		die();
	}
	add_action( 'wp_ajax_nopriv_phoen_add_compare', 'phoen_add_compare' );
	
	add_action( 'wp_ajax_phoen_add_compare', 'phoen_add_compare' );
	
	function phoen_add_compare(){
		
		session_start();
		
		$product_id=sanitize_text_field($_POST['data']);
		
		$list_attributes=(array)get_option('list_attributes');
		
		$phoen_compare_data=$_SESSION['phoen_compare_data'];
	
		$attributes = wc_get_attribute_taxonomies();
		 
			foreach($attributes as $key =>$value){ 
			
			  $list_attributes[]=$attributes[$key]->attribute_name;		
			  
			}
			
		$product = wc_get_product( $product_id );
		
		$price = $product->get_price();
		
		$image= $product->get_image() ;
		
		 $title=$product->get_title();
		 
		$quantity=$product->get_stock_quantity();
		
		$weight=$product->get_weight();
		
		$desc=get_post_field('post_content', $product_id);
		
		$short_desc=get_post_field('post_excerpt', $product_id);
		
		$regular=$product->get_regular_price();
		
		$wc_product_variable  =  new WC_Product_Variable( $product_id );
		
		$variation_price_html  =$wc_product_variable->get_price_html();
		
	    $phoen_url = get_permalink( $product_id );
		
		foreach($list_attributes as $key=> $value){
			
			for($i=0;$i<=count($value);$i++){
				
				if(!empty(wc_get_product_terms( $product_id, 'pa_'.$value.'' ))) {
				
					$val=wc_get_product_terms( $product_id, 'pa_'.$value.'' );
					
					$data[$value]=$val;
					
				}
				
			}
			
		}
		
		$id=0;
		
		if ($product->product_type == 'variable'){
			$finel_array=array($id=>array(
				'id'=>$product_id,
				'type'=>'variable',
				'image'=>$image,
				'title'=>$title,
				'variation_price_html'=>$variation_price_html,
				'description'=>$desc,
				'short_desc'=>$short_desc,
				'quantity'=>$quantity,
				'weight'=>$weight,
				'attributes'=>$data,
				'url'=>$phoen_url,
			));
		}else{
			$finel_array=array($id=>array(
				'id'=>$product_id,
				'type'=>'single',
				'image'=>$image,
				'title'=>$title,
				'price'=>$price,
				'regular'=>$regular,
				'description'=>$desc,
				'short_desc'=>$short_desc,
				'quantity'=>$quantity,
				'weight'=>$weight,
			));
		}
		 
		
		
		foreach($phoen_compare_data as $key => $product)
		{
			
			if ( $product['id'] === $product_id ){
			  
				$pid=$product['id'];
			  
			}
	
		}
	
		if(	empty($data)){

				$phoen_ajaxdata = array('id' => $product_id, 'image' => $image, 'title' => $title, 'price' => $price, 'regular' => $regular,'variation_price_html' => '', 'desc' => $short_desc, 'quantity' => $quantity, 'weight' => $weight);
		
		}else{	
		
			foreach($data as $num=>$kes){
				
					$attr=implode(',', array_values($kes));
					
					$phoen_attr[$num]=$attr;				
					
			}	
			$arr = array('id' => $product_id, 'image' => $image, 'type'=>'variable' ,'url'=>$phoen_url, 'title' => $title, 'variation_price_html' => $variation_price_html, 'desc' => $short_desc, 'quantity' => $quantity, 'weight' => $weight);
			
			$phoen_ajaxdata=array_merge( $arr, $phoen_attr );	
			
		}
		
		$compare= json_encode($phoen_ajaxdata);

		if(empty($phoen_compare_data)){
			
			$_SESSION['phoen_compare_data']=$finel_array;
			
			print_r($compare);
			
		}elseif(!empty($phoen_compare_data) && empty($pid)){
			
			 $phoen_fainaldata=array_merge($phoen_compare_data,$finel_array);
			 
			 $_SESSION['phoen_compare_data']=$phoen_fainaldata;
			 
			  print_r($compare);
			
		}else{
			
			$phoen_compare_data=$phoen_compare_data;
			
		}	
		
		die();
		
	}
	
}
else
{	 
	?>
		<div class="error notice is-dismissible " id="message"><p><?php _e( 'Please', 'phoen_woo_compare' ); ?><strong><?php _e( 'Activate', 'phoen_woo_compare' ); ?></strong><?php _e( ' WooCommerce Plugin First, to use it.', 'phoen_woo_compare' ); ?></p>
			<button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'phoen_woo_compare' ); ?></span></button>
		</div>
	<?php 
}  
?>
