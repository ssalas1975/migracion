<?php
if(function_exists("fw_get_db_post_option")){
?>
<section class="ts-schedule ts-schedule-alt">
   <div class="container">
      <div class="row">
         <!-- col end-->
         <div class="col-lg-12 text-center wow fadeInUp" data-wow-duration="1.5s" data-wow-delay="500ms">
            <div class="ts-schedule-nav mb-70">
               <ul class="nav nav-tabs" role="tablist">
                  <?php                 extract([
                     'number' => $schedule_day_limit,
                     'order' => $schedule_order,
                     'class' => '',
                  ]);

                  global $post;
                  $multiple_speaker = [];
                  $args = array(
                     'post_type'             => 'ts-schedule',
                     'suppress_filters' => false,
                     'posts_per_page'          => esc_attr($number),
                     'order'                   => $order,
                  );

                  if(isset($schedule_term_id) && $schedule_term_id !=''){
                     $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'ts-schedule_cat',
                            'field' => 'term_id',
                            'terms' => $schedule_term_id
                             ),
                     );
                  };
                  
                  $i = 1;
                  $posts = get_posts($args);
                  foreach ($posts as $postnav) {
                     setup_postdata($postnav);
                  ?>
                     <?php if ($i == 1) { ?>
                        <li class="nav-item">
                           <a class="active" title="<?php echo get_the_title($postnav->ID) ?>" href="<?php echo esc_attr("#date" . $i); ?>" role="tab" data-toggle="tab">
                              <h3><?php echo get_the_title($postnav->ID) ?></h3>
                              <?php $schedule_day_v = fw_get_db_post_option($postnav->ID, 'schedule_day');
                              if (!empty($schedule_day_v)) {  ?>
                                 <h3><?php echo fw_get_db_post_option($postnav->ID, 'schedule_day'); ?></h3>
                              <?php } ?>
                           </a>
                        </li>
                     <?php } else { ?>
                        <li class="nav-item">
                           <a title="<?php echo get_the_title($postnav->ID) ?>" href="<?php echo esc_attr("#date" . $i); ?>" role="tab" data-toggle="tab">
                              <h3><?php echo get_the_title($postnav->ID) ?></h3>

                              <?php $schedule_day_v = fw_get_db_post_option($postnav->ID, 'schedule_day');
                              if (!empty($schedule_day_v)) {  ?>
                                 <h3><?php echo fw_get_db_post_option($postnav->ID, 'schedule_day'); ?></h3>
                              <?php } ?>
                           </a>
                        </li>
                     <?php  } ?>
                  <?php $i++;
                  }
                  wp_reset_postdata(); ?>
               </ul>
               <!-- Tab panes -->
            </div>
         </div>
         <!-- col end-->
      </div>
      <!-- row end-->
      <div class="row">
         <div class="col-lg-12">
            <div class="tab-content schedule-tabs">
               <?php              $j = 1;
               foreach ($posts as $post) {
                  setup_postdata($post);
                  $schedule_list = fw_get_db_post_option($post->ID)["exhibz_schedule_pop_up"];
               ?>
                  <?php if ($j == 1) { ?>
                     <div role="tabpanel" class="tab-pane active" id="<?php echo esc_attr("date" . $j); ?>">
                        <?php foreach ($schedule_list as $key => $schedule) { ?>
                           <?php

                           if ($key == $schedule_limit) {
                              break;
                           }

                           $speaker_one = '';
                           $speaker_two = '';
                           $speaker_three = '';

                           $speaker_id_one = '';
                           $speaker_id_two = '';
                           $speaker_id_three = '';

                           $schedule_speaker_multi = [];

                           $q = [];
                           $speaker_title = [];
                           $speaker_id = [];
                           $multiple_speaker = [];

                           if (array_key_exists("multi_speaker_choose", $schedule)) {
                              if (array_key_exists("style", $schedule["multi_speaker_choose"])) {
                                 $schedule_speaker_check = $schedule["multi_speaker_choose"];
                                 $schedule_speaker = $schedule_speaker_check["yes"]["multi_speakers"];
                                 if ($schedule_speaker_check["style"] == "yes") { //multi speaker style 
                                    $schedule_speaker_multi = $schedule_speaker_check["yes"]['multi_speakers'];

                                    // var_dump($schedule_speaker_multi);
                                    foreach ($schedule_speaker_multi as $sp_key => $speaker) {
                                       $multiple_speaker[] = exhibz_meta_option($speaker, "exhibs_photo");
                                       $single_sp = get_post($speaker);
                                       $speaker_id[] = $single_sp->ID;
                                       $speaker_title[] = $single_sp->post_title;
                                
                                    }
                                    // var_dump($multiple_speaker);

                                    // get speaker name
                                    $args = [
                                       'post_type'      => 'ts-speaker',
                                       'posts_per_page' => 10,
                                       'post__in'  => $schedule_speaker_multi,
                                    ];

                                    $q = get_posts($args, ARRAY_A);
                                 } else { //single speaker  

                                    if (is_string($schedule["speakers"]) && $schedule["speakers"] != '') {
                                       $multiple_speaker[] = exhibz_meta_option($schedule["speakers"], "exhibs_photo");
                                       if ($schedule["speakers"] != '' && $schedule["speakers"] > 0) {

                                          $q = get_post($schedule["speakers"]);
                                   
                                          $speaker_id[] = $q->ID;
                                          $speaker_title[] = $q->post_title;
                                       }
                                    } // single speaker
                                 }
                              }
                           } else {

                              if (is_string($schedule["speakers"]) && $schedule["speakers"] != '') {
                                 $multiple_speaker[] = exhibz_meta_option($schedule["speakers"], "exhibs_photo");
                                 if ($schedule["speakers"] != '' && $schedule["speakers"] > 0) {
                                    $q = get_post($schedule["speakers"]);
                                    // $speaker_one = $q->post_title;
                                    // $speaker_id_one = $q->ID;
                                    $speaker_id[] = $q->ID;
                                    $speaker_title[] = $q->post_title;
                                 }
                              } // single speaker

                           }
                           $background_color = isset($schedule["background_color"]) ? $schedule["background_color"] : '';
                           $position = isset($schedule["position"]) ? $schedule["position"] : 'default';
                           $column_position = isset($schedule['column_position']) ? $schedule['column_position'] : 'normal';
                           $flex_direction = ($column_position === 'reverse') ? 'row-reverse' : 'row';
                           ?>



<div class="schedule-listing multi-schedule-list row" style="background-color: <?php echo esc_attr($background_color); ?>; flex-direction: <?php echo esc_attr($flex_direction); ?>;">


<div class="col-lg-3">
    <?php if($schedule["schedule_time"]): ?> 
        <div class="schedule-slot-time">
        

        <?php if (!empty($schedule["day"])) : ?>
           <span style="background-color: <?php echo esc_attr($background_color); ?>!important;">    
               <?php echo esc_html($schedule["day"]); ?>
               </span>
           <?php endif; ?>
            <span style="background-color: <?php echo esc_attr($background_color); ?>!important;">  <?php echo esc_html($schedule["schedule_time"]); ?> </span>
       
            <?php if (!empty($schedule["duration"])) : ?>
            <span class="schedule-duration" style="background-color: <?php echo esc_attr($background_color); ?>!important;;">
                   <?php echo esc_html($schedule["duration"]); ?>
            </span>
            <?php endif; ?>
        </div>
    <?php endif; ?> 



    <?php if ($column_position == 'reverse' && $position != 'bottom') : ?>

    <div class="speakers-default">
            <?php foreach ($multiple_speaker as $key => $value) { ?>
                <div class="speaker-content">
                    <?php if (isset($value['attachment_id'])) : ?>
                        <a href="<?php echo esc_url(get_the_permalink($speaker_id[$key])); ?>">
                            <img class="schedule-slot-speakers" 
                                 src="<?php echo wp_get_attachment_url($value["attachment_id"], 'thumbnail'); ?>" 
                                 title="<?php echo esc_attr($speaker_title[$key]); ?>" 
                                 alt="<?php echo esc_attr("speaker-" . ($key + 1)); ?>">
                        </a>
                    <?php endif; ?>
                    <p class="schedule-speaker"><?php echo exhibz_kses($speaker_title[$key]); ?></p>
                </div>
            <?php } ?>
        </div>
        <?php endif; ?>
</div>

    <div class="col-lg-5">
    <div class="schedule-slot-info">
        <div class="schedule-slot-info-content">
            <h3 class="schedule-slot-title">
                <?php echo esc_html($schedule["schedule_title"]); ?>
            </h3>
            <p>
                <?php echo exhibz_kses($schedule["schedule_note"]); ?>
            </p>
        </div>
    </div>

    <?php if ($position == 'bottom') : ?>
        <!-- Insertar speakers dentro del mismo col-lg-5 -->
        <div class="speakers-below d-flex justify-content-center flex-wrap">
            <?php foreach ($multiple_speaker as $key => $value) { ?>
                <div class="speaker-content">
                    <?php if (isset($value['attachment_id'])) : ?>
                        <a href="<?php echo esc_url(get_the_permalink($speaker_id[$key])); ?>">
                            <img class="schedule-slot-speakers" 
                                 src="<?php echo wp_get_attachment_url($value["attachment_id"], 'thumbnail'); ?>" 
                                 title="<?php echo esc_attr($speaker_title[$key]); ?>" 
                                 alt="<?php echo esc_attr("speaker-" . ($key + 1)); ?>">
                        </a>
                    <?php endif; ?>
                    <p class="schedule-speaker"><?php echo exhibz_kses($speaker_title[$key]); ?></p>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($position != 'bottom') : ?>
    <!-- Speakers en columna cuando la posición es default -->
    <div class="col-lg-4">
    <?php 

$schedule_vertical = isset($schedule["schedule_vertical"]) ? $schedule["schedule_vertical"] : '';

if (!empty($schedule_vertical)) {
    if ($schedule_vertical['type'] == 'text' && !empty($schedule_vertical['text']['text_value'])) {
        echo '<div class="vertical-text">' . esc_html($schedule_vertical['text']['text_value']) . '</div>';
    } elseif ($schedule_vertical['type'] == 'image' && !empty($schedule_vertical['image']['image_value']['url'])) {
        echo '<div class="vertical-image">
                <img src="' . esc_url($schedule_vertical['image']['image_value']['url']) . '" alt="Vertical Image">
              </div>';
    }
}

?>

<?php if ($column_position === 'normal') : ?>
        <div class="speakers-default">
            <?php foreach ($multiple_speaker as $key => $value) { ?>
                <div class="speaker-content">
                    <?php if (isset($value['attachment_id'])) : ?>
                        <a href="<?php echo esc_url(get_the_permalink($speaker_id[$key])); ?>">
                            <img class="schedule-slot-speakers" 
                                 src="<?php echo wp_get_attachment_url($value["attachment_id"], 'thumbnail'); ?>" 
                                 title="<?php echo esc_attr($speaker_title[$key]); ?>" 
                                 alt="<?php echo esc_attr("speaker-" . ($key + 1)); ?>">
                        </a>
                    <?php endif; ?>
                    <p class="schedule-speaker"><?php echo exhibz_kses($speaker_title[$key]); ?></p>
                </div>
            <?php } ?>
        </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
</div>

                        <?php    } // end loop schedule list 
                        ?>
                        <!--schedule-listing end -->
                     </div>
                  <?php } else { //active 
                  ?>
                     <div role="tabpanel" class="tab-pane" id="<?php echo esc_attr("date" . $j); ?>">
                        <?php foreach ($schedule_list as $key => $schedule) { ?>
                           <?php

                           if ($key == $schedule_limit) {
                              break;
                           }

                           $speaker_one = '';
                           $speaker_two = '';
                           $speaker_three = '';

                           $speaker_id_one = '';
                           $speaker_id_two = '';
                           $speaker_id_three = '';

                           $schedule_speaker_multi = [];

                           $q = [];
                           $speaker_title = [];
                           $speaker_id = [];
                           $multiple_speaker = [];

                           if (array_key_exists("multi_speaker_choose", $schedule)) {
                              if (array_key_exists("style", $schedule["multi_speaker_choose"])) {
                                 $schedule_speaker_check = $schedule["multi_speaker_choose"];
                                 $schedule_speaker = $schedule_speaker_check["yes"]["multi_speakers"];
                                 if ($schedule_speaker_check["style"] == "yes") { //multi speaker style 
                                    $schedule_speaker_multi = $schedule_speaker_check["yes"]['multi_speakers'];
                                    foreach ($schedule_speaker_multi as $sp_key => $speaker) {
                                       $multiple_speaker[] = exhibz_meta_option($speaker, "exhibs_photo");
                                       $single_sp = get_post($speaker);
                                       $speaker_id[] = $single_sp->ID;
                                       $speaker_title[] = $single_sp->post_title;
                                    }
                                 } else { //single speaker  

                                    if (is_string($schedule["speakers"]) && $schedule["speakers"] != '') {
                                       $multiple_speaker[] = exhibz_meta_option($schedule["speakers"], "exhibs_photo");
                                       if ($schedule["speakers"] != '' && $schedule["speakers"] > 0) {
                                          $q = get_post($schedule["speakers"]);
                                          $speaker_id[] = $q->ID;
                                          $speaker_title[] = $q->post_title;
                                       }
                                    } // single speaker
                                 }
                              }
                           } else {

                              if (is_string($schedule["speakers"]) && $schedule["speakers"] != '') {
                                 $multiple_speaker[] = exhibz_meta_option($schedule["speakers"], "exhibs_photo");
                                 if ($schedule["speakers"] != '' && $schedule["speakers"] > 0) {
                                    $q = get_post($schedule["speakers"]);
                                    $speaker_id[] = $q->ID;
                                    $speaker_title[] = $q->post_title;
                                 }
                              } // single speaker

                           }



                           ?>
                           <div class="schedule-listing multi-schedule-list row">
                              <div class="col-lg-3">
                                 <div class="schedule-slot-time">
                                    <span> <?php echo esc_html($schedule["schedule_time"]); ?> </span>
                                 </div>
                              </div>
                               <!--  col end -->
                             <div class="col-lg-5">
                                 <div class="schedule-slot-info">
                                 
                                    <div class="schedule-slot-info-content">
                                        <h3 class="schedule-slot-title">
                                             <?php echo esc_html($schedule["schedule_title"]); ?>
                                             <!-- <strong>@ Fredric Martinsson </strong> -->
                                          </h3>
                                          <p>
                                             <?php echo exhibz_kses($schedule["schedule_note"]); ?>
                                          </p>
                                       </div>
                                       <!--Info content end -->
                                    </div>
                                    <!-- Slot info end -->
                             </div>
                              <!--  col end -->
                              <div class="col-lg-4">
                                 <?php if (count($multiple_speaker)) : ?>
                                       <div class="<?php echo count($multiple_speaker) == 1 ? 'single-speaker-2' : 'multi-speaker-2' ?>">
                                          <?php foreach ($multiple_speaker as $key => $value) { ?>
                                             <div class="speaker-content">
                                                <?php $incremented_key = $key + 1; 
                                                if (isset($value['attachment_id'])) : ?>
                                                <a rel="noreferrer" href="<?php echo esc_url(get_the_permalink($speaker_id[$key])); ?>"> <img class="schedule-slot-speakers <?php echo esc_attr("speaker-img-" . $incremented_key); ?>" src="<?php echo wp_get_attachment_url($value["attachment_id"], 'thumbnail'); ?>" title="<?php echo esc_attr($speaker_title[$key]); ?>" alt="<?php echo esc_attr("speaker-" . $incremented_key); ?>"> </a>
                                                <?php endif; ?>
                                                <p class="schedule-speaker <?php echo esc_attr("speaker-" . $key); ?>">
                                                   <?php echo exhibz_kses($speaker_title[$key]); ?>
                                                </p>
                                             </div>
                                          <?php } ?>
                                       </div>
                                    <?php elseif ($schedule["speakers"] == '') : ?>
                                       <div class="single-speaker"></div>
                                    <?php endif; ?>
                              </div>
                               <!--  col end -->
                           </div>
                        <?php $multiple_speaker = array();
                           $q = '';
                        } // end loop schedule list 
                        ?>
                        <!--schedule-listing end -->
                     </div>
                  <?php } //end else  
                  ?>
               <?php                 $j++;
               }
               wp_reset_postdata(); ?>
            </div>
         </div>
      </div>
   </div>
   <!-- container end-->
</section>
<?php }
else{
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo esc_html("Please activate unyson plugin!");?>
  </div>
  <?php 
} ?>