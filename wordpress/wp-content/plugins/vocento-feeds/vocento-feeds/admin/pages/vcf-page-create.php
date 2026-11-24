<?php
ob_start();
class vcf_page_create extends vcf_page_base implements iVCFeedPage
{
	var $slug="";
	var $name="";
	var $xslt="";
	
    /**
     * Start up
     */
    public function __construct()
    {
		$this->set();
		var_dump($this->slug );
		if(!$this->slug || !$this->name || !$this->xslt){
			$this->renderError("Debe rellenar todos los campos");
		}
		else{
			$url = add_query_arg($this->composeFormParams(VCF_MENU::CREATE,"preview"), admin_url('admin.php'));
			header('Location: '.$url);
		}
	}
	
	
	public function render()
    {
		$this->renderHeader("Crear feed");
		$this->composeFormContent();
	}
	
	
	
	public function set()
    {
		
		if(isset($_FILES["vcf-xslt"]))
		{
			$this->xslt=$_FILES["vcf-xslt"];
			$this->move_file();
		}
		
		if(isset($_POST["vcf-name"])){
			$this->name=$_POST["vcf-name"];
		}
		
		if(isset($_POST["vcf-slug"])){
			$this->slug=$_POST["vcf-slug"];
		}

	}
	
	public function move_file(){
		
		$uploads_dir =VCF_PLUGIN_PATH.'/uploads';
		$tmp_name = $_FILES["vcf-xslt"]["tmp_name"];
		$name = $_FILES["vcf-xslt"]["name"];
		move_uploaded_file($tmp_name, "$uploads_dir/$name");
		$this->xslt="$uploads_dir/$name";
		
	}
	
	public function composeFormContent(){
		$output="";
		$output.=$this->renderFormContent();
		return $output;
	}

	public function renderFormContent(){
		
		$url = add_query_arg($this->composeFormParams(VCF_MENU::CREATE,"create"), admin_url('admin.php'));
		
		?>
		<form action="<?php echo $url ?>" method="POST" name="create" enctype='multipart/form-data'>
		
		<div class="create-feed">
		
		<div class="vcf-create-field"><span>Nombre del feed:</span><input required type="text" name="vcf-name" placeholder="Introduzca nombre" /></div>
		
		<div class="vcf-create-field"><span>Slug del feed:</span><input required type="text" name="vcf-slug" placeholder="Introduzca slug (mobile-feed or mobile-feed/custom)" /></div>
		
		<div class="vcf-create-field"><span>XSLT:</span><input required type="file" name="vcf-xslt" placeholder="Seleccione .xslt" accept='.xslt' /></div>
		
		</div>
		<input type='submit' value='Crear' />
		
		</form>
		<?php
	}
}
?>