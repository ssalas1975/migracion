<?php
class VCF_Edit
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	private $messages=array();
    private $feed;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_vocentovcf_page' ) );
        add_action('admin_enqueue_scripts', array( $this, 'vocentovcf_enqueue' ));
        
       
		
        //solo carge la libreria en las opciones
		add_action( 'admin_enqueue_scripts', function( $hook )
            {
               
             


                $screen = get_current_screen();       
                if ( 'toplevel_page_vocentovcf_settings' == $screen->base ) {
                    wp_enqueue_media();
                } else return;
            } );

		wp_enqueue_script( 'custom-header' );

      
       
      

        $this->setCurrentFeed();

        if(!$this->feed)
        {
            $url = add_query_arg($this->composeFormParams("vocentovcf_settings","",""), admin_url('options-general.php'));
            header('Location: '. $url);
            return;
        }


        if($_POST['submit-vcf-edit'])
            $this->save();

        if($_POST['submit-template-vcf'])
            $this->save_template();

        if($_POST['submit-delete-vcf']){
            $this->delete_template();
            $url = add_query_arg($this->composeFormParams("vocentovcf_settings","",""), admin_url('options-general.php'));
            header('Location: '. $url);
            return;
        }

    }

 

    public function vocentovcf_enqueue($hook) {

        wp_enqueue_style( 'vocentotvcf_styles', plugins_url('../assets/css/jquery-ui.css', __FILE__)  );
       
        wp_enqueue_script( 'ajax-script-ui',
				plugins_url( '../assets/js/jquery-ui.min.js', __FILE__ ),
				array('jquery')
			);

        wp_localize_script( 'ajax-script-ui', 'ajax_vcf', array(
                     'ajax_url' => admin_url( 'admin-ajax.php' )
        ));
	}

    /**
     * Add options page
     */
    public function add_vocentovcf_page()
    {
		
		// This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Vocento Feeds', 
            'manage_options', 
            'vocentovcf_settings', 
            array( $this, 'create_vocentovcf_page' )
        );
		
	}

    /**
     * Options page callback
     */
    public function create_vocentovcf_page()
    {
        // Set class property
        $this->options = get_option( 'vocentovcf_options' );
?>
<div class="wrap theme-mujer">
    <h2>Editar feed</h2>
    <br />
    <div class="template-info" style="float:none; margin-bottom:20px;">Puede ver una fuente de datos est&aacute;ndar <a target="_blank" href="<?php echo plugins_url('../assets/sample.xml', __FILE__)?>">aqu&iacute;</a></div>
    <div id="tabs">
          <ul>
            <li><a href="#tabs-general">Datos generales</a></li>
            <li><a href="#tabs-source">Fuente XML</a></li>
            <li><a href="#tabs-template">Plantilla</a></li>
            <li><a href="#tabs-preview">Preview</a></li>
          </ul>
          <div id="tabs-general">
            
               <?php 
                $this->displayMessages();
                $url = add_query_arg($this->composeFormParams("vocentovcf_settings","edit",$this->feed[id]), admin_url('options-general.php'));
                ?>

                <form method="post" action="<?php echo $url?>" enctype="multipart/form-data">

                    <div class="vcf-fields">
                        <label for="vcf_name">Nombre</label>
                        <input required type="text" placeholder="Nombre del feed" id="vcf_name" name="vcf_name" value="<?php echo $this->feed["name"]?>" /><br />
                        <br />
                        <label for="vcf_slug">Slug</label>
                        <input required type="text" placeholder="Slug del feed" id="vcf_slug" name="vcf_slug" value="<?php echo $this->feed["slug"]?>" /><br />
                        <br />
                        <label for="vcf_xslt">XSLT</label>
                        <input type="file" placeholder="XSLT" id="vcf_xslt" name="vcf_xslt" value="" accept=".xslt" /><br />
                         <br />
                         <label for="current-xslt">Actual XSLT</label>
                         <div name="current-xslt" class=""><?php echo $this->feed["xslt"]?></div><br />
                         <br />
                        <label for="vcf_slug">Content type</label>
                        <input required type="text" placeholder="content type respuesta" id="vcf_content_type" name="vcf_content_type" value="<?php echo $this->feed["content_type"]?>" /><br />
                      
                        <br />
                        <label for="vcf_poststype">Post types</label>
		                <?php 
                        $posts_types=vcf_get_post_types_by_blog(get_current_blog_id());

                        foreach ( $posts_types  as $post_type ) {
                               
                            $checked='';
                                if(is_array($this->feed['pt']) && array_search($post_type, $this->feed['pt'])!==false)
                                    $checked='checked';
                                                     
                        ?>

                        <div class="vcf-pt-ck"><input style="width: auto !important;" name="vcf_poststype[]" type="checkbox" <?php echo $checked ?> value="<?php echo $post_type ?>" ><?php echo $post_type ?></div>
                       
		                <?php }  ?>
                        <br />
                         <br /> <label for="current-xslt">Url</label>
                         <div name="current-xslt" class=""><a href="<?php echo compose_feed_link($this->feed)?>" target="_blank">Ver actual</a></div>
                       
                         <div class="submits">
                    <?php    submit_button('Guardar','button button-primary','submit-vcf-edit');  ?>

                    <?php    submit_button('Eliminar','button button-primary alert','submit-delete-vcf');  ?>
                   </div>

                    </div>
       

                </form>

          
          </div>
         <?php
        $currentFeedXML=get_sample_xml_raw_posttype($this->feed["pt"]);                     
        ?>

         <div id="tabs-source" >
             <textarea readonly style="width:100%; height:600px;">  <?php echo process_xsl( get_xslt_filepath_clone(),$currentFeedXML);?></textarea>
         </div>

         <div id="tabs-template">
             <form method="post" action="<?php echo $url?>">
                 <div class="template-info">Puede editar desde aqui el XSLT. Para ver los cambios sin guardar active la pesta&ntilde;a "Preview" y refresque los cambios</div><?php    submit_button('Guardar','button button-primary','submit-template-vcf');  ?>
                <textarea style="width:100%; height:600px;" name="xslt_template" id="xslt_template"><?php echo htmlentities(get_sample_xslt($this->feed["id"])) ?></textarea>
             </form>
         </div>
         <div id="tabs-preview">
            <input type="button" id="preview_btn" value="Refrescar" class="button button-primary" /> <img class="vcf-loading" src="<?php echo plugins_url( '../assets/img/loading.gif', __FILE__ )?>" />
           <textarea readonly style="width:100%; height:600px;" id="preview-area"><?php echo process_xsl(  get_xslt_filepath($this->feed["id"]),$currentFeedXML);?></textarea>
         </div>
          
    </div>
 
    
   
</div>
<script>
    jQuery(document).ready(function () {

        jQuery("#submit-delete").click(function () {
            return confirm("¿Confirma que desea eliminar el medio? Los videos asociados dejarán de funcionar");
        });

        jQuery("#tabs").tabs();

        jQuery("#preview_btn").click(function () {

            if (!jQuery(this).hasClass("disabled")) {
                jQuery(".vcf-loading").show();
                jQuery(this).addClass("disabled");
                jQuery.post(ajax_vcf.ajax_url, {
                    action: "vcfpreview_xslt",
                    xslt: jQuery("#xslt_template").val(),
                    contentType: "text/xml",
                    dataType: "text",
                    processData: false,
                }, function (data) {
                    jQuery("#preview-area").val(data);
                    jQuery(".vcf-loading").hide();
                    jQuery("#preview_btn").removeClass("disabled");

                });
            }

        });
        
    });


    </script>
<?php
    }

  
	public function editConfig($name,$slug,$id,$xslt,$posttypes,$content_type){
		
        $this->options = get_option( 'vocentovcf_options');
		$config=$this->options['vcf_feeds_config'];
		if(!$config)
			$config="[]";
		
		$configClass=json_decode($config,true);
		
		foreach($configClass as &$config){
			if($config["id"]==$_GET["id"])
            {
                if($name)
                $config["name"]=$name;
                
                if($slug)
                $config["slug"]=$slug;

                if($xslt)
                    $config["xslt"]=$xslt;
                 if($xslt)
                    $config["xslt"]=$xslt;
                 if($posttypes)
                     $config['pt']=$posttypes;
                 if($content_type)
                     $config['content_type']=$content_type;
                
                //Actualizamos el feed actual
                $this->feed=  $config;
            }
		}
      

	
        $newvalue=json_encode($configClass);
        if($newvalue)
        {
            $this->options['vcf_feeds_config']=$newvalue;
         
            update_option('vocentovcf_options',$this->options,true);
            $this->addMessage("Feed actualizado");
            return true;
        }

	}

    public function setCurrentFeed()
    {
        $this->options = get_option( 'vocentovcf_options');
		$config=$this->options['vcf_feeds_config'];
		if(!$config)
			$config="[]";
		
		$configClass=json_decode($config,true);
		

		foreach($configClass as $config){
			if($config["id"]==$_GET["id"])
            {
                $this->feed=$config;
            }
		}
	
	}

	
	public function existsSlug($configClass,$newConfig)
    {
		foreach($configClass as $config){
			if($config["slug"]==$newConfig["slug"])
				return true;
		}
		return false;
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
    

    public function save_template( )
    {
        if(isset( $_POST['xslt_template'] ))
		 {
             if(file_put_contents (  get_xslt_filepath($this->feed["id"]) , $_POST['xslt_template']))
                $this->addMessage("Template actualizado");
             else
                 $this->addErrorMessage("No se ha podido guardar el template");
         }
    }

    public function delete_template( )
    {
        
        if($this->deleteFeed($this->feed["id"])){
            unlink(get_xslt_filepath($this->feed["id"]));
            $this->addMessage("Feed eliminado");
        }
        else
        {
            $this->addErrorMessage("No se ha podido eliminar el feed");
        }
        
    }

    public function deleteFeed($id){
		
        $this->options = get_option( 'vocentovcf_options');
		$config=$this->options['vcf_feeds_config'];
		if(!$config)
			$config="[]";
		
		$configClass=json_decode($config,true);
		$index=0;
        $found=false;
		foreach($configClass as &$config){
			if($config["id"]==$_GET["id"])
            {
                $found=true;
                break;
            }
            $index++;
		}
        if($found)
            array_splice($configClass,$index,1);
        

        
        $newvalue=json_encode($configClass);
        if($newvalue)
        {
            $this->options['vcf_feeds_config']=$newvalue;
            
            update_option('vocentovcf_options',$this->options,true);
            
            return true;
        }

	}



        
    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function save( )
    {
        

        if($_FILES['vcf_xslt']['name'])
		{
			
			$xsltName=$this->move_file_xslt();

            if(!$xsltName){
                $this->addErrorMessage ("No se ha podido subir el fichero");
                return;
            }
        }
        else {
            $xsltName=$this->feed["xslt"];
        }
            
		if(isset( $_POST['vcf_name'] ) && isset( $_POST['vcf_slug'] ))
		 {
                
             if(!$this->editConfig($_POST['vcf_name'],$_POST['vcf_slug'],$this->feed["id"],$xsltName,$_POST['vcf_poststype'],$_POST['vcf_content_type'] )){
                 $this->addErrorMessage ("El slug ya existe");
              }
			    
                
		 }
        else{
            $this->addErrorMessage("Debe rellenar todos los campos");
        }
        
        
    }

    public function move_file_xslt(){
		
		$uploads_dir =get_upload_dir();
		$tmp_name = $_FILES['vcf_xslt']['tmp_name'];
		$filename = $_FILES['vcf_xslt']['name'];
        $name=$this->feed['id'];
		if(move_uploaded_file($tmp_name, "$uploads_dir/$name"))
        {
		    $this->filepath="$uploads_dir/$name";
            return $filename;
        }
        else{
            return false;
        }
		
	}

 

    
	
    /** 
     * Get the settings option array and print one of its values
     */
    public function vcf_xslt_callback()
    {
        echo "<div class='current-file-vcf'>Actualmente usando '".$this->feed["xslt"]."'</div>";

        printf(
            '<input type="file" placeholder="XSLT" id="vcf_xslt" name="vcf_xslt" value="%s" />',
            isset( $this->feed['xslt'] ) ? esc_attr( $this->feed['xslt']) : ''
        );
    }
	
	
	/** 
     * Get the settings option array and print one of its values
     */
    public function vcf_name_callback()
    {
		
        printf(
            '<input type="text" placeholder="Nombre del feed" id="vcf_name" name="vcf_name" value="%s" />',
            isset( $this->feed['name'] ) ? esc_attr( $this->feed['name']) : ''
        );
    }
	
	/** 
     * Get the settings option array and print one of its values
     */
    public function vcf_slug_callback()
    {
		
        printf(
            '<input type="text" placeholder="Slug del feed" id="vcf_slug" name="vcf_slug" value="%s" />',
            isset( $this->feed['slug'] ) ? esc_attr( $this->feed['slug']) : ''
        );
    }
	
	/** 
     * Get the settings option array and print one of its values
     */
    public function vcf_feeds_config_callback()
    {
		
		if( isset( $this->options['vcf_feeds_config'] )){
			$jsonconfig=json_decode($this->options['vcf_feeds_config'],true);
			echo "<div class='vcf-c-lines'>";
			foreach($jsonconfig as $feed){

                $url = add_query_arg($this->composeFormParams("vcf_edit_feed",$feed["id"]), admin_url('admin.php'));

				echo "<div class='line'>";
				echo "<div class='name'>".$feed["name"]."</div>";
				echo "<div class='slug'>".$feed["slug"]."</div>";
				echo "<div class='slug'>".$feed["xslt"]."</div>";
				echo "<div class='view'><a href='/".$feed["slug"]."' target='_blank'>ver</a></div>";
                echo "<div class='editar'><a href='".$url."'>editar</a></div>";
				echo "</div>";
			}
            echo "</div>";
			
		}
		
        
    }



    public function composeFormParams($page,$action,$id)
	{
		$params= array(
            'page'=>$page
        );
		
		if($action)
			$params['action']=$action;

        if($id)
			$params['id']=$id;
		
		return $params;
	}



}