<div class="col-c-module social">

       <div class="title">Síguenos en redes</div>
	<div class="nets-container">
  <?php if(ldk_get_current_medio_meta('facebook')){?>
     <div class="net fb">
        <a target="_blank" href="<?php echo ldk_get_current_medio_meta('facebook') ?>"><div class="icon"><i class="fa fa-facebook-f"></i></div></a>
    </div>
	<?php } ?>
	<?php if(ldk_get_current_medio_meta('twitter')){?>
    <div class="net tw">
        <a target="_blank" href="<?php echo ldk_get_current_medio_meta('twitter') ?>"><div class="icon"><i class="fa fa-twitter"></i></div></a>
    </div>
	<?php } ?>
	<?php if(ldk_get_current_medio_meta('google')){?>
	<div class="net gp">
        <a target="_blank" href="<?php echo ldk_get_current_medio_meta('google') ?>"><div class="icon"><i class="fa fa-google-plus"></i></div></a>
    </div>
	<?php } ?>
	<?php if(ldk_get_current_medio_meta('linkedin')){?>
	<div class="net ln">
        <a target="_blank" href="<?php echo ldk_get_current_medio_meta('linkedin') ?>"><div class="icon"><i class="fa fa-linkedin"></i></div></a>
    </div>
	<?php } ?>
	</div>
</div>