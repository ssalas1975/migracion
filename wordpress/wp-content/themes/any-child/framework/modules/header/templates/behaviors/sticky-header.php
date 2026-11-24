<?php do_action('any_edge_before_sticky_header'); ?>

<div class="edgtf-sticky-header">
    <?php do_action( 'any_edge_after_sticky_menu_html_open' ); ?>
    <div class="edgtf-sticky-holder">
    <?php if($sticky_header_in_grid) : ?>
        <div class="edgtf-grid">
            <?php endif; ?>
            <div class=" edgtf-vertical-align-containers">
                <div class="edgtf-position-left">
                    <div class="edgtf-position-left-inner">
                        <?php if(!$hide_logo) {
                            any_edge_get_logo('sticky');
                        } ?>
                    </div>
                </div>
                <div class="edgtf-position-right">
                    <div class="edgtf-position-right-inner">
						<?php
							switch($header_type):
								case 'header-standard':

									any_edge_get_sticky_menu('edgtf-sticky-nav');
									if(is_active_sidebar('edgtf-sticky-right')) :
										dynamic_sidebar('edgtf-sticky-right');
									endif;

									break;
								case 'header-full-screen':

									any_edge_get_full_screen_opener();
									if(is_active_sidebar('edgtf-full-screen-sticky-right')) :
										dynamic_sidebar('edgtf-full-screen-sticky-right');
									endif;

									break;
								case 'header-expanding':

									any_edge_get_sticky_menu('edgtf-sticky-nav');
									if(is_active_sidebar('edgtf-expanding-sticky-right')) : ?>
										<div class="edgtf-menu-right-widgets">
											<?php dynamic_sidebar('edgtf-expanding-sticky-right'); ?>
										</div>
									<?php endif;
									any_edge_get_expanding_header_opener();
									break;
							endswitch;
						?>
                    </div>
                </div>
                <?php if ($header_type == 'header-expanding'){ ?>
					<span class="edgtf-expanding-background"></span>
                <?php } ?>
            </div>
            <?php if($sticky_header_in_grid) : ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php do_action('any_edge_after_sticky_header'); ?>