<?php
class VCBC_Media_Edit
{
    private $messages=array();
    private $video_id;
    private $medio_id;
    private $video;
    private $medio;

    public function __construct($vcbc_video_id,$vcbc_medio_id)
    {

        if(!isset($vcbc_video_id) || !isset($vcbc_medio_id))
            return;

        $this->medio=vcbc_get_medio_id($vcbc_medio_id);
        
        if(!$this->medio)
            return;

        $this->video=VC_CMSAPI_Video::GET_VIDEO_BY_ID($this->medio["vcbc_medio_accid"],$vcbc_video_id,$this->medio["vcbc_medio_id"]);

        $this->medio_id=$vcbc_medio_id;

        $this->video_id=$vcbc_video_id;

        if($this->video && validate_media_edit_form_fields())
        {

            $this->video_id=$vcbc_video_id;

            
            $videoCC= new VC_CMSAPI_Video_CreateContainer();
            
            $videoCC->name=$_POST['vcbc_media_name'];
            
            $videoCC->description=$_POST['vcbc_media_small_desc'];
            
            $videoCC->long_description=$_POST['vcbc_media_desc'];

            $videoCC->link->text="";

            $videoCC->link->url="";

            $post_tags=$_POST['vcbc_media_tags'];

            $videoCC->tags=array();

            $videoCC->tags=array_merge($_POST['vcbc_media_categories']);
            
            if( $videoCC->tags==null)
                $videoCC->tags=array();

            if($post_tags)
                $videoCC->tags=array_merge( $videoCC->tags,explode(",",$post_tags));

            array_push($videoCC->tags,$_POST['vcbc_media_main_category']);

            //Añadimos los tags creados por la red
            $mediotags=$this->medio["vcbc_required_tags"];
            if(!is_array($mediotags))
                $mediotags=explode(",",$mediotags);
            $videoCC->tags=array_merge($videoCC->tags,$mediotags);

            //Limpiamos los repetidos
            $videoCC->tags=array_unique($videoCC->tags);


            //Limpiamos vacios del array
            $videoCC->tags=array_filter($videoCC->tags);

            $videoCC->state=($_POST["vcbc_media_active"])?"ACTIVE":"INACTIVE";

            //Si no se publica se añade la etiqueta de "X:No_Publicar"
            if(!$_POST["vcbc_media_publish"]){
                array_push( $videoCC->tags,"X:No_Publicar");
            }


           

            if($this->medio){

                $newVideo=VC_CMSAPI_Video::UPDATE( $this->medio["vcbc_medio_accid"], $this->video_id,$videoCC,$this->medio["vcbc_medio_id"]);

                //El id del job es que se ha creado ok
                if($newVideo && !is_array($newVideo)  && !property_exists($newVideo,"error_code")){
                    $this->addMessage("Video editado correctamente");
                    $this->video=$newVideo;
                }
                else{

                    $msg="Ha ocurrido un error al editar el video";
                    if(is_array($newVideo) && property_exists($newVideo[0],"error_code"))
                        $msg.=":"+$newVideo[0]->error_code;

                    $this->addErrorMessage($msg);
                }
            }
            else{

                $this->addErrorMessage("Ha ocurrido un error en la edicion del video. Medio no disponible");
            }
        }

    }

    public function addErrorMessage($error)
    {
		array_push($this->messages,array('class'=>'notice-error', 'msg'=>$error));
	}
	
    public function addMessage($mesage)
    {
		array_push($this->messages,array('class'=>'notice-success', 'msg'=>$mesage));
	}

	public function displayMessages()
    {
		foreach($this->messages as $msg){
			echo "<div class='notice  ".$msg['class']."'>".$msg['msg']."</div>";
		}
	}

    

