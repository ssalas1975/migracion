<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php edgtf_quadric_wp_title(); ?>
    <?php
    /**
     * @see edgt_quadric_header_meta() - hooked with 10
     * @see edgt_user_scalable - hooked with 10
     */
    ?>
	<?php do_action('edgtf_quadric_header_meta'); ?>

	<?php wp_head(); ?>
    <!-- ADOBE ANALYTICS -->
<script src="//static.vocstatic.com/voonto2/latest/voonto.js " data-cmp="true"></script>
<script src="//static.vocstatic.com/tag-manager/latest/vtm.js" data-property-id="4f979df643fa11b494d7ca20407fccd7b46fd491"></script>
<script>TagManager.ready.then(function() {TagManager.set({}).track();});</script>
<!-- FIN ADOBE ANALYTICS -->
</head>

<body <?php body_class(); ?>>

<!-- Begin comScore Inline Tag 1.1302.13 --> 
<script type="text/javascript"> 
// <![CDATA[
function udm_(e){var t="comScore=",n=document,r=n.cookie,i="",s="indexOf",o="substring",u="length",a=2048,f,l="&ns_",c="&",h,p,d,v,m=window,g=m.encodeURIComponent||escape;if(r[s](t)+1)for(d=0,p=r.split(";"),v=p[u];d<v;d++)h=p[d][s](t),h+1&&(i=c+unescape(p[d][o](h+t[u])));e+=l+"_t="+ +(new Date)+l+"c="+(n.characterSet||n.defaultCharset||"")+"&c8="+g(n.title)+i+"&c7="+g(n.URL)+"&c9="+g(n.referrer),e[u]>a&&e[s](c)>0&&(f=e[o](0,a-8).lastIndexOf(c),e=(e[o](0,f)+l+"cut="+g(e[o](f+1)))[o](0,a)),n.images?(h=new Image,m.ns_p||(ns_p=h),h.src=e):n.write("<","p","><",'img src="',e,'" height="1" width="1" alt="*"',"><","/p",">")};
udm_('http'+(document.location.href.charAt(4)=='s'?'s://sb':'://b')+'.scorecardresearch.com/b?c1=2&ns_site=cmvocento&voc_site=cmvocento&voc_pr=1');
// ]]>
</script>
<noscript><p><img src="https://b.scorecardresearch.com/p?c1=2&ampc2=7715761&ns_site=cmvocento&amp;voc_site=cmvocento&amp;voc_pr=1" height="1" width="1" alt="*"></p></noscript> 
<!-- End comScore Inline Tag -->
<!-- Start vocento DTM B -->
<noscript><img src="//vocento.d3.sc.omtrdc.net/b/ss/vocvocento,voces/1/G.4--S/838777838?pageName=&g=<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" width="1" height="1" border="0" /></noscript>
<!-- End vocento DTM B --> 	

<?php edgtf_quadric_get_side_area(); ?>
<div class="edgtf-wrapper">
    <div class="edgtf-wrapper-inner">
        <?php edgtf_quadric_get_header(); ?>

        <?php if(edgtf_quadric_options()->getOptionValue('show_back_button') == "yes") { ?>
            <a id='edgtf-back-to-top'  href='#'>
                <span class="edgtf-icon-stack">
                     <?php
                        edgtf_quadric_icon_collections()->getBackToTopIcon('font_elegant');
                    ?>
                </span>
				<span class="edgtf-back-to-top-icon-hover">
						 <?php edgtf_quadric_icon_collections()->getBackToTopIcon('font_elegant'); ?>
				</span>
            </a>
        <?php } ?>
        <div class="edgtf-content" <?php edgtf_quadric_content_elem_style_attr(); ?>>
            <div class="edgtf-content-inner">