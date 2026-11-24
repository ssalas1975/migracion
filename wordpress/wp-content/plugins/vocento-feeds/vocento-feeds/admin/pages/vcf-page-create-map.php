<?php

class vcf_page_create_map extends vcf_page_base implements iVCFeedPage
{
	var $post_type="";
	var $filename="";
	var $filepath="";
	var $jsonXML="";
	var $postFields;
    /**
     * Start up
     */
    public function __construct()
    {
		$this->set();
		if(!$this->post_type || !$this->filename){
			$this->redirectToStartPage();
		}
		
		$this->jsonXML=xml_to_json_path($this->filepath);
		
		
		//var_dump($this->postFields);
		
		
	}
	
	
	public function render()
    {
		$this->renderHeader("Asociar campos");
		if($this->postFields!=null)
			$this->renderForm("POST",VCF_MENU::CREATE,"preview","create_feed_form_map","Continuar",$this->composeFormContent(),"");
		else
			$this->renderError("No se han encontrado campos<br />Recuerde que debe existir al menos un post de este tipo.");
		
		$this->renderScripts();
	}
	
	
	
	public function set()
    {
		
		if(isset($_FILES["vcf-xml-file"]))
		{
			$this->filename=$_FILES["vcf-xml-file"];
			$this->move_file();
		}
		
		if(isset($_POST["vcf_posttype"])){
			$this->post_type=$_POST["vcf_posttype"];
		}
		
		if($this->post_type){
			
			$this->postFields=get_post_type_fields($this->post_type);
		}
		
		
	}
	
	public function move_file(){
		
		$uploads_dir =VCF_PLUGIN_PATH.'/uploads';
		$tmp_name = $_FILES["vcf-xml-file"]["tmp_name"];
		$name = $_FILES["vcf-xml-file"]["name"];
		move_uploaded_file($tmp_name, "$uploads_dir/$name");
		$this->filepath="$uploads_dir/$name";
		
	}
	
	public function composeFormContent(){
		$output="";

		$output.=$this->renderPostTypeForm();
		$output.=$this->renderTreeFields();
		$output.=$this->renderPostFields();
		
		return $output;
	}
	
	public function renderSelectedPostType(){
		
		$output="<div class='vcf-highligth'>Creando plantilla para '<span>".$this->post_type."'</span></div>";

		return $output;
	}
	
	public function renderTreeFields(){
		$output="<ul id='vcf-pt-xml-tree'>";
		$output.="</ul>";
		return $output;
		
	}
	
	public function renderPostFields(){
		$output="<div class='vcf-pt-fields'>";
		
		foreach( $this->postFields as $key => $field ) {
			$output.="<div class='vcf-pt-field' data-key='".$field."'>".$key."</div>";
		}
		$output.="</div>";
		return $output;
		
	}
	
	public function renderPostTypeForm(){
		
		$output="<input name='vcf_posttype' type='hidden' value='".$this->post_type."'/>";
		return $output;
	}
	
	public function renderJsonXMLForm(){
		
		$output="<input id='vcf_json_xml' name='vcf_json_xml' type='hidden' value='".$this->jsonXML."'/>";

		return $output;
	}
	
	public function renderScripts(){
		
		?>
		<script>
		jQuery(document).ready(function(){
			var jsonTree=<?php echo $this->jsonXML ?>;
			jQuery("#vcf-pt-xml-tree").MeytelJsonTree({json:jsonTree});

			
		});
		</script>
		<?php

		
	}
}

?>