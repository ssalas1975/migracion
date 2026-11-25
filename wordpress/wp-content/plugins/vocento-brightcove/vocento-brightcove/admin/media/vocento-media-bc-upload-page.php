<?php
ob_start();

class VCBC_Media_Upload_Page
{
    private $vcmediaUpload;
    private $vcmediaSelect;
    

    public function __construct()
    {
		//Refrescamos tokens
        vcbc_init_tokens();

        //Init inside pages
        $this->vcmediaUpload=new VCBC_Media_Upload();
        $this->vcmediaSelect=new VCBC_Media_Select();
        
    }

    

    

    public function render_page()
    {
        ?>
<div id="tabs-vcbc-upload">
    <ul>
        <li><a href="#tabs-vcbc-upload">Subir video</a></li>
        <li><a  href="#tabs-vcbc-select">Seleccionar</a></li>

    </ul>
    <div id="tabs-vcbc-upload" class="vcbc-tab">
        <?php 
        $this->vcmediaUpload->render_page();
        ?>
    </div>
    <div id="tabs-vcbc-select" class="vcbc-tab">
        <?php 
        $this->vcmediaSelect->render_page();
        ?>
    </div>

</div>

<script type="text/javascript">
    // Custom example logic
    jQuery(document).ready(function () {
        jQuery("#tabs-vcbc-upload").tabs();

        <?php 
         //Si estamos en seleccion activamos esa pestaña
        if (isset($_POST["vcbc_select_control"])){ 
        ?>
        jQuery("#tabs-vcbc-upload").tabs("option", "active", 1);
        <?php }?>
    });

</script>

<?php

    }



    
    

}