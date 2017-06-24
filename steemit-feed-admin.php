<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function mn_steemit_menu() {
    add_menu_page(
        'Steemit Feed',
        'Steemit Feed',
        'manage_options',
        'mn-steemit-feed',
        'mn_steemit_settings_page'
    );
    add_submenu_page(
        'mn-steemit-feed',
        'Settings',
        'Settings',
        'manage_options',
        'mn-steemit-feed',
        'mn_steemit_settings_page'
    );
}
add_action('admin_menu', 'mn_steemit_menu');

function mn_steemit_settings_page() {

    //Hidden fields
    $mn_steemit_settings_hidden_field = 'mn_steemit_settings_hidden_field';
    $mn_steemit_general_hidden_field = 'mn_steemit_general_hidden_field';
    $mn_steemit_postsettings_hidden_field = 'mn_steemit_postsettings_hidden_field';

    //Declare defaults
    $mn_steemit_settings_defaults = array(
		// General
        'mn_steemit_username'				=> '',
		'mn_steemit_ajax_theme'           	=> false, 
        'mn_steemit_disable_awesome'      	=> false,
		// Post settings
		'mn_steemit_posts_count'			=> '5',
		'mn_steemit_post_image'				=> true,
        'mn_steemit_post_title'				=> true,
        'mn_steemit_post_content'			=> true,
        'mn_steemit_word_limit'				=> '20',
        'mn_steemit_post_reward'          	=> true,
        'mn_steemit_post_date'				=> true,
        'mn_steemit_post_author'			=> true,
        'mn_steemit_post_tag'				=> true,
        'mn_steemit_post_votes'				=> true,
        'mn_steemit_post_replies'			=> true,
        'mn_steemit_excluded_tags'			=> '',
    );
    //Save defaults in an array
    $options = wp_parse_args(get_option('mn_steemit_settings'), $mn_steemit_settings_defaults);
    update_option( 'mn_steemit_settings', $options );

    //Set the page variables
	// General
    $mn_steemit_username = $options[ 'mn_steemit_username' ];
    $mn_steemit_ajax_theme = $options[ 'mn_steemit_ajax_theme' ];
    $mn_steemit_disable_awesome = $options[ 'mn_steemit_disable_awesome' ];
	// Post settings
	$mn_steemit_posts_count = $options['mn_steemit_posts_count'];
	$mn_steemit_post_image = $options[ 'mn_steemit_post_image' ];
    $mn_steemit_post_title = $options[ 'mn_steemit_post_title' ];
    $mn_steemit_post_content = $options[ 'mn_steemit_post_content' ];
    $mn_steemit_word_limit = $options[ 'mn_steemit_word_limit' ];
    $mn_steemit_post_reward = $options[ 'mn_steemit_post_reward' ];
    $mn_steemit_post_date = $options[ 'mn_steemit_post_date' ];
    $mn_steemit_post_author = $options[ 'mn_steemit_post_author' ];
    $mn_steemit_post_tag = $options[ 'mn_steemit_post_tag' ];
    $mn_steemit_post_votes = $options[ 'mn_steemit_post_votes' ];
    $mn_steemit_post_replies = $options[ 'mn_steemit_post_replies' ];
    $mn_steemit_excluded_tags = $options[ 'mn_steemit_excluded_tags' ];

    //Check nonce before saving data
    if ( ! isset( $_POST['mn_steemit_settings_nonce'] ) || ! wp_verify_nonce( $_POST['mn_steemit_settings_nonce'], 'mn_steemit_saving_settings' ) ) {
        //Nonce did not verify
    } else {
        // See if the user has posted us some information. If they did, this hidden field will be set to 'Y'.
        if( isset($_POST[ $mn_steemit_settings_hidden_field ]) && $_POST[ $mn_steemit_settings_hidden_field ] == 'Y' ) {

            if( isset($_POST[ $mn_steemit_general_hidden_field ]) && $_POST[ $mn_steemit_general_hidden_field ] == 'Y' ) {

                $mn_steemit_username = sanitize_text_field( $_POST[ 'mn_steemit_username' ] );
                isset($_POST[ 'mn_steemit_ajax_theme' ]) ? $mn_steemit_ajax_theme = sanitize_text_field( $_POST[ 'mn_steemit_ajax_theme' ] ) : $mn_steemit_ajax_theme = '';
                isset($_POST[ 'mn_steemit_disable_awesome' ]) ? $mn_steemit_disable_awesome = sanitize_text_field( $_POST[ 'mn_steemit_disable_awesome' ] ) : $mn_steemit_disable_awesome = '';

                $options[ 'mn_steemit_username' ] = $mn_steemit_username;
                $options[ 'mn_steemit_ajax_theme' ] = $mn_steemit_ajax_theme;
				$options[ 'mn_steemit_disable_awesome' ] = $mn_steemit_disable_awesome;
            } //End General tab post

            if( isset($_POST[ $mn_steemit_postsettings_hidden_field ]) && $_POST[ $mn_steemit_postsettings_hidden_field ] == 'Y' ) {
                
				//Validate and sanitize options
                $mn_steemit_posts_count = intval( sanitize_text_field( $_POST['mn_steemit_posts_count'] ) );
                $mn_steemit_post_image = sanitize_text_field( $_POST['mn_steemit_post_image'] );
                $mn_steemit_post_title = sanitize_text_field( $_POST['mn_steemit_post_title'] );
                $mn_steemit_post_content = sanitize_text_field( $_POST['mn_steemit_post_content'] );
                $mn_steemit_word_limit = intval( sanitize_text_field( $_POST['mn_steemit_word_limit'] ) );
                $mn_steemit_post_reward = sanitize_text_field( $_POST['mn_steemit_post_reward'] );
                $mn_steemit_post_date = sanitize_text_field( $_POST[ 'mn_steemit_post_date' ] );
                $mn_steemit_post_author = sanitize_text_field( $_POST['mn_steemit_post_author'] );
                $mn_steemit_post_tag = sanitize_text_field( $_POST[ 'mn_steemit_post_tag' ] );
                $mn_steemit_post_votes = sanitize_text_field( $_POST[ 'mn_steemit_post_votes' ] );
                $mn_steemit_post_replies = sanitize_text_field( $_POST[ 'mn_steemit_post_replies' ] );
                $mn_steemit_excluded_tags = sanitize_text_field( $_POST[ 'mn_steemit_excluded_tags' ] );
                
				$options[ 'mn_steemit_posts_count' ] = $mn_steemit_posts_count;
                $options[ 'mn_steemit_post_image' ] = $mn_steemit_post_image;
                $options[ 'mn_steemit_post_title' ] = $mn_steemit_post_title;
                $options[ 'mn_steemit_post_content' ] = $mn_steemit_post_content;
                $options[ 'mn_steemit_word_limit' ] = $mn_steemit_word_limit;
                $options[ 'mn_steemit_post_reward' ] = $mn_steemit_post_reward;
                $options[ 'mn_steemit_post_date' ] = $mn_steemit_post_date;
                $options[ 'mn_steemit_post_author' ] = $mn_steemit_post_author;
                $options[ 'mn_steemit_post_tag' ] = $mn_steemit_post_tag;
                $options[ 'mn_steemit_post_votes' ] = $mn_steemit_post_votes;
                $options[ 'mn_steemit_post_replies' ] = $mn_steemit_post_replies;
                $options[ 'mn_steemit_excluded_tags' ] = $mn_steemit_excluded_tags;
                
            } //End Post Settings tab post
            
            //Save the settings to the settings array
            update_option( 'mn_steemit_settings', $options );

        ?>
        <div class="updated"><p><strong><?php _e('Settings saved.', 'steemit-feed' ); ?></strong></p></div>
        <?php } ?>

    <?php } //End nonce check ?>

    <div id="sfi_admin" class="wrap">

        <div id="header">
            <h1><?php _e('Steemit Feed', 'steemit-feed'); ?></h1>
        </div>
    
        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $mn_steemit_settings_hidden_field; ?>" value="Y">
            <?php wp_nonce_field( 'mn_steemit_saving_settings', 'mn_steemit_settings_nonce' ); ?>

            <?php $sfi_active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general'; ?>
            <h2 class="nav-tab-wrapper">
                <a href="?page=mn-steemit-feed&amp;tab=general" class="nav-tab <?php echo $sfi_active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('1. General', 'steemit-feed'); ?></a>
                <a href="?page=mn-steemit-feed&amp;tab=postsettings" class="nav-tab <?php echo $sfi_active_tab == 'postsettings' ? 'nav-tab-active' : ''; ?>"><?php _e('2. Post Settings', 'steemit-feed'); ?></a>
                <a href="?page=mn-steemit-feed&amp;tab=display" class="nav-tab <?php echo $sfi_active_tab == 'display' ? 'nav-tab-active' : ''; ?>"><?php _e('3. Display Your Feed', 'steemit-feed'); ?></a>
                <a href="?page=mn-steemit-feed&amp;tab=support" class="nav-tab <?php echo $sfi_active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', 'steemit-feed'); ?></a>
            </h2>

            <?php if( $sfi_active_tab == 'general' ) { //Start General tab ?>
            <input type="hidden" name="<?php echo $mn_steemit_general_hidden_field; ?>" value="Y">

            <table class="form-table">
                <tbody>
                    <h3><?php _e('General', 'steemit-feed'); ?></h3>
                    
                    <tr valign="top">
                        <th scope="row"><label><?php _e('Steemit Username:', 'steemit-feed'); ?></label></th>
                        <td>
                            <span>
                                <?php $mn_steemit_type = 'user'; ?>
                                <input name="mn_steemit_username" id="mn_steemit_username" type="text" value="<?php esc_attr_e( $mn_steemit_username, 'steemit-feed' ); ?>" size="25" />
                            </span>                                                       
                        </td>
                    </tr>

                    <tr>
                        <th class="bump-left"><label for="mn_steemit_ajax_theme" class="bump-left"><?php _e("Are you using an Ajax powered theme?", 'steemit-feed'); ?></label></th>
                        <td>
                            <input name="mn_steemit_ajax_theme" type="checkbox" id="mn_steemit_ajax_theme" <?php if($mn_steemit_ajax_theme == true) echo "checked"; ?> />
                            <label for="mn_steemit_ajax_theme"><?php _e('Yes', 'steemit-feed'); ?></label>
                            <a class="sfi_tooltip_link" href="JavaScript:void(0);"><?php _e('What does this mean?', 'steemit-feed'); ?></a>
                            <p class="sfi_tooltip"><?php _e("When navigating your site, if your theme uses Ajax to load content into your pages (meaning your page doesn't refresh) then check this setting. If you're not sure then please check with the theme author.", 'steemit-feed'); ?></p>
                        </td>
                    </tr>
					
					<tr valign="top">
						<th scope="row"><label><?php _e("Disable Font Awesome", 'steemit-feed'); ?></label></th>
						<td>
							<input type="checkbox" name="mn_steemit_disable_awesome" id="mn_steemit_disable_awesome" <?php if($mn_steemit_disable_awesome == true) echo 'checked="checked"' ?> /> Yes
						</td>
					</tr>
					
                </tbody>
            </table>
			
            <?php submit_button(); ?>
        </form>

        <p><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp; <?php _e('Next Step: <a href="?page=mn-steemit-feed&tab=postsettings">Post Settings</a>', 'steemit-feed'); ?></p>

    <?php } // End General tab ?>

    <?php if( $sfi_active_tab == 'postsettings' ) { //Start Post Settings tab ?>

    <p class="mn_steemit_contents_links" id="general">
        <span>Quick links: </span>
        <a href="#postsettings"><?php _e('General', 'steemit-feed'); ?></a>
    </p>

    <input type="hidden" name="<?php echo $mn_steemit_postsettings_hidden_field; ?>" value="Y">

        <h3><?php _e('General', 'steemit-feed'); ?></h3>

        <table class="form-table">
            <tbody>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Posts Count', 'steemit-feed'); ?></label></th>
                    <td>
                        <input name="mn_steemit_posts_count" type="text" value="<?php esc_attr_e( $mn_steemit_posts_count, 'steemit-feed' ); ?>" id="mn_steemit_posts_count" size="4" maxlength="4" />
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Post Image', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_image" id="mn_steemit_post_image">
                            <option value="1" <?php if($mn_steemit_post_image == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_image == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
                <tr valign="top">
                    <th scope="row"><label><?php _e('Post Title', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_title" id="mn_steemit_post_title">
                            <option value="1" <?php if($mn_steemit_post_title == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_title == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
                <tr valign="top">
                    <th scope="row"><label><?php _e('Post Content', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_content" id="mn_steemit_post_content">
                            <option value="1" <?php if($mn_steemit_post_content == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_content == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Word Limit', 'steemit-feed'); ?></label></th>
                    <td>
                        <input name="mn_steemit_word_limit" type="text" value="<?php esc_attr_e( $mn_steemit_word_limit, 'steemit-feed' ); ?>" size="4" maxlength="4" />
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Post Reward', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_reward" id="mn_steemit_post_reward">
                            <option value="1" <?php if($mn_steemit_post_reward == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_reward == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Post Date', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_date" id="mn_steemit_post_date">
                            <option value="1" <?php if($mn_steemit_post_date == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_date == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Post Author', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_author" id="mn_steemit_post_author">
                            <option value="1" <?php if($mn_steemit_post_author == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_author == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Post Tag', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_tag" id="mn_steemit_post_tag">
                            <option value="1" <?php if($mn_steemit_post_tag == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_tag == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Post Votes', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_votes" id="mn_steemit_post_votes">
                            <option value="1" <?php if($mn_steemit_post_votes == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_votes == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>
				
				<tr valign="top">
                    <th scope="row"><label><?php _e('Post Replies', 'steemit-feed'); ?></label></th>
                    <td>
                        <select name="mn_steemit_post_replies" id="mn_steemit_post_replies">
                            <option value="1" <?php if($mn_steemit_post_replies == 1) echo 'selected="selected"' ?> ><?php _e('Show', 'steemit-feed'); ?></option>
                            <option value="0" <?php if($mn_steemit_post_replies == 0) echo 'selected="selected"' ?> ><?php _e('Hide', 'steemit-feed'); ?></option>
                        </select>
                    </td>
                </tr>

				<tr valign="top">
                    <th scope="row"><label><?php _e('Excluded Tags', 'steemit-feed'); ?></label></th>
                    <td>
                        <input name="mn_steemit_excluded_tags" type="text" value="<?php echo esc_attr( $mn_steemit_excluded_tags ); ?>" size="40" />
						<p class="howto"><?php _e('Separate tags with commas.', 'steemit-feed'); ?></p>
                    </td>
                </tr>
				
            </tbody>
        </table>

        <!--<hr id="second-section-id" />-->

        <?php submit_button(); ?>

    </form>

    <p><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp; <?php _e('Next Step: <a href="?page=mn-steemit-feed&tab=display">Display your Feed</a>', 'steemit-feed'); ?></p>

    <?php } //End Post Settings tab ?>

    <?php if( $sfi_active_tab == 'display' ) { //Start Display tab ?>

        <h3><?php _e('Display your Feed', 'steemit-feed'); ?></h3>
        <p><?php _e("Copy and paste the following shortcode directly into the page, post or widget where you'd like the feed to show up:", 'steemit-feed'); ?></p>
        <input type="text" value="[steemit-feed]" size="16" readonly="readonly" style="text-align: center;" onclick="this.focus();this.select()" title="<?php _e('To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', 'steemit-feed'); ?>" />

        <h3 style="padding-top: 10px;"><?php _e( 'Multiple Feeds', 'steemit-feed' ); ?></h3>
        <p><?php _e("If you'd like to display multiple feeds then you can set different settings directly in the shortcode like so:", 'steemit-feed'); ?>
        <code>[steemit-feed username="wordpress-tips" postimage="false"]</code></p>
        <p>You can display as many different feeds as you like, on either the same page or on different pages, by just using the shortcode options below. For example:<br />
        <code>[steemit-feed]</code><br />
        <code>[steemit-feed username="username"]</code><br />
        <code>[steemit-feed username="username" postcontent="false" postvotes="true" postreplies="false"]</code>
        </p>
        <p><?php _e("See the table below for a full list of available shortcode options:", 'steemit-feed'); ?></p>

        <table class="sfi_shortcode_table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?php _e('Shortcode option', 'steemit-feed'); ?></th>
                    <th scope="row"><?php _e('Description', 'steemit-feed'); ?></th>
                    <th scope="row"><?php _e('Example', 'steemit-feed'); ?></th>
                </tr>

                <tr class="sfi_table_header"><td colspan=3><?php _e("General Settings", 'steemit-feed'); ?></td></tr>
                <tr>
                    <td>username</td>
                    <td><?php _e('A Steemit username.', 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed username="wordpress-tips"]</code></td>
                </tr>

                <tr class="sfi_table_header"><td colspan=3><?php _e("Post Settings", 'steemit-feed'); ?></td></tr>
                <tr>
                    <td>postscount</td>
                    <td><?php _e("Total posts in feed (integer).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postscount="5"]</code></td>
                </tr>
                <tr>
                    <td>postimage</td>
                    <td><?php _e("Show post image (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postimage="true"]</code></td>
                </tr>
                <tr>
                    <td>posttitle</td>
                    <td><?php _e("Show post title (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed posttitle="true"]</code></td>
                </tr>
                <tr>
                    <td>postcontent</td>
                    <td><?php _e("Show post content (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postcontent="false"]</code></td>
                </tr>
                <tr>
                    <td>wordlimit</td>
                    <td><?php _e("Word limit for post content (integer).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed wordlimit="20"]</code></td>
                </tr>
                <tr>
                    <td>postreward</td>
                    <td><?php _e("Show post reward (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postreward="false"]</code></td>
                </tr>

                <tr>
                    <td>postdate</td>
                    <td><?php _e("Show post date (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postdate="true"]</code></td>
                </tr>
                <tr>
                    <td>postauthor</td>
                    <td><?php _e("Show post author (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postauthor="false"]</code></td>
                </tr>
                <tr>
                    <td>posttag</td>
                    <td><?php _e("Show post tag (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed posttag="true"]</code></td>
                </tr>
                <tr>
                    <td>postvotes</td>
                    <td><?php _e("Show post votes (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postvotes="false"]</code></td>
                </tr>
                <tr>
                    <td>postreplies</td>
                    <td><?php _e("Show post replies (true or false).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed postreplies="true"]</code></td>
                </tr>
                <tr>
                    <td>excludedtags</td>
                    <td><?php _e("Exclude certain tags (separated with commas).", 'steemit-feed'); ?></td>
                    <td><code>[steemit-feed excludedtags="foo,bar"]</code></td>
                </tr>
                
            </tbody>
        </table>

    <?php } //End Display tab ?>

    <?php if( $sfi_active_tab == 'support' ) { //Start Support tab ?>

        <h3><?php _e('Setting up and Customizing the plugin', 'steemit-feed'); ?></h3>
        <p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php _e('<a href="https://steemit.com/steemit/@wordpress-tips/steemit-for-wordpress-1-display-your-steemit-blog-in-your-wordpress-website-with-this-free-plugin" target="_blank">Click here for setup instructions</a>', 'steemit-feed'); ?></p>
        
	<?php } //End Support tab ?>
	
    <hr />

    <p class="sfi_plugins_promo dashicons-before dashicons-admin-plugins"> Check out our other products at <a href="https://www.minitek.gr/" target="_blank">https://www.minitek.gr/</a>.</p>

    <div class="sfi_share_plugin">
        <h3><?php _e('Like the plugin? Help spread the word!'); ?></h3>

        <!-- TWITTER -->
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="https://steemit.com/steemit/@wordpress-tips/steemit-for-wordpress-1-display-your-steemit-blog-in-your-wordpress-website-with-this-free-plugin" data-text="A simple Wordpress plugin that displays a feed of your Steemit posts." data-via="Minitek_gr" data-dnt="true">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <style type="text/css">
        #twitter-widget-0{ float: left; width: 100px !important; }
        .IN-widget{ margin-right: 20px; }
        </style>

        <!-- FACEBOOK -->
        <div id="fb-root" style="display: none;"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1684364011793192&version=v2.7";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-like" data-href="https://steemit.com/steemit/@wordpress-tips/steemit-for-wordpress-1-display-your-steemit-blog-in-your-wordpress-website-with-this-free-plugin" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true" style="display: block; float: left; margin-right: 20px;"></div>

    </div>

</div> <!-- end #sfi_admin -->

<?php } //End Settings page

function mn_steemit_admin_style() {
        wp_register_style( 'mn_steemit_admin_css', plugins_url('css/mn-steemit-style-admin.css', __FILE__), array(), MNSFVER );
        wp_enqueue_style( 'mn_steemit_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
        wp_enqueue_style( 'mn_steemit_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'mn_steemit_admin_style' );

function mn_steemit_admin_scripts() {
    wp_enqueue_script( '', plugins_url( 'js/mn-steemit-admin.js' , __FILE__ ), array(), MNSFVER );

    if( !wp_script_is('jquery-ui-draggable') ) { 
        wp_enqueue_script(
            array(
            'jquery',
            'jquery-ui-core',
            'jquery-ui-draggable'
            )
        );
    }
    wp_enqueue_script(
        array(
        'hoverIntent',
        'wp-color-picker'
        )
    );
}
add_action( 'admin_enqueue_scripts', 'mn_steemit_admin_scripts' );

// Add a Settings link to the plugin on the Plugins page
$sfi_plugin_file = 'steemit-feed/steemit-feed.php';
add_filter( "plugin_action_links_{$sfi_plugin_file}", 'sfi_add_settings_link', 10, 2 );
 
//modify the link by unshifting the array
function sfi_add_settings_link( $links, $file ) {
    $sfi_settings_link = '<a href="' . admin_url( 'admin.php?page=mn-steemit-feed' ) . '">' . __( 'Settings', 'mn-steemit-feed', 'steemit-feed' ) . '</a>';
    array_unshift( $links, $sfi_settings_link );
 
    return $links;
}
?>
