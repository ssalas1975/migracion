(function ($) {
    $.fn.MeytelJsonTree = function (options) {

        return this.each(function () {


            var $this = $(this);
            var self = this;
			this.html="";
            this.opts = $.extend({}, $.fn.MeytelJsonTree.defaultOptions, options);
			//this.opts.json={"portadaid":{},"path":"http:\/\/blogs.larioja.com","url":"http:\/\/blogs.larioja.com","fechapublicacion":{},"descripcion":{},"title":{},"keywords":{},"scribblelive1":{},"scribblelive2":{},"scribblelive3":{},"auto":{},"noticias":{"noticia":{"noticiaid":"3205","tipocontenido":"noticia","plantilla":{},"plantilla_visualizacion":{},"zona":{},"fechapublicacion":"1455404856","edicion":"1455404857","lead":{"titulo":{},"subtitulo":{},"antetitulo":{},"entradilla":{},"firma":"Mart\u00edn Schmitt","fechapublicacion":"1455404856","apoyos":{"apoyo":{"apoyo_zona":{},"apoyo_tipo":{},"apoyo_titulo":{},"apoyo_texto":{},"apoyo_epigrafe":{},"apoyo_antetitulo":{},"apoyo_entradilla":{},"apoyo_autor":{},"apoyo_url":{},"apoyo_image":{"url":{},"height":{},"tipo":{},"video_titulo":{},"video_url":{}}}},"images":{"image":{"image_zona":{},"image_url":{},"image_height":{},"image_pie":{},"image_videotitulo":{},"image_videourl":{}}},"modulos":{"modulo":{"tipo_modulo":{},"identificador":{},"tipo_contenido":{},"plantilla":{},"fechapublicacion":{},"epigrafe_texto":{},"epigrafe_url":{},"noticias":{"zona":{},"antetitulo":{},"entradilla":{},"autor":{},"subtitulo":{},"titular":{},"url":{},"image":{"image_url":{},"image_height":{},"image_pie":{},"image_videotitulo":{},"image_videourl":{}}}}}},"story":{"id":{},"tipo_contenido":"post","seccion":"ElBalonblog","path":"http:\/\/blogs.larioja.com\/balonblog\/2016\/02\/14\/victoria-aplastante-del-naturhouse\/?feed=noticiasfeed","url":"http:\/\/blogs.larioja.com\/balonblog\/2016\/02\/14\/victoria-aplastante-del-naturhouse\/","url_medio":"http:\/\/blogs.larioja.com\/balonblog","descripcion":{},"keywords":{},"fechapublicacion":"1455404856","titular":{},"titulo":{},"subtitulo":{},"antetitulo":{},"firma":"Mart\u00edn Schmitt","localidad":{},"cuerpo":{},"apoyos":{"apoyo":{"apoyozona":{},"apoyotipo":{},"apoyoepigrafe":{},"apoyotexto":{},"apoyourl":{}}},"images":{"image":{"imagezona":{},"imageurl":{},"image_height":{},"imagepie":{},"videotitulo":{},"videourl":{}}}}}}};
			
			this.printTree=function(){
				self.html=self.generateNode(self.opts.json,self.html); 
				$this.html(self.html);
				
				$("li",$this).each(function(i,val){
					if($("ul",$(val)).size()>0)
						$(val).removeClass("vcf-tree-node");
				});
				
				//Test click edit
				$(".vcf-icon-edit",".vcf-tree-node").click(function(){
					$parent=$(this).closest(".vcf-tree-node");
					if($(".vcf-edit-layer",$parent).size()==0)
					{
						$parent.append(self.generateEditLayer());
						
					}
					else{
						$(".vcf-edit-layer",$parent).toggle();
					}
					self.generateEditLayerEvents($parent);
				});
			}
			
			
			this.generateEditLayer=function(){
				//main container
				$edit=$("<div class='vcf-edit-layer'>");
				
				//combo types
				$type="<div class='vcf-edit-row vcf-edit-layer-types'><span>Tipo:</span>";
				$type+="<select data-key='custom_type' class='vcf-attr select-type'>";
				$type+="<option>Original</option>";
				$type+="<option>Fecha</option>";
				$type+="<option>Reemplazo</option>";
				$type+="</select>";
				$type+="</div>";
				
				//use CDATA
				$type+="<div class='vcf-edit-row cdata-format' ><span>Usar CDATA:</span>";
				$type+="<input type='checkbox' data-key='custom_cdata_format' class='vcf-attr cdata-format' style='width:auto !important' />";
				$type+="</div>";
				
				//date format
				$type+="<div class='vcf-edit-row date-format' style='display:none;'><span>Formato fecha:</span>";
				$type+="<input type='text' data-key='custom_date_format' class='vcf-attr date-format' placeholder='yyyymmdd' />";
				$type+="</div>";
				
				//custom value
				$type+="<div class='vcf-edit-row custom-format' style='display:none;'><span>Valor fijo:</span>";
				$type+="<input type='text' data-key='custom_value' class='vcf-attr custom-value' placeholder='Escriba un valor' />";
				$type+="</div>";
				
				//custom value
				$type+="<div class='vcf-edit-row replace-format' style='display:none;'><span>Plantilla:</span>";
				$type+="<input type='text' data-key='replace_value' class='vcf-attr replace-value' placeholder='Utilice: % Ej:mh.blogs.%' />";
				$type+="</div>";
				
				//Guardar
				//$type+="<div class='vcf-save'>";
				//$type+="<input type='button'  value='Guardar' />";
				//$type+="</div>";
				
				$edit.append($type);
				return $edit;
			};
			
			this.generateEditLayerEvents=function($row){
				//row es el "li" que contiene todo, tanto el titulo como lof formularios
				//si es fecha mostramos la caja de texto
				$select=$(".select-type",$row);
				
				$select.off('change');
				$select.on('change',function(){
					
					//Si marcan fecha
					if($select.val()=="Fecha")
						$(".date-format",$row).show();
					else
						$(".date-format",$row).hide();
					
					
					//Si marcan reemplazo
					if($select.val()=="Reemplazo")
					{
						$(".replace-format",$row).show();
						$(".custom-format",$row).hide();
						$(".custom-format",$row).val("");
					}
					else
					{
						$(".replace-format",$row).hide();
						$(".custom-format",$row).show();
						$(".replace-format",$row).val("");
					}
					
					//si ya tiene un valor asociado quitamos el mapeado
					$mapvalue=$(".node-map",$row);
					if($mapvalue.val()!="")
						$(".custom-format",$row).hide();
					else
						$(".custom-format",$row).show();
					});
				
				
				//si ya tiene un valor asociado quitamos el mapeado
				$mapvalue=$(".node-map",$row);
				if($mapvalue.val()!="")
					$(".custom-format",$row).hide();
				else
					$(".custom-format",$row).show();
				
			}
			
			
			this.generateNode=function(nodes,$html){
				
				
				
				for(var nodo in nodes){
					$html+="<li class='vcf-tree-node' data-node='"+nodo+"'>";
					$html+="<img src='http://findicons.com/files/icons/2330/open_source_icons_gcons/32/edit.png' class='vcf-icon-edit' />"+nodo;
					if (nodes.hasOwnProperty(nodo)) {

						var childnode=nodes[nodo];
						if(childnode instanceof Object){
							$tempHTML="<ul>"+self.generateNode(childnode,"")+"</ul>";
							if($tempHTML!="<ul></ul>")
								$html+=$tempHTML;
						}

					}
					
					$html+="<input type='hidden' class='node-map' /></li>"; 
				}
				
				return $html;				
			}
			
			this.generateHTMLNode=function($node){
				
					
			}
			
			this.setDragDrop=function(){
				
				jQuery(".vcf-pt-field").draggable({ revert: true });
 
				jQuery( "#vcf-pt-xml-tree li.vcf-tree-node" ).droppable({
					greedy: true,
					activeClass: "ui-state-hover",
					hoverClass: "ui-state-active",
					drop: function( event, ui ) {
						self.bindInputOnDrop(jQuery(this),ui.helper);
						return false;
					}
				});
				
				
				
				
			}
			
			this.unbindInputOnDrop=function($droppable){

					$(".node-map",$droppable).val("");
					$(".vcf-binded",$droppable).remove();
					$droppable.removeClass("binded");
					$droppable.removeClass("ui-state-highlight");
					//quitamos el valor custom si hubiera
					$(".custom-format",$droppable).val("");
					$(".custom-format",$droppable).show();
				
			}
			
			this.bindInputOnDrop=function($droppable,$draggable){
				//Quitamos anterior si hubiera y definimos nuevos valores y estilos
				self.unbindInputOnDrop($droppable);
				$(".node-map",$droppable).val($draggable.data("key"));
				$droppable.addClass( "ui-state-highlight binded" );
				self.createBindedUI($droppable,$draggable);
				self.generateEditLayerEvents($droppable);
				
				
			}
			
			this.createBindedUI=function($droppable,$draggable){
				$html=$("<div class='vcf-binded'>"+$draggable.html()+"<span class='vcf-map-delete'>X</span></div>");
				$droppable.prepend($html);
				
				$('.vcf-map-delete',$html).on('click',function(){
					$node=$(this).closest(".vcf-tree-node");
					$(".node-map",$node).val("");
					$(this).parent().remove();
					$node.removeClass("ui-state-highlight");
					self.unbindInputOnDrop($droppable);
					
				});
				
			}
			
            this.printTree();
			this.setDragDrop();
        });


    };

    $.fn.MeytelJsonTree.defaultOptions = { json: {}, }

})(jQuery);
