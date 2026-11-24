<?php
if( isset($_POST['save_voonto_options']) )
{
    $data_property = $_POST['data-property-id'];
    $report_suit = $_POST['report-suit'];
    $data_cmp_position = $_POST['data_cmp_position'];
    $data_cmp = $_POST['data_cmp'];
    $settings = [
        'data_poperty'         => $_POST['data-property-id'],
        'data_analytics_id'    => $_POST['data-analytics-id'],
        'data_legacy_adbd'     => $_POST['data-legacy-adbd'],
        'data_legacy_krux'     => $_POST['data-legacy-krux'],
        'data_legacy_comscore' => $_POST['data-legacy-comscore'],
        'repor_suit'           => $_POST['report-suit'],
        'data_cmp'             => $_POST['data_cmp'],
        'data_cmp_position'    => $_POST['data_cmp_position']
    ];
    update_option('settings_voonto', $settings);
}

$options = get_option('settings_voonto');

echo '
    <div class="bd-example">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style=" margin-top: 20px; margin-right: 50px;">
        <li class="breadcrumb-item active" aria-current="page">Parámetros Configuración</li>
    </ol>
    </nav>
';
if( isset($_POST['save_voonto_options']) )
{
    ?>
    <div class="updated notice is-dismissable">
        <p>Opciones guardadas</p>
    </div>
    <?php
}


echo '
    </div>
    <div class="content-adobe-settings">
        <form method="post">
            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Data Properties</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Report Suit</a>
                    <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">CMP</a>
                    <!--a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a-->
                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="form-group">
                                <label for="data-property-id">Data Property ID</label>
                                <input type="text" class="form-control data-property-id" name="data-property-id" id="data-property-id" aria-describedby="data-property-id" placeholder="Data Property ID" value="'.$options['data_poperty'].'">
                            </div>
                            <div class="form-group">
                                <label for="data-analytics-id">Data Analytics ID</label>
                                <input type="text" class="form-control data-analytics-id" name="data-analytics-id" id="data-analytics-id" aria-describedby="data-analytics-id" placeholder="Data Analytics ID" value="'.$options['data_analytics_id'].'">
                            </div>
                            <div class="form-group">
                                <label for="data-legacy-adbd">Data Legacy Adbd</label>
                                <input type="text" class="form-control data-legacy-adbd" name="data-legacy-adbd" id="data-legacy-adbd" aria-describedby="data-legacy-adbd" placeholder="true | false" value="'.$options['data_legacy_adbd'].'">
                            </div>
                            <div class="form-group">
                                <label for="data-property-id">Data Legacy krux</label>
                                <input type="text" class="form-control data-legacy-krux" name="data-legacy-krux" id="data-legacy-krux" aria-describedby="data-legacy-krux" placeholder="Data Legacy Krux" value="'.$options['data_legacy_krux'].'">
                            </div>
                            <div class="form-group">
                                <label for="data-property-id">Data Legay comscore</label>
                                <input type="text" class="form-control data-legacy-comscore" name="data-legacy-comscore" id="data-legacy-comscore" aria-describedby="data-legacy-comscore" placeholder="Data Property ID" value="'.$options['data_legacy_comscore'].'">
                                <span>Añadir el siguiente shortcode al head de la página: do_shortcode( "[voonto_head]" );</span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <div class="form-group">
                                <label for="report-suit">Report Suit</label>
                                <input type="text" class="form-control report-suit" name="report-suit" id="report-suit" placeholder="report-suit" value="'.$options['repor_suit'].'">
                                <span>Añadir el siguiente shortcode en el body de la página: do_shortcode( "[voonto_body]" );</span>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                            <label for="data-property-id">CMP</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="">'.$options['data_cmp'].'</label>
                                </div>
                                <select class="custom-select" name="data_cmp" style="height: auto; padding-left: 20px;">
                                    <option selected>Selecciona...</option>
                                    <option value="true">true</option>
                                    <option value="false">false</option>
                                </select>
                                </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="">'.$options['data_cmp_position'].'</label>
                                </div>
                                <select class="custom-select"name="data_cmp_position" style="height: auto; padding-left: 20px;">
                                    <option selected>Selecciona...</option>
                                    <option value="panel-top-left">panel-top-left</option>
                                    <option value="panel-top-right">panel-top-right</option>
                                    <option value="panel-bottom-left">panel-bottom-left</option>
                                    <option value="panel-bottom-right">panel-bottom-right</option>
                                    <option value="top">top</option>
                                    <option value="bottom">bottom</option>
                                    <option value="top-left">top-left</option>
                                    <option value="top-right">top-right</option>
                                    <option value="bottom-left">bottom-left</option>
                                    <option value="bottom-right">bottom-right</option>
                                </select>
                            </div>
                            <p><span>Añadir el siguiente shortcode en el footer de la página: do_shortcode( "[voonto_footer]" );</span></p>
                        </div>
                        <!--div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">d</div-->
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" name="save_voonto_options" class="btn btn-primary">Submit</button>
        </form>
    </div>
';
