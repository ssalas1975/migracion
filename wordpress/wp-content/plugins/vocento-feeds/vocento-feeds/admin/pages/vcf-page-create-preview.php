<?php

class vcf_page_create_preview extends vcf_page_base implements iVCFeedPage
{
	var $post_type="";
    /**
     * Start up
     */
    public function __construct()
    {
		$this->set();
		if(!$this->post_type){
			$this->redirectToStartPage();
		
		}
		
	}
	
	
	public function render()
    {
		$this->renderHeader("Seleccionar XML");
		$this->renderForm("POST",VCF_MENU::CREATE,"map","create_feed_form_xml","Continuar",$this->composeFormContent(),"enctype='multipart/form-data'");
	}
	
	
	
	public function set()
    {
		if($_POST["vcf_posttype"])
			$this->post_type=$_POST["vcf_posttype"];
		
		
	}
	
	public function composeFormContent(){
		$output="";
		$output.=$this->renderSelectedPostType();
		$output.=$this->renderUploadForm();
		$output.=$this->renderPostTypeForm();
		return $output;
	}
	
	public function renderSelectedPostType(){
		
		$output="<div class='vcf-highligth'>Creando plantilla para '<span>".$this->post_type."'</span></div>";

		return $output;
	}
	
	public function renderUploadForm(){
		
		$output="<input name='vcf-xml-file' type='file' value='Seleccionar plantilla XML' accept='.xml'/>";

		return $output;
	}
	
	public function renderPostTypeForm(){
		
		$output="<input name='vcf_posttype' type='hidden' value='".$this->post_type."'/>";

		return $output;
	}
}
?>