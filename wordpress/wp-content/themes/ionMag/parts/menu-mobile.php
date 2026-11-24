<div class="td-menu-background"></div>
<div id="td-mobile-nav">
    <div class="td-mobile-container">
        <!-- mobile menu top section -->
        <div class="td-menu-socials-wrap">
            
            <!-- close button -->
            <!--div class="td-mobile-close">
                <a href="#"><i class="td-icon-close-mobile"></i></a>
            </div-->
        </div>

        

        <!-- menu section -->
        <div class="td-mobile-content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'header-menu',
                'menu_class'=> 'td-mobile-main-menu',
                'fallback_cb' => 'td_wp_no_mobile_menu',
                'link_after' => '<i class="td-icon-menu-right td-element-after"></i>',
                'walker'  => new td_walker_mobile_menu()
            ));

            //if no menu
            function td_wp_no_mobile_menu() {
                //this is the default menu
                echo '<ul class="">';
                echo '<li class="menu-item-first"><a href="' . esc_url(home_url( '/' )) . 'wp-admin/nav-menus.php">Click here - to use the wp menu builder</a></li>';
                echo '</ul>';
            }

            ?>
        </div>
    </div>

    <!-- register/login section -->
    <?php if (td_util::get_option('tds_login_sign_in_widget') == 'show') { ?>
        <div id="login-form-mobile" class="td-register-section">
            <?php locate_template('parts/register.php', true); ?>
        </div>
    <?php } ?>
</div>