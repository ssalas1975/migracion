<?php
class vcf_page_base 
{

	public function renderHeader($title){

		echo "<div class='vcf-header-admin'><h1>".$title."</h1></div>";
	}
	
	public function render()
    {
		echo "estás en page base";
	}
	
	public function renderForm($method,$page,$action,$name,$submitValue,$content,$attributes='')
    {
		$url = add_query_arg($this->composeFormParams($page,$action), admin_url('admin.php'));
		   
		echo "<form action='".$url."' method='".$method."' name='".$name."' ".$attributes.">";
		echo $content;
		echo "<input type='submit' value='".$submitValue."' />";
		echo "</form>";
	}
	
	public function renderError($message)
    {
		echo "<div class='vcf-error'>".$message."</div>";
		
	}
	
	public function composeFormParams($page,$action)
	{
		$params= array(
            'page'=>$page
        );
		
		if($action)
			$params['action']=$action;
		
		return $params;
	}
	
	public function redirectToStartPage()
	{
		$params= array(
            'page'=>VCF_MENU::CREATE
        );
		$url = add_query_arg($params, admin_url('admin.php'));

		//header("Location: ".$url);
		
	}

}
?>