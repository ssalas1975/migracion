<?php
header('Content-type: text/xml');
$rootObject = vcf_generate_root_object_xml(vcf_generate_root_object());
ob_clean();
echo $rootObject;
?>