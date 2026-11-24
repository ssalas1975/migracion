<?php

$host = "www.madridfusion.net/es/cocineros/letra/B/page/2/";
$arrayPatrones = [];
$arrayPatrones[0]['patron'] = '/es\/cocineros\/$/m'; //home
$arrayPatrones[0]['id'] = 1;
$arrayPatrones[1]['patron'] = '/es\/cocineros\/[a-zA-Z]+\/$/m'; //termino de busqueda
$arrayPatrones[1]['id'] = 1;
$arrayPatrones[2]['patron'] = '/es\/cocineros\/letra\/[a-zA-Z]{1}\/page\/[0-9]+\//m';
$arrayPatrones[2]['id'] = 1;


function regex101 ($array, $url)
{
    foreach($array as $patron)
    {
        preg_match_all($patron['patron'], $url, $matches, PREG_SET_ORDER, 0);
        if( count($matches) > 0 )
        {
            echo $patron['id'];
        }
    }

}

regex101($arrayPatrones, $host);



$re = '/es\/cocineros\/letra\/[a-zA-Z]{1}\/page\/[0-9]+\//m';
$str = 'www.madridfusion.net/es/cocineros/letra/B/page/2/
www.madridfusion.net/es/cocineros/letra/B/page/23/';