    public function render_page()
    {
        
        if(!$this->medio || !$this->video)
            return;



       
?>
<div class="vcbc-fields-media">
   
    <?php  $this->displayMessages(); ?>

    <form id="form-edit-video" method="post">
     
          
         <input required type="hidden"  id="vcbc_video_id" name="vcbc_video_id" value="<?php echo $this->video_id?>" />
       
         <input required type="hidden"  id="vcbc_medio_id" name="vcbc_medio_id" value="<?php echo  $this->medio_id?>" />
       

        <label for="vcbc_name">Titulo</label>
        <input required type="text" placeholder="Nombre descriptivo" id="vcbc_media_name" name="vcbc_media_name" value="<?php echo $this->video->name?>" /><br />
        <br />
        <label for="vcbc_name">Descripci&oacute;n breve</label>
        <input required type="text" placeholder="Descripci&oacute;n breve" id="vcbc_media_small_desc" name="vcbc_media_small_desc" value="<?php echo $this->video->description?>" /><br />
        <br />
        <label for="vcbc_name">Descripci&oacute;n</label>
        <textarea required placeholder="Descripci&oacute;n" id="vcbc_media_desc" name="vcbc_media_desc" ><?php echo $this->video->long_description?></textarea><br />
        <br />

        <?php if($this->medio["vcbc_vocento_url_methode"]!="") {?>
            <label for="vcbc_name">Categor&iacute;a principal</label>
            <select required id="vcbc_media_main_category" name="vcbc_media_main_category">
                <?php echo vcbc_secciones_methode_to_dropdown($this->medio["vcbc_vocento_url_methode"],$this->medio["vcbc_vocento_url_methode_tmpl"],true,$this->video->tags) ?>
            </select>
            <br />
            <br />
            <label for="vcbc_name">Categor&iacute;as secundarias</label>
            <select id="vcbc_media_categories" name="vcbc_media_categories" multiple>
                 <?php echo vcbc_secciones_methode_to_dropdown($this->medio["vcbc_vocento_url_methode"],$this->medio["vcbc_vocento_url_methode_tmpl"],false,$this->video->tags) ?>
            </select>
            <br />
        <?php } ?>
        <br />

        <label for="vcbc_name">Otros tags</label>
        <input type="text" placeholder="Otros tags" id="vcbc_media_tags" name="vcbc_media_tags" value="<?php echo implode(",",vcbc_get_user_tags($this->video->tags,$this->medio))?>" /><br />
        <br />
         <label for="vcbc_name">Todos los tags</label>
        <textarea disabled ><?php echo implode(",",$this->video->tags)?></textarea><br />
        <br />

        <label for="vcbc_name">Activo</label>
        <input type="checkbox" placeholder="Video activo" id="vcbc_media_active" name="vcbc_media_active" value="true" <?php echo (($this->video->state=="ACTIVE")?"checked":"")?> /><br />
        <br />
        <?php if($this->medio["vcbc_vocento_url_methode"]!="") {?>
        <label for="vcbc_name">Publicado</label>
        <input type="checkbox" placeholder="Video publicado" id="vcbc_media_publish" name="vcbc_media_publish" value="true" <?php echo ((vcbc_video_is_published($this->video->tags))?"checked":"")?> /><br />
        <br />
        <?php } ?>
        <br />
        <br />
        <div class="submit-edit-container">
            <input class="button button-primary button-large" style="float: right;" id="saveVideo" type="button" value="Guardar video" /></div>
    </form>
</div>

<script type="text/javascript">
    // Custom example logic
   



        jQuery("#saveVideo").click(function () {

                        var formData = JSON.parse(JSON.stringify(jQuery('#form-edit-video').serializeArray()));

            var data = { action: 'vcbc_edit_video_post' };
            jQuery(formData).each(function (i, val) {
                if (val.name == 'vcbc_media_categories') {
                    if (!data[val.name])
                        data[val.name] = [];

                    data[val.name].push(val.value);
                }
                else {
                    data[val.name] = val.value;
                }
               

            });

            jQuery.post(ajaxurl, data, function (response) {

                try {

                   
                    jQuery("#vcbc-edit-video-form").html(response);

                }
                catch (err) {
                    alert("No se puede guardar.")
                }

            });

         

        });


        jQuery("#vcbc_media_channel").change(function () {
            if (jQuery("#vcbc_media_channel").val() != "") {
                var data = {
                    'action': 'vcbc_methode_sections',
                    'medio_id': jQuery("#vcbc_media_channel").val()
                };


                jQuery("#vcbc_media_categories").attr("disabled", "true");
                jQuery("#vcbc_media_main_category").attr("disabled", "true");
                jQuery("#vcbc_media_categories").css("opacity", 0.2);
                jQuery("#vcbc_media_main_category").css("opacity", 0.2);
                jQuery.post(ajaxurl, data, function (response) {

                    try {
                        response = jQuery.parseJSON(response);

                        if (response.main) {
                            jQuery("#vcbc_media_main_category").html(response.main);
                            jQuery("#vcbc_media_main_category").removeAttr("disabled");
                            jQuery("#vcbc_media_main_category").css("opacity", 1);
                        }
                        if (response.secondary) {
                            jQuery("#vcbc_media_categories").html(response.secondary);
                            jQuery("#vcbc_media_categories").removeAttr("disabled");
                            jQuery("#vcbc_media_categories").css("opacity", 1);
                        }


                    }
                    catch (err) {
                        alert("No se puede procesar el fichero de secciones. Revise la URL configurada")
                    }

                });
            }
        });









</script>

<?php

    }



    
    

}