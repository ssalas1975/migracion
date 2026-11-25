<?php
class VCF_Network_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	private $messages=array();

    /**
     * Start up
     */
    public function __construct()
    {
		add_action( 'network_admin_menu', function(){
         
               add_menu_page( "Vocento Feeds", "Vocento feeds", 'manage_options', "network_vcf_settings",    array( $this, 'create_vocentovcf_page' ) );
	

        } );
		
       

	

        //Registramos los settings
        add_action( 'admin_init', function(){
          
            register_setting(
                'vocentovcf_option_group', // Option group
                'vocentovcf_options', // Option name
                array( $this, 'sanitize' ) // Sanitize
            );

        } );




        //solo carge la libreria en las opciones
		add_action( 'admin_enqueue_scripts', function( $hook )
            {
                $screen = get_current_screen();       
                if ( 'toplevel_page_vocentovcf_settings' == $screen->base ) {
                    wp_enqueue_media();
                } else return;
            } );

		wp_enqueue_script( 'custom-header' );



        //Si es un post guardamos
        if($_POST['submit-vcf-create-nw'])
            $this->save();

    }

    
    
    public function create_vocentovcf_page()
    {
        // Set class property
        $this->options = get_site_option( 'vocentovcf_options' );
?>
<div class="wrap theme-mujer">
    <h2>Creación de nuevo feed global</h2>
    <br />
     
    <br />
    <?php 
        $this->displayMessages();
        $url = add_query_arg($this->composeFormParams("network_vcf_settings","",""), network_admin_url('admin.php'));
    ?>
      <div class="template-info" style="float:none; margin-bottom:20px;">Puede ver una fuente de datos estándar <a target="_blank" href="<?php echo plugins_url('../assets/sample.xml', __FILE__)?>">aquí</a></div>
   

    <form method="post" action="<?php echo $url?>" enctype="multipart/form-data">

        <div class="vcf-fields">
            <label for="vcf_name">Nombre</label>
            <input required type="text" placeholder="Nombre del feed" id="vcf_name" name="vcf_name" value="" /><br />
            <br />
            <label for="vcf_slug">Slug</label>
            <input required type="text" placeholder="Slug del feed" id="vcf_slug" name="vcf_slug" value="" /><br />
            <br />
            <label for="vcf_xslt">XSLT</label>
            <input required type="file" placeholder="XSLT" id="vcf_xslt" name="vcf_xslt" value="" accept=".xslt" /><br />
               <br />
                        <label for="vcf_slug">Content type</label>
                        <input required type="text" placeholder="content type respuesta" id="vcf_content_type" name="vcf_content_type" value="<?php echo $this->feed["content_type"]?>" /><br />
                      
             <br />
            <label for="vcf_poststype">Post types</label>
		   
		
		    <?php 
        foreach ( vcf_get_post_types_mu()  as $post_type ) {?>
            <div class="vcf-pt-ck"><input style="width: auto !important;" name="vcf_poststype[]" type="checkbox" '.$checked.' value="<?php echo $post_type ?>" ><?php echo $post_type ?></div>
		    <?php } ?>
             <br />
             <br />
            <label for="vcf_slug">Post propios</label>
            <input  type="checkbox" placeholder="Hijo" id="vcf_child" name="vcf_child" value="true" style="width:auto !important"/><br />
		    
       
             <?php    submit_button('Guardar','button button-primary','submit-vcf-create-nw');  ?>
        </div>
       

    </form>

    <div class="feed-list">
        <h2>Feeds actuales</h2>

        <?php 

        if( isset( $this->options['vcf_feeds_config'] )){
            $jsonconfig=json_decode($this->options['vcf_feeds_config'],true);
            echo "<table id='vcf-table' class='vcf-c-lines'>";

            echo "<thead class='line-head'>";
            echo "<tr>";
            echo "<th class='name'>NOMBRE</th>";
            echo "<th class='slug'>SLUG</th>";
            echo "<th class='slug'>XSLT</th>";
            echo "<th class='replica'>Post propios</th>";
            echo "<th class='view'></th>";
            echo "<th class='editar'></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach($jsonconfig as $feed){

                $url = add_query_arg($this->composeFormParams("network_vcf_settings","edit",$feed["id"]), network_admin_url('admin.php'));
                
                echo "<tr >";
                echo "<td class='name'>".$feed["name"]."</td>";
                echo "<td class='slug'>".$feed["slug"]."</td>";
                echo "<td class='slug'>".$feed["xslt"]."</td>";
                echo "<td class='replica'>".(($feed["rpl"])?'<img  src="'. plugins_url( '../assets/img/icon-check.png', __FILE__ ).'" />':"&nbsp;")."</td>";
                echo "<td class='view'><a href='". compose_feed_link($feed)."' target='_blank'>ver</a></td>";
                echo "<td class='editar'><a href='".$url."'>editar</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            
        }
        
        ?>



    </div>


</div>

<script>
    jQuery(document).ready(function () {
        jQuery('#vcf-table').DataTable();
    });
    </script>
<?php
    }

    
	public function appendToConfig($name,$slug,$xsl,$id,$posttypes,$replicate,$content_type){
		
        $this->options = get_site_option( 'vocentovcf_options');
		$config=$this->options['vcf_feeds_config'];
		if(!$config)
			$config="[]";
		
		$configClass=json_decode($config,true);
		
		
		//creamos nuevo item array
		$newConfig=array();
		$newConfig["name"]=$name;
		$newConfig["slug"]=$slug;
		$newConfig["xslt"]=$xsl;
		$newConfig["id"]=$id; //uniqid ("vcfc_");
        $newConfig["pt"]=$posttypes;
        $newConfig["rpl"]=$replicate;
        $newConfig["content_type"]=$content_type;
       
		if(!$this->existsSlug($configClass,$newConfig)){
			//insert
			array_push($configClass,$newConfig);
            $this->options['vcf_feeds_config']=json_encode($configClass);
            update_site_option('vocentovcf_options',$this->options,true);
            $this->addMessage("Feed creado");
			return true;
		}
		return false;
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

    public function save(  )
    {
        

        if(isset($_FILES['vcf_xslt']['name']))
		{
			
			$xsltName=$this->move_file_xslt();
			
            if(!$xsltName){
                $this->addErrorMessage ("No se ha podido subir el fichero");
                return;
            }
            
		    if($xsltName && isset( $_POST['vcf_name'] ) && isset( $_POST['vcf_slug'] ))
		    {
                if($_POST['vcf_name']  &&  $_POST['vcf_slug'] )
                {
                    if(!$this->appendToConfig($_POST['vcf_name'],$_POST['vcf_slug'],$_FILES['vcf_xslt']['name'],$xsltName,$_POST['vcf_poststype'],$_POST['vcf_child'],$_POST['vcf_content_type'])){
                        $this->addErrorMessage ("El slug ya existe");
                    }
                }
                else{
                    $this->addErrorMessage("Debe rellenar todos los campos");
                }
                
		    }
        }
        else{
            $this->addErrorMessage("Debe rellenar todos los campos");
        }
        
    }

    public function move_file_xslt(){
		
		$uploads_dir =get_upload_dir();
		 $this->addErrorMessage ($_FILES['vcf_xslt']['tmp_name']);
		
		
		$tmp_name = $_FILES['vcf_xslt']['tmp_name'];
		$name = $_FILES['vcf_xslt']['name'];
        $name=uniqid ("vcfc_");
		if(move_uploaded_file($tmp_name, "$uploads_dir/$name"))
        {
            $this->filepath="$uploads_dir/$name";
            return $name;
        }
        else{
            return false;
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