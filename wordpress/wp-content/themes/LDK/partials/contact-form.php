<div class="post-contact-form rocket">
    <div class="huge">
        <div>CONTÁCTANOS</div>
    </div>
   <div class="form">
       <form method="POST"
          class="contact-form"
          action-xhr="<?php echo get_stylesheet_directory_uri()?>/srvcontacto.php"
          target="_top">
          <div class="ampstart-input inline-block relative mb3">
            <input type="text" placeholder="Nombre empresa" name="empresa" class="query" required>
			 <input type="text" placeholder="Teléfono"  name="telefono" class="query phone" required>
			 <div class="input_div cf-item">
			<amp-list width="auto" height="37" layout="fixed-height" src="<?php echo get_stylesheet_directory_uri()?>/provinces.php">
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
          </div>
           <div class="ampstart-input inline-block relative mb3">
           <div class="input_div cf-item fright">
			<amp-list width="auto" height="37" layout="fixed-height" [src]="citysource || '<?php echo get_stylesheet_directory_uri()?>/cities.php'" src="<?php echo get_stylesheet_directory_uri()?>/cities.php">
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
			<amp-list width="auto" height="37" layout="fixed-height" src="<?php echo get_stylesheet_directory_uri()?>/categories.php">
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
			<input type="text" placeholder="Nombre y apellidos" name="nombre" class="query" required>
          </div>
           <div class="ampstart-input inline-block relative mb3">
            <input type="text" placeholder="Email" name="email" class="query" required>
		
            <div class="input_div cf-item action-check">
				<div class="input_div cf-item check-accept"><input type="checkbox" name="accept" required >He leído y acepto la <a href=" http://www.vocento.com/politica-privacidad" target="_blank">política de privacidad</a></div>
            </div>
          </div>
         	<input type="hidden"  name="medio" class="query" required value="<?php echo LDK_CURRENT_MEDIO_KEY?>">
			<input type="hidden"  name="tipo" class="query" required value="BLOG">
			<input type="hidden"  name="producto" class="query" required value="gestiona">
          
           <div class="submit"><input type="submit"  value="Enviar"  class="contact-btn"></div>
		   
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
   
</div>