<?php

function hook_css()
{
    ?>
        <link rel="stylesheet" href="<?php echo GISTAU__PLUGIN_URL.'templates/candidateTemplate/'; ?>styleCandidate.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo GISTAU__PLUGIN_URL.'templates/candidateTemplate/'; ?>codeCandidate.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <?php
}
add_action('wp_head', 'hook_css');

get_header();
require_once(__DIR__.'/candidateTemplate/candidateTemplate_ps.php');
get_footer();
