<?php
ob_start();



class VCBC_Media_Select
{
    private $messages=array();
    private $filter=array();
    private $ipp=10;
    private $page=0;
    private $searched_videos;
    private $searched_videos_count;
    private $is_filtering=false;
    
    public function __construct()
    {

        /**
         * Load media files needed for Uploader
         */
       
      

        //Si es busqueda nueva maracmos la p�gina a 0
        if(isset($_POST["vcbc-filter-submit"]))
        {
            $this->page=0;
        }

        
        if(isset($_POST["vcbc_select_control"]))
        {
            
            if(isset($_POST["vcbc_select_page"]) && !isset($_POST["vcbc-filter-submit"]))
                $this->page=$_POST["vcbc_select_page"];

            $this->filter=vcbc_compose_filter_video();
            $filter_query=vcbc_compose_params_read_video($this->filter);
            $this->searched_videos=VC_CMSAPI_Video::READ($this->filter['medio'],$this->ipp,($this->page*$this->ipp),$filter_query,"-updated_at",$this->filter['medio_id']);
            $this->searched_videos_count=VC_CMSAPI_Video::COUNT($this->filter['medio'],$filter_query,"-updated_at",$this->filter['medio_id']);
            
            $this->is_filtering=true;

        }

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

    public function render_pagination(){

        echo paginate_links( array(
	    'base' => "#",
	    'format' => '?paged=%#%',
	    'current' =>  $this->page+1 ,
	    'total' => (($this->searched_videos_count/$this->ipp)+(($this->searched_videos_count%$this->ipp>0)?1:0))-1
    ) );

    }


    public function render_filtered_videos(){

        if(is_array($this->searched_videos)){
            foreach($this->searched_videos as $video){
                $video_images=VC_CMSAPI_Video::GET_VIDEO_IMAGES($video->account_id,$video->id,$this->filter['medio_id']);
                $video_sources=VC_CMSAPI_Video::GET_VIDEO_SOURCES($video->account_id,$video->id,$this->filter['medio_id']);

                $image_src="";
                if($video_images && $video_images->thumbnail)
                    $image_src=$video_images->thumbnail->src;
                else
                    $image_src=plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/video-default.png' );

?>

<div class="vcbc-video" data-videoid="<?php echo $video->id?>"  data-medio="<?php echo $this->filter['medio_id']?>">
    <div class="vcbc-video-image">
        <img src="<?php echo $image_src ?>" />
    </div>
    <div class="vcbc-video-title"><?php echo $video->name?></div>
    <div class="vcbc-video-info">


        <div class="vcbc-video-state <?php echo $video->state?>">
            <img src="<?php echo plugins_url( '/vocento-brightcove/vocento-brightcove/admin/assets/img/video-'.$video->state.'.png' )?>" alt="<?php echo $video->state; ?>" title="<?php echo $video->state; ?>">
        </div>

        <?php if(is_array($video_sources) && count($video_sources)){ ?>

        <div class="vcbc-video-size"><?php echo vcbc_format_bytes($video_sources[0]->size,2); ?></div>
        <div class="vcbc-video-duration"><?php echo  vcbc_format_miliseconds($video_sources[0]->duration); ?></div>

        <?php } ?>
        <div class="vcbc-video-actions">

            <div class="vcbc-video-edit">
                <div class="vcbc-video-more edit-video">
                    Editar
                </div>
            </div>
            <?php if(is_array($video_sources) && count($video_sources)){ ?>
            <div class="vcbc-video-more">
                + info
            </div>
            <?php } ?>
        </div>
         <?php if(is_array($video_sources) && count($video_sources)){ ?>
        <div class="vcbc-video-more-hidden">

            <table class="bc-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th dir="auto">Dimensiones</th>
                        <th dir="auto">Formato</th>
                        <th dir="auto">Tama&nacute;o</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                  foreach($video_sources as $source){
                    ?>
                    <tr>
                        <td dir="auto"><?php echo $source->width." x ".$source->height ?></td>
                        <td dir="auto"><?php echo $source->container." - ".$source->codec?></td>
                        <td dir="auto"><?php echo vcbc_format_bytes($source->size,2); ?></td>

                    </tr>
                    <?php
                  }    
                    ?>


                </tbody>
            </table>


        </div>

         <?php } ?>
        
    </div>
</div>

<?php
            }
        }
        
    }
    public function render_page()
    {
        $this->displayMessages();
?>

<div class="vcbc-fields-media">
    <form id="form-select" method="post">

        <div class="vcbc-select-filters">
            <div class="vcbc-filter vcbc-filter-head">Filtros</div>
            <div class="vcbc-filter">
                <select required name="vcbc_filter_medio" id="vcbc_filter_medio">
                    <?php 
        foreach(vcbc_get_active_medios() as $medio){

            $selected="";

            if($this->filter && ($this->filter['medio_id'] == $medio["vcbc_medio_id"]))
                $selected="selected";

            echo "<option ".$selected." name='".$medio["vcbc_medio_name"]."' value='".$medio["vcbc_medio_id"]."'>".$medio["vcbc_medio_name"]."</option>";
        }
                    ?>
                </select>
            </div>
            <div class="vcbc-filter">
                <input placeholder="Fecha" type="date" id="vcbc_select_date" name="vcbc_select_date" value="<?php echo $this->filter['criteria']['created_at']?>"/>

            </div>
            <div class="vcbc-filter">
                <input placeholder="Tags a buscar" type="text" id="vcbc_select_tags" name="vcbc_select_tags" value="<?php echo $this->filter['criteria']['tags']?>"/>
            </div>
            <div class="vcbc-filter">
                <input placeholder="Texto a buscar" type="text" id="vcbc_select_txt" name="vcbc_select_txt" value="<?php echo $this->filter['criteria']['q']?>"/>
            </div>
            <div class="vcbc-filter">
                <input class="button button-primary" style="float: right; width: 55% !important;" type="submit" id="vcbc-filter-submit" name="vcbc-filter-submit" value="Buscar" />
            </div>

            <input type="hidden" id="vcbc_select_page" name="vcbc_select_page" value="<?php echo (isset($_POST["vcbc_select_page"])?$_POST["vcbc_select_page"]:"0")?>"/>

            <input type="hidden" id="vcbc_select_control" name="vcbc_select_control" value="true" />

            <input type="hidden" id="vcbc_select_selected_video_id" name="vcbc_select_selected_video_id" />
            <input type="hidden" id="vcbc_select_selected_medio_id" name="vcbc_select_selected_medio_id" />
        </div>
        <br />
        <br />
        <div class="vcbc-filter-results">
           
            <?php
        $this->render_filtered_videos();
            ?>
        </div>

        <div class="vcbc-select-submit">
            <div class="vcbc-filter-pagination">
                <?php
        if($this->is_filtering)
            $this->render_pagination();
                ?>
            </div>
             <input class="button button-primary button-large" style="float: right;" type="button" id="vcbc-filter-select-submit" name="vcbc-filter-select-submit" value="Seleccionar video" />
        </div>
    </form>
</div>

<script type="text/javascript">
    // Custom example logic
    jQuery(document).ready(function () {


       


        jQuery("#vcbc-filter-submit").click(function () {
            jQuery(".vcbc-filter-results").addClass("loading");
        });


        jQuery("#vcbc-filter-select-submit").click(function () {

            if (!jQuery("#vcbc_select_selected_video_id").val()) {
                alert("Debe seleccionar un video");
                return false;
            }

            if (!jQuery("#vcbc_select_selected_medio_id").val()) {
                alert("Debe seleccionar un video");
                return false;
            }

			//Trigger to notify videolselect
            if (window.parent)
                window.parent.jQuery('body').trigger('vcbc_videoselected', [jQuery("#vcbc_select_selected_video_id").val(), jQuery("#vcbc_select_selected_medio_id").val(),jQuery(".vcbc-video.selected img").attr("src"), '[vcbc videoid="' + jQuery("#vcbc_select_selected_video_id").val() + '" medio="' + jQuery("#vcbc_select_selected_medio_id").val() + '"]']);
           
            window.parent.send_to_editor('[vcbc videoid="' + jQuery("#vcbc_select_selected_video_id").val() + '" medio="' + jQuery("#vcbc_select_selected_medio_id").val() + '"]');
           

			window.parent.tb_remove();
        });

        jQuery(".vcbc-video").click(function () {
            var inactive = (jQuery(".INACTIVE", jQuery(this)).size() > 0);
            if (inactive)
                return;

            jQuery(".vcbc-video").removeClass("selected");
            jQuery(this).addClass("selected");
            $videoid = jQuery(this).data("videoid");
            jQuery("#vcbc_select_selected_video_id").val($videoid);

            $medioid = jQuery(this).data("medio");
            jQuery("#vcbc_select_selected_medio_id").val($medioid);

        });

        jQuery(".vcbc-video-more").click(function () {

            if (jQuery(this).hasClass("edit-video"))
                return false;
           
            $parent = jQuery(this).closest(".vcbc-video");
            $tabla = jQuery(".vcbc-video-more-hidden", $parent);
            composeTablaContainer($tabla);

        });

        jQuery(".edit-video").click(function () {
            $parent = jQuery(this).closest(".vcbc-video");
            $videoid = $parent.data("videoid");
            $medioid = $parent.data("medio");
            composeEditWindow($videoid, $medioid);
        });

        jQuery("a", ".vcbc-filter-pagination").click(function () {
            //L paginacion s emaneja abajo
            if (jQuery(this).hasClass("prev") || jQuery(this).hasClass("next"))
                return false;

            $parent = jQuery("#vcbc_select_page").val(parseInt(jQuery(this).html().replace('.', '')) - 1);
            jQuery("#form-select").submit();
            return false;

        });

        jQuery("a.prev", ".vcbc-filter-pagination").click(function () {
            $parent = jQuery("#vcbc_select_page").val(parseInt(jQuery("#vcbc_select_page").val().replace('.', '')) - 1);
            jQuery("#form-select").submit();
            return false;

        });

        jQuery("a.next", ".vcbc-filter-pagination").click(function () {
            $parent = jQuery("#vcbc_select_page").val(parseInt(jQuery("#vcbc_select_page").val().replace('.', '')) + 1);
            jQuery("#form-select").submit();
            return false;

        });

    });

    function composeTablaContainer($tabla) {

        $tablaContainer = jQuery("<div class='vcbc-table-streams'><div class='vcbc-table-header'><div class='vcbc-table-title'>Informacion de fuentes</div><div class='vcbc-table-close'>X</div></div><div class='vcbc-table-content'></div></div>");
        jQuery(".vcbc-table-content", $tablaContainer).html($tabla.html());
        jQuery("body").append($tablaContainer);
        jQuery(".vcbc-table-close", $tablaContainer).click(function () {
            jQuery(this).closest(".vcbc-table-streams").remove();
        });
    }


    function composeEditWindow($videoid, $medioid) {

        var data = {
            action:'vcbc_edit_video',
            vcbc_medio_id: $medioid,
            vcbc_video_id:$videoid 
        }
        
        jQuery.post(ajaxurl, data, function (response) {

            try {
             
                $editContainer = jQuery("<div class='vcbc-table-streams vcbc-edit-video-window'><div class='vcbc-table-header'><div class='vcbc-table-title'>Editar video</div><div class='vcbc-table-close'>X</div></div><div id='vcbc-edit-video-form'>" + response + "</div></div>");
                jQuery("body").append("<div class='bkg-vcbc-edit'></div>");
                jQuery("body").append($editContainer);
                jQuery("#media-upload").addClass("disabled");
                jQuery(".vcbc-table-close", $editContainer).click(function () {
                    jQuery(this).closest(".vcbc-table-streams").remove();
                    jQuery(".bkg-vcbc-edit").remove();
                    jQuery("#media-upload").removeClass("disabled");
                });


            }
            catch (err) {
                alert("No se puede cargar el video")
            }

        });


      
      
    }

</script>

<?php

    }

    

}