<?php
class CMSNativeEditor
{
    /**
     * Start up
     */
    public function __construct()
    {	
        
        if($_POST["cmsnative-submit"]){
            if($_POST["editor-cmsnative"]){
				$code=$_POST["editor-cmsnative"];
				cmsnative_e_save_style($code);
			}
        }
    }

    public function render_page()
    {
?>

<style>
    .CodeMirror {
  border: 1px solid #eee;
  height: auto;
}

</style>

<div style="width: 97%">
    <br /><br /><h1>CMS Native: Editar css</h1><br /><br />
	<form name="cmsnative-editor-form" id="cmsnative-editor-form" method="POST">
	<textarea  style="min-height:500px;" id="editor-cmsnative" name="editor-cmsnative"><?php echo cmsnative_e_load_style()?></textarea >
	<input type="submit" name="cmsnative-submit" id="cmsnative-submit" class="button button-primary" style="float:right; margin-top:30px;"  value="Guardar"/>
	
    </form>
		<script>
		
			var editor = CodeMirror.fromTextArea(document.getElementById("editor-cmsnative"), {
			  mode: "text/html",
			  lineNumbers: true
			});
			
			
			
			
		
		
		</script>

</div>



<?php

        
    }




}