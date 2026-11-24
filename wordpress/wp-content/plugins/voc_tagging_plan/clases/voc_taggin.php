<?php

class voc_taggin
{
    public function createTagmanager($parseData, $slug)
    {
        $variables = get_option('voc_vars');
        $perico = get_option('patron_url');
        $script = '';
        $postid = url_to_postid($slug);
        $created = get_the_time('Y-m-d', $postid);
        $modified = get_the_modified_date('Y-m-d', $postid);
        $text = '';
        foreach ($variables as $variable) {
            if ($variable['idpatron'] == $parseData['url']['id']) {
                $script .= '
                    <script>
                        TagManager.ready.then(function() {
                            TagManager.set({
                ';
                foreach ($variable['variables'] as $key => $value) {
                    $re = '/\[[0-9]+\]/m';
                    preg_match_all($re, $value, $matches, PREG_SET_ORDER, 0);
                    if (!empty($matches)) {
                        $text = str_replace($matches[0][0], '', $value);
                        $value = str_replace(str_split('[]'), '', $matches[0][0]);
                    }
                    switch ($value) {
                        case "1":
                            // $script .= $key.":'".$parseData['variables'][0][1]."',\n";
                            if ($text != "" || $text != null) {
                                $script .= $key.":'".$text.'-'.$parseData['variables'][0][1]."',\n";
                            } else {
                                $script .= $key.":'".$parseData['variables'][0][1]."',\n";
                            }
                            break;
                        case "2":
                            // $script .= $key.":'".$parseData['variables'][0][2]."',\n";
                            if ($text != "" || $text != null) {
                                $script .= $key.":'".$text.'-'.$parseData['variables'][0][2]."',\n";
                            } else {
                                $script .= $key.":'".$parseData['variables'][0][2]."',\n";
                            }
                            break;
                        case "[aaaammdd][publicacion]":
                            $script .= $key.":'".$created."',\n";
                            break;
                        case "[aaaammdd][modificacion]":
                            $script .= $key.":'".$modified."',\n";
                            break;
                        case "12":
                            $script .= $key.":'".$parseData['variables'][0][1].'-'.$parseData['variables'][0][2]."',\n";
                            break;
                        case "21":
                            $script .= $key.":'".$parseData['variables'][0][2].'-'.$parseData['variables'][0][1]."',\n";
                            break;
                        default:
                            $script .= $key.':"'.$value.'",'."\n";
                            break;
                    }
                }
                if (wp_is_mobile()) {
                    $script .= 'plataformaContenido: "m-responsive",'."\n";
                } else {
                    $script .= 'plataformaContenido: "contenido-web",'."\n";
                }
                if (wp_get_referer() == (get_option('home').'/')) {
                    $script .= 'origenPortada:"1",'."\n";
                } else {
                    $script .= 'origenPortada:"0",'."\n";
                }
                $script .= 'tipoDominio:"1",'."\n";
                $script .= '
                            }).track();
                        });
                    </script>
                ';
            }
        }
        echo $script;
    }

    public function serachPatron($slug)
    {
        $urls = get_option('patron_url');
        foreach ($urls as $value) {
            if ($value['usarpatron'] == "false") {
                $patron = str_replace("/", "\/", $value['url']);
                // $patron = str_replace('[texto]', '(.*)', $patron);
                $patron = str_replace('[texto]', '([^\s\/]+)', $patron);
                $patron = str_replace('[numero]', '([0-9]+)', $patron);
                $patron = '/.*?'.$patron.'$/m';
                preg_match_all($patron, $slug, $matches, PREG_SET_ORDER, 0);
                if (!empty($matches)) {
                    return [
                        'variables' => $matches,
                        'url'     => $value
                    ];
                }
            }
        }
    }
}

$tagging = new voc_taggin();
