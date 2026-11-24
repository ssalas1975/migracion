 <?php $utm_array=ldk_get_utm(); ?>
<div class="col-c-module sidebar-contact-form">
    <div class="title">
        <div>
            ¿Te gustaría aparecer en el directorio de empresas de <?php echo LDK_CURRENT_MEDIO_NAME?>?<br />
        </div>
    </div>
   <div class="form">
       <form method="POST"
          class="contact-form-sidebar"
          action-xhr="<?php echo get_stylesheet_directory_uri()?>/srvcontacto.php"
          target="_top">
         
            <input type="text" placeholder="Empresa" name="empresa" class="query cf-item" required>
            <input type="text" placeholder="Teléfono" name="telefono" class="query phone cf-item fright" required>
			<div class="input_div cf-item">
			<amp-list width="auto" height="25" layout="fixed-height" src="<?php echo get_stylesheet_directory_uri()?>/provinces.php">
				<template type="amp-mustache">
				  <select class="query state" name="provincia" required id="provincia" on="
				  change:
					AMP.setState({
					 citysource:'<?php echo get_stylesheet_directory_uri()?>/cities.php?pr=' + event.value
					})">
					<option value="">Provincia</option>
					{{#provinces}}
					  <option value="{{slug}}">{{nombre}}</option>
					{{/provinces}}
				  </select>
				</template>
			</amp-list>
			</div>
	
			<div class="input_div cf-item fright">
			<amp-list width="auto" height="25" layout="fixed-height" [src]="citysource || '<?php echo get_stylesheet_directory_uri()?>/cities.php'" src="<?php echo get_stylesheet_directory_uri()?>/cities.php">
				<template type="amp-mustache">
				  <select class="query state" name="localidad" required id="localidad">
					<option value="">Localidad</option>
					{{#cities}}
					  <option value="{{slug}}">{{nombre}}</option>
					{{/cities}}
				  </select>
				</template>
			</amp-list>
			</div>

			
          
				
			
			<div class="input_div cf-item">
			<amp-list width="auto" height="25" layout="fixed-height" src="<?php echo get_stylesheet_directory_uri()?>/categories.php">
				<template type="amp-mustache">
				  <select class="query state" name="categoria" required id="categoria" >
					<option value="">Categoría</option>
					{{#categories}}
					  <option value="{{slug}}">{{nombre}}</option>
					{{/categories}}
				  </select>
				</template>
			</amp-list>
			</div>
			
		
	
            <input type="text" placeholder="Nombre y apellidos" name="nombre" class="query cf-item fright" required>
            <input type="text" placeholder="Email" name="email" class="query cf-item fullw" required>
			
			
			<input type="hidden"  name="medio" class="query" required value="<?php echo LDK_CURRENT_MEDIO_KEY?>">
			<input type="hidden"  name="tipo" class="query" required value="BLOG">
			<input type="hidden"  name="producto" class="query" required value="gestiona">
            <div class="action-check">
				<div class="input_div cf-item check-accept"><input type="checkbox" name="accept" required >He leído y acepto la <a href=" http://www.vocento.com/politica-privacidad" target="_blank">política de privacidad</a></div>
                <div class="submit"><input type="submit"  value="Enviar"  class="contact-btn"></div>
            </div>
			
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
    <div class="form-footer">
		<a  class="a-width" href="<?php  echo ldk_get_current_medio_meta('url_localdigitalkit',true).$utm_array['query']?>">
		<?php if(ldk_get_current_medio_meta('logo_white',true)){ ?>
		  <div class="contact-logo-footer"><amp-img src="<?php echo ldk_get_current_medio_meta('logo_white') ?>" alt="Logo"  height="20" width="130" layout="responsive"></amp-img></div>
		<?php } ?>
		</a>
    </div>
   
</div>