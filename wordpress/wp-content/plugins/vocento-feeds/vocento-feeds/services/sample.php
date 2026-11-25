<?php
header('Content-type: text/xml');

if($_GET["pt"]){
    $posttypes=explode(",",$_GET["pt"]);
}
else{

    if(is_multisite() && is_network_admin())
        $posttypes= vcf_get_post_types_mu();
    else
    {
        if(is_multisite())
            $posttypes= vcf_get_post_types_by_blog(current_blog_id());
        else
            $posttypes= vcf_get_post_types_by_blog();
    }
        
}


if(is_multisite() && is_network_admin())
    $rootObject = vcf_generate_root_object_xml(vcf_generate_network_root_sample_object($posttypes));
else
    $rootObject = vcf_generate_root_object_xml(vcf_generate_root_sample_object($posttypes));


ob_clean();
echo $rootObject;
?>