<?php
class VCBC_Site_List_Medio
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	private $messages=array();
    private $network_options;
    /**
     * Start up
     */
    public function __construct()
    {
		
		//refrescamos los tokens que hayan caducado
        vcbc_init_tokens();
		
        //Registramos los settings
        add_action( 'admin_init', function(){
          
            register_setting(
                'vocentovcbc_option_group', // Option group
                'vocentovcbc_options', // Option name
                array( $this, 'sanitize' ) // Sanitize
            );

        } );

        //Recuperamos state
        $this->get_state();





    }

    
    public function get_state(){
        if(isset($_GET["state"]))
        {
            switch($_GET["state"]){
                case "deleted":
                    $this->addMessage ("Medio eliminado correctamente");
                    break;
            }
        }
    }

    public function render_network_medios(){
    
        if(vcbc_plugin_active_network()){
            $this->network_options = get_site_option( 'vocentovcbc_options' );

            if( isset($this->network_options['vcbc_medios'] )){

                $jsonconfig=json_decode($this->network_options['vcbc_medios'],true);

                foreach($jsonconfig as $medio){

                    if(VC_CMSAPI_OAuth::GET_CURRENT_TOKEN($medio['vcbc_medio_accid'],$medio['vcbc_medio_id']))
                        $icon=plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/active.png' );
                    else
                        $icon=plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/inactive.png' );

                    echo "<tr >";
                    echo "<td class='name'>".$medio["vcbc_medio_name"]."</td>";
                    echo "<td class='name'>".$medio["vcbc_medio_accid"]."</td>";
                    echo "<td class='url'>". VC_CMSAPI_Video::COUNT($medio["vcbc_medio_accid"],false,false,$medio["vcbc_medio_id"])."</td>";
                    echo "<td class='state' style='text-align:center;'><img src='$icon'/></td>";
                    echo "<td class='edit'  style='text-align:center;'><img src='". plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/network.png' )."'/></td>";
                    
                    echo "</tr>";
                }
            }
        }
    
    }

    
    public function render_page()
    {
        // Set class property
        $this->options = get_option( 'vocentovcbc_options' );
     
       
?>

    <div class="wrap vcbc-add-medio-wrapper">
    <h2>Vocento BC: <span>Listado de medios</span></h2>
    <br />
    <?php
        $this->displayMessages();
    ?>
    <div class="bc-medio-list">
        <?php 


        echo "<table id='vcf-table' class='vcf-c-lines'>";
        echo "<thead class='line-head'>";
        echo "<tr>";
        echo "<th class='name' style='text-align:left;'>Medio</th>";
        echo "<th class='name' style='text-align:left;'>Account ID</th>";
        echo "<th class='url' style='text-align:left;'>Nº videos</th>";
        echo "<th class='url' >Estado</th>";
        echo "<th class='edit'></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $this->render_network_medios();

        if( isset($this->options['vcbc_medios'] )){

            $jsonconfig=json_decode($this->options['vcbc_medios'],true);
            foreach($jsonconfig as $medio){

                if(VC_CMSAPI_OAuth::GET_CURRENT_TOKEN($medio['vcbc_medio_accid'],$medio['vcbc_medio_id']))
                    $icon=plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/active.png' );
                else
                    $icon=plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/inactive.png' );

                $edit_url = add_query_arg(vcbv_compose_form_params("site_vcbc_add_medio","bc_ec_medio",$medio["vcbc_medio_id"]), admin_url('admin.php'));
                echo "<tr >";
                echo "<td class='name'>".$medio["vcbc_medio_name"]."</td>";
                echo "<td class='name'>".$medio["vcbc_medio_accid"]."</td>";
                echo "<td class='url'>". VC_CMSAPI_Video::COUNT($medio["vcbc_medio_accid"],false,false,$medio["vcbc_medio_id"])."</td>";
                echo "<td class='state' style='text-align:center;'><img src='$icon'/></td>";
                echo "<td class='edit'  style='text-align:center;'><a href='$edit_url'><img src='". plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/edit.png' )."'/></a></td>";
                echo "</tr>";
            }
  
        }

        echo "</tbody>";
        echo "</table>";
        
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

}