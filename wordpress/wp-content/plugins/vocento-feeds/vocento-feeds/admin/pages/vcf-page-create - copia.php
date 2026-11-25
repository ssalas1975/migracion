<?php

class vcf_page_create extends vcf_page_base implements iVCFeedPage
{
  
    /**
     * Start up
     */
    public function __construct()
    {}
	
	
	public function render()
    {
		$this->renderHeader("Crear feed");
		$this->renderForm("POST",VCF_MENU::CREATE,"xml","create_feed_form","Continuar",$this->composeFormContent());
	}
	
	
	
	public function set()
    {}
	
	public function composeFormContent(){
		$output="";
		$output.=$this->renderAvailablePostType();
		return $output;
	}
	

	
	public function renderAvailablePostType(){
		
		$output="";
		
		$post_types= vcf_get_post_types_mu();
		
		$output.= '<select name="vcf_posttype">';
		
		foreach ( $post_types  as $post_type ) {
			$output.=  '<option>' . $post_type->name . '</option>';
		}
		
		$output.=  '</select>';
		
		return $output;
	}
}
?>