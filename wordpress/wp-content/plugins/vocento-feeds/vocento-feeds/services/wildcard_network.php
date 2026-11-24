<?php
try{
	

    $cacheKey=compose_key_cache($vcfid);

    $feed=getFeedByNetworkId($vcfid);


    if($feed && $feed["content_type"])
        header('Content-type: '.$feed["content_type"]);
    else
        header('Content-type: text/xml');

    //Si tenemos cache
    //$cached_feed=apc_fetch($cacheKey);
    //if($cached_feed){
    //    echo $cached_feed;
    //    return;
    //}


	



		if($feed['rpl']){
			$rootObject = vcf_generate_root_object_xml(vcf_generate_root_object($feed));
		}
		else{
			$rootObject = vcf_generate_root_object_xml(vcf_generate_network_root_object($feed));
		}
	$result=process_xsl(get_xslt_filepath($vcfid),$rootObject);
	ob_clean();

    //a cache 5 minutos
    //apc_store($cacheKey, $result,300);


	echo $result;

 }
catch (Exception $e) {
        echo $e->getMessage();
}
?>