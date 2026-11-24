<?php
ob_start();

class VCBC_Media_Upload
{
    private $messages=array();

    public function __construct()
    {

        if(validate_media_form_fields())
        {

            $medioChannel=vcbc_get_medio_id($_POST["vcbc_media_channel"]);

            $upload_dir = wp_upload_dir();
            $domain=$_SERVER['HTTP_HOST'];
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            
			$host_domain=apply_filters( 'vcbc_video_host', $protocol.$domain );
			 
			
			$videourl = $host_domain.'/wp-content/uploads/vocentobc/temp/'.$_POST['vcbc_video_tmp_name'];
           
           

            $videoCC= new VC_CMSAPI_Video_CreateContainer();
           
            $videoCC->name=$_POST['vcbc_media_name'];
            
            $videoCC->description=$_POST['vcbc_media_small_desc'];
            
            $videoCC->long_description=$_POST['vcbc_media_desc'];


            $videoCC->tags=array();
            if($_POST['vcbc_media_categories'] && is_array($_POST['vcbc_media_categories']))
            $videoCC->tags=array_merge($_POST['vcbc_media_categories']);
            
            if($_POST['vcbc_media_tags'])
                $videoCC->tags=array_merge( $videoCC->tags,explode(",",$_POST['vcbc_media_tags']));

            array_push($videoCC->tags,$_POST['vcbc_media_main_category']);
            
            //Añadimos los tags creados por la red
            $mediotags=$medioChannel["vcbc_required_tags"];
            if(!is_array($mediotags))
                $mediotags=explode(",",$mediotags);

            $videoCC->tags=array_merge($videoCC->tags,$mediotags);

            //Limpiamos los repetidos
            $videoCC->tags=array_unique($videoCC->tags);


            //Limiamos vacios del array
            $videoCC->tags=array_filter($videoCC->tags);

            $videoCC->state=($_POST["vcbc_media_active"])?"ACTIVE":"INACTIVE";

            //Si no se publica se añade la etiqueta de "X:No_Publicar"
            if(!$_POST["vcbc_media_publish"]){
                array_push( $videoCC->tags,"X:No_Publicar");
            }


           

            if($medioChannel){
				
                $newVideo=VC_CMSAPI_Video::CREATE_AND_UPLOAD( $medioChannel["vcbc_medio_accid"],$videoCC,$videourl, $medioChannel["vcbc_medio_id"]);

                //El id del job es que se ha creado ok
                if($newVideo && property_exists($newVideo,"id")){
                    $this->addMessage("Video subido a Brightcove correctamente");
                }
                else{
                    $this->addErrorMessage("Ha ocurrido un error en la subida del video");
                }
            }
            else{

                $this->addErrorMessage("Ha ocurrido un error en la subida del video. Medio no disponible");
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
        $this->displayMessages();
?>
<div class="vcbc-fields-media">
    <form id="form-upload" method="post">
        <label for="vcbc_medio">Canal</label>
        <select required name="vcbc_media_channel"  id="vcbc_media_channel">
            <option value="" >-Seleccione un canal</option>
            <?php 
             foreach(vcbc_get_active_medios() as $medio){
                 echo "<option name='".$medio["vcbc_medio_name"]."' value='".$medio["vcbc_medio_id"]."'>".$medio["vcbc_medio_name"]."</option>";
             }
            ?>
        </select>
        <br />
        <br />

        <label for="vcbc_name">Titulo</label>
        <input required type="text" placeholder="Nombre descriptivo" id="vcbc_media_name" name="vcbc_media_name" value="" /><br />
        <br />
        <label for="vcbc_name">Descripci&oacute;n breve</label>
        <input required type="text" placeholder="Descripci&oacute;n breve" id="vcbc_media_small_desc" name="vcbc_media_small_desc" value="" /><br />
        <br />
        <label for="vcbc_name">Descripci&oacute;n</label>
        <textarea required placeholder="Descripci&oacute;n" id="vcbc_media_desc" name="vcbc_media_desc" value=""></textarea><br />
        
        <br />
        <label for="vcbc_name">Categor&iacute;a principal</label>
        <select required id="vcbc_media_main_category" name="vcbc_media_main_category">
        </select>
        <br />

        <br />
        <label for="vcbc_name">Categor&iacute;as secundarias</label>
        <select  id="vcbc_media_categories" name="vcbc_media_categories[]" multiple>
        </select>
        <br />
        
         <br />
        <label for="vcbc_name">Otros tags</label>
        <input  type="text" placeholder="Otros tags" id="vcbc_media_tags" name="vcbc_media_tags" value="" /><br />
        <br />
        <label for="vcbc_name">Activo</label>
        <input  type="checkbox" placeholder="Video activo" id="vcbc_media_active" name="vcbc_media_active" value="true" /><br />
        <br />
        <label for="vcbc_name">Publicado</label>
        <input  type="checkbox" placeholder="Video publicado" id="vcbc_media_publish" name="vcbc_media_publish" value="true" /><br />
        <br />

        <div class="uploader-zone">
        <label for="vcbc_name">
            <div id="container">
                <input id="pickfiles" type="button" class="button" value="Seleccionar video">
            </div>

        </label>
        <div class="file-progress-container">
            <div id="filelist"></div>
            <div id="uploadprogress">
                <div id="progresstext"></div>
            </div>
        </div>
            </div>
        <br />
        <input required type="hidden" placeholder="Nombre descriptivo" id="vcbc_video_tmp_name" name="vcbc_video_tmp_name" /><br />
        <br />

        <br />
        <br />
        <div class="vcbc-upload-submit"><input class="button button-primary button-large" style="float: right;" type="submit" value="Subir video" /></div>
    </form>
</div>

<script type="text/javascript">
    // Custom example logic
    jQuery(document).ready(function () {

        var guivideoid = guid();
        var uploaded = false;
       

        var uploader = new plupload.Uploader({
            runtimes: 'html5',
            browse_button: 'pickfiles',
            multi_selection: false,
            container: document.getElementById('container'),
            url: '<?php echo plugins_url( '/vocento-brightcove/vocento-brightcove/admin/media/vocento-media-uploader.php' ); ?>',
            filters: {
                max_file_size: '250mb',
                mime_types: [
                    { title: "Videos", extensions: "mp4,avi,wmv,mov" },
                ]
            },

            init: {
                PostInit: function () {
                    document.getElementById('filelist').innerHTML = '';

                },

                UploadComplete: function (e, t) {
                    uploaded = true;
                    jQuery('#form-upload').submit();
                },

                FilesAdded: function (up, files) {
                    
                  

                    plupload.each(files, function (file) {
                        up.settings.url = up.settings.url + "?name=" + guivideoid + "_" + file.name;
                        jQuery("#vcbc_video_tmp_name").val(guivideoid + "_" + file.name);
                        document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                },

                UploadProgress: function (up, file) {
                    console.log(file);
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                    jQuery('#uploadprogress').css("width", file.percent + "%");
                    jQuery('#progresstext').html(file.percent + "%");

                },

                Error: function (up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

        uploader.init();


        jQuery("#vcbc_media_channel").change(function() {
            if (jQuery("#vcbc_media_channel").val() != "")
            {
                var data = {
                    'action': 'vcbc_methode_sections',
                    'medio_id': jQuery("#vcbc_media_channel").val()
                };

                   
                jQuery("#vcbc_media_categories").attr("disabled", "true");
                jQuery("#vcbc_media_main_category").attr("disabled", "true");
                jQuery("#vcbc_media_categories").css("opacity", 0.2);
                jQuery("#vcbc_media_main_category").css("opacity", 0.2);

                jQuery("#vcbc_media_publish").attr("disabled", "disabled");
                console.log("disabled media puvlish")
                jQuery.post(ajaxurl, data, function (response) {
                  
                    try{
                        response = jQuery.parseJSON(response);
               
                        if (response.main) {
                            jQuery("#vcbc_media_main_category").html(response.main);
                            jQuery("#vcbc_media_main_category").removeAttr("disabled");
                            jQuery("#vcbc_media_main_category").css("opacity", 1);
                            jQuery("#vcbc_media_main_category").removeAttr("disabled");
                            jQuery("#vcbc_media_publish").removeAttr("disabled");
                            
                        }
                        else {
                            jQuery("#vcbc_media_main_category").attr("disabled");
                            jQuery("#vcbc_media_publish").removeAttr("checked");
                            
                            
                        }

                        if (response.secondary) {
                            jQuery("#vcbc_media_categories").html(response.secondary);
                            jQuery("#vcbc_media_categories").removeAttr("disabled");
                            jQuery("#vcbc_media_categories").css("opacity", 1);
                            jQuery("#vcbc_media_categories").removeAttr("disabled");
                            jQuery("#vcbc_media_publish").removeAttr("disabled");
                        }
                        else {
                            jQuery("#vcbc_media_categories").attr("disabled");
                            jQuery("#vcbc_media_publish").removeAttr("checked");
                            
                           
                        }


                    }
                    catch (err) {
                        alert("No se puede procesar el fichero de secciones. Revise la URL configurada")
                    }

                });
            }
        });


        // Handle the case when form was submitted before uploading has finished
        jQuery('#form-upload').submit(function (e) {

            if (!uploaded) {
                uploader.start();
                return false;
            }
            return true;

        });

    });


    function guid() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
              .toString(16)
              .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
          s4() + '-' + s4() + s4() + s4();
    }

</script>

<?php

    }



    
    

}