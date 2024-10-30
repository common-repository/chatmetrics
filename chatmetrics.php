<?php
/*
Plugin Name: Chat Metrics - Managed live chat plugin for wordpress
Plugin URI: https://chatmetrics.com/kb/live-chat-plugin-for-wordpress
Description: Chat Metrics is a 24/7 fully managed live chat service where you only get sales qualified leads. We convert your web traffic into leads.
Version: 1.0
Author: Chat Metrics
Author URI: https://chatmetrics.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function chat_metrics_activate(){
    add_option( 'chatmetrics_url', 'https://chat-application.com/frontend/web/index.php' );
    register_uninstall_hook( __FILE__, 'chat_metrics_uninstall' );
}

register_activation_hook( __FILE__, 'chat_metrics_activate' );
 
function chat_metrics_uninstall(){
	delete_option( 'chatmetrics_url' );
	delete_option( 'chatmetrics_enabled' );
	delete_option( 'chatmetrics_user' );
    delete_option( 'chatmetrics_code' );
}


add_action('admin_menu', 'chat_metrics_setup_menu');
 
function chat_metrics_setup_menu(){
        add_menu_page( 'Live Chat Support', 'Live Chat', 'manage_options', 'chat-metrics-plugin', 'chat_metrics_init', 'dashicons-format-chat' );
}

add_action('admin_enqueue_scripts', 'chat_metrics_css_and_js');

function chat_metrics_css_and_js($hook)
{
	$current_screen = get_current_screen();

	if ( strpos($current_screen->base, 'chat-metrics-plugin') === false) {
	    return;
	} else {
	    wp_enqueue_style('chatmetrics_css', plugins_url('assets/style.css',__FILE__ ));
	    wp_enqueue_script('chatmetrics_js', plugins_url('assets/custom.js',__FILE__ ), array('jquery'));

		// in JavaScript, object properties are accessed as ajax_object.ajax_url
		wp_localize_script( 'chatmetrics_js', 'ajax_object', array( 'ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax-nonce')) );
	}
}
 
function chat_metrics_init() 
{ 
?>
	<div id="chatmetrics-content">
		<div class="column-left">
			<div class="ks-logo">
		       <img src="<?php echo esc_url(str_replace('index.php', '', get_option( 'chatmetrics_url' ))) ?>images/CM-new-logo.png" height="64">
		    </div>
		<?php
			if(get_option('chatmetrics_code')) {
		?>
			        <table class="form-table" style="width:240px; max-width:100%;">
			        	<?php if(get_option('chatmetrics_enabled')) { ?>
				        <tr>
				        	<th>
								<label class="switch">
								  <input type="checkbox" id="chatmetrics_switch" checked="checked">
								  <span class="slider"></span>
								</label>
							</th>
				        	<td class="chat-enable"><span id="chatenable-text">Chat Enabled</span></td>
				        </tr>
				        <?php } else { ?>
				        <tr>
				        	<th>
								<label class="switch">
								  <input type="checkbox" id="chatmetrics_switch">
								  <span class="slider"></span>
								</label>
							</th>
				        	<td class="chat-enable"><span id="chatenable-text">Chat Disabled</span></td>
				        </tr>
				        <?php } ?>
					    <tr>
				        	<th>
				        		<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
				        			<?php wp_nonce_field( 'chatmetriclogout', 'nonce' ); ?>        			
				        			<input type="hidden" name="action" value="chatmetlogout140784" />
				        			<input type="submit" name="logout" value="logout" class="button button-chat">
				        		</form>
							</th>
				        	<td class="chat-enable">You are logged in as <br/>
				        		<?php echo esc_html(get_option( 'chatmetrics_user' )); ?></td>
				        </tr>			        
				    </table>
		<?php			
			} else { 
				if(isset($_GET['signup'])) {
		?>
					<div class="errormsg">
				    	<?php echo isset($_GET['msg']) ? '<span class="dashicons dashicons-no-alt"></span>'.esc_html($_GET['msg']) : '&nbsp;'; ?>
				    </div>
				   <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>" id="chatlogin-form">
				       <?php wp_nonce_field( 'chatmetricsignup', 'nonce' ); ?> 
				       <input type="hidden" name="action" value="chatmesignup2934" />
				        <fieldset class="chatform-fieldset">
				        	<label>Name<br/><input type="text" name="name" class="chatform-field" /></label>
				        </fieldset>				        
				        <fieldset class="chatform-fieldset">
				        	<label>Email<br/><input type="text" name="email" class="chatform-field" /></label>
				        </fieldset>
				        <input type="submit" name="submit" id="submit" class="button button-chat button-submit" value="Sign Up">
				    </form>	    
			<?php			
				} else { 
			?>
					<div class="errormsg">
				    	<?php echo isset($_GET['msg']) ? '<span class="dashicons dashicons-no-alt"></span>'.esc_html($_GET['msg']) : '&nbsp;'; ?>
				    </div>
				   <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>" id="chatlogin-form">
				   		<?php wp_nonce_field( 'chatmetriclogin', 'nonce' ); ?> 
				        <input type="hidden" name="action" value="chatmet10500" />
				        <fieldset class="chatform-fieldset">
				        	<label>Email<br/><input type="text" name="email" class="chatform-field" /></label>
				        </fieldset>

				        <fieldset class="chatform-fieldset">
				        	<label>Password<br/><input type="password" name="password"  class="chatform-field"  /></label>
				        </fieldset>
				        <input type="submit" name="submit" id="submit" class="button button-chat button-submit" value="Login">
				    </form>	    
				    <div class=noaccount-wrap>
					    <h3 class="noaccount">Don't have an account? </h3>
					    <a href="<?php echo admin_url( 'admin.php' ); ?>?page=chat-metrics-plugin&signup=true" class="button button-chat button-signup">Sign Up</a>
				    </div>
			<?php } ?>		    
	<?php } ?>
		</div>
		<div class="column-right">
			<div id="about-chatmetrics">
				<h1>What is Chat Metrics?</h1>
				<p>We are a 24/7 fully managed live chat service. We convert your current website traffic into sales qualified leads.</p>
			</div>

			<div id="how-chatmetrics-works">
				<h3>How does it work?</h3>
				<p>Once you activate this plugin (requires a free active account) by entering your username and password a chatbox tracking code will be installed for your website. Our team will then research your website and come back to you with a 'business brief'. Once you approve the business brief we'll start taking chats on your website. We intitiate conversations with your website visitors and turn them into sales qualified leads (as defined by you in the business brief)</p>
			</div>
		</div>
		<div class="clear"></div>
	</div>	
<?php
} 

add_action( 'admin_action_chatmetlogout140784', 'chatmetlogout140784_admin_action' );

function chatmetlogout140784_admin_action()
{
	if (
	        isset($_POST['logout']) &&
	        isset($_POST['nonce']) &&
	        wp_verify_nonce($_POST['nonce'], 'chatmetriclogout')
	 ) {
		delete_option( 'chatmetrics_enabled' );
		delete_option( 'chatmetrics_user' );
	    delete_option( 'chatmetrics_code' );
		wp_redirect( $_SERVER['HTTP_REFERER'] );
	}
}

add_action( 'admin_action_chatmet10500', 'chatmet10500_admin_action' );

function chatmet10500_admin_action()
{ 
	if (
	        isset($_POST['email']) &&
	        isset($_POST['password']) &&
	        isset($_POST['nonce']) &&
	        wp_verify_nonce($_POST['nonce'], 'chatmetriclogin')
	 ) { 
		$email = sanitize_email($_POST['email']);
		$password = sanitize_text_field($_POST['password']); 
        
		$url = esc_url(get_option( 'chatmetrics_url' ) . '?r=api/get-tracking-code');

		$data = array(
			    'email' => $email,
			    'password' => $password
		);

		$json_response = wp_remote_post($url, array( 'sslverify' => false, 'body' => $data ));
		$response = json_decode($json_response['body'], true);

		if((isset($response['success']) ? $response['success'] : '') == 1) { 
			add_option('chatmetrics_user', $email);
			add_option('chatmetrics_code', esc_url_raw($response['message']));
			add_option('chatmetrics_enabled',true);
			wp_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		} else { 
			$msg = esc_attr(isset($response['message']) ? $response['message'] : 'Sorry. Please try again later.');
			wp_redirect( add_query_arg( 'msg', urlencode($msg), $_SERVER['HTTP_REFERER'] ) );
			exit();
		}	  
	}
}

add_action( 'admin_action_chatmesignup2934', 'chatmesignup2934_admin_action' );

function chatmesignup2934_admin_action()
{ 
	if (
	        isset($_POST['email']) &&
	        isset($_POST['name']) &&
	        isset($_POST['nonce']) &&
	        wp_verify_nonce($_POST['nonce'], 'chatmetricsignup') &&
	        is_email($_POST['email'])
	 ) { 
		$email = sanitize_email($_POST['email']);
		$name = sanitize_user($_POST['name']); 
        
		

		$data = array(
		        'r' => 'api/success',
			    'email' => $email,
			    'name' => $name,
			    'wp' => site_url('/'),
		);
        $url = esc_url(get_option( 'chatmetrics_url' )) . '?' .http_build_query($data);
        
		$json_response = wp_remote_get($url, array( 'sslverify' => false)); 
		$response = json_decode($json_response['body'], true);

		if((isset($response['success']) ? $response['success'] : '') == 1) { 
			add_option('chatmetrics_user', $email);
			add_option('chatmetrics_code', esc_url_raw($response['message']));
			add_option('chatmetrics_enabled',true);
			
			$data = array(
		        'r' => 'install/step',
			    'api_key' => sanitize_key($response['api_key']),
			    'email' => sanitize_email($response['email']),
			    'wp' => site_url('/'),
		    );		
			$url = esc_url(get_option( 'chatmetrics_url' )) . '?' .http_build_query($data);
			
			wp_redirect( $url );
			exit();
		} else { 
			$msg = esc_attr(isset($response['message']) ? $response['message'] : 'Sorry. Please try again later.');
			wp_redirect( add_query_arg( 'msg', urlencode($msg), $_SERVER['HTTP_REFERER'] ) );
			exit();
		}	  
	}
}

function chat_metrics_javascript() {
	$tracking_url = esc_url(get_option('chatmetrics_code'));
	if(get_option('chatmetrics_enabled')) {
	    wp_enqueue_script('chatmetrics_chat_js', $tracking_url);
	}
}
add_action('wp_head', 'chat_metrics_javascript');



// Same handler function...
add_action( 'wp_ajax_chatmetrics_switch', 'chatmetrics_switch' );
function chatmetrics_switch() {
	 if ( isset($_POST['_ajax_nonce']) && wp_verify_nonce( $_POST['_ajax_nonce'], 'ajax-nonce' ) ) {
		if(sanitize_key($_POST['chatenable']) == 'true') {
			update_option('chatmetrics_enabled',true);
			wp_send_json(array('msg' => 'Chat Enabled'));
		} else {
			update_option('chatmetrics_enabled',false);
			wp_send_json(array('msg' => 'Chat Disabled'));
		}
	 }
}