<div class="col-c-module  newsletter">
   <b class="title"><i class="orange-line"></i>Apúntante a nuestra newsletter</b>
<form method="POST"
  class="p2"
   action-xhr="<?php echo get_stylesheet_directory_uri()?>/srvnewsletter.php"
  target="_top">
  <div class="ampstart-input inline-block relative mb3">
    <input type="email"
      placeholder="Déjanos tu email"
      name="email" class="query">
  </div>
  <input type="hidden"  name="medio" class="query" required value="<?php echo LDK_CURRENT_MEDIO_KEY?>">
  <input type="submit"
    value="SUSCRÍBETE"
    class="query-btn">
	
	<div submit-success>
			 <template type="amp-mustache">
				<div class="form-{{type}}">{{msg}}</div>
			</template>
			</div>
		   <div submit-error>
		     <template type="amp-mustache">
				<div class="form-{{type}}">{{msg}}</div>
			</template>
		   </div>
</form>
</div>