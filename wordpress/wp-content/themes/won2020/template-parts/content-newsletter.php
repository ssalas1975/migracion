<p class="c-title-small u-color-cyan">NEWSLETTER</p>
<?php if($_ENV["ENVIRON"] == "PRE" || $_ENV["ENVIRON"] == "DES"){ ?>
<div class='widget-prospect <?php echo $post->post_name=='newsletter' ? "" : "wrapperNewsletter hide" ?>' data-widget-id=<?php echo $post->post_name=='newsletter' ? "5f8711304aaf961d9708e46f" : "5f804b2ae83d491a2352b146" ?>> </div>
  <?php } elseif($_ENV["ENVIRON"] == "PRO") { ?>
<div class='widget-prospect <?php echo $post->post_name=='newsletter' ? "" : "wrapperNewsletter hide" ?>' data-widget-id=<?php echo $post->post_name=='newsletter' ? "5f8efbedac778664a737e08c" : "5f8efb90ac778664a737e08b" ?>> </div>
  <?php } ?>
  <?php
/*
 <div class="section section-head">
    <p class="title">NEWSLETTER</p>
	<span class="navi"></span>
</div>
 */
 ?>
