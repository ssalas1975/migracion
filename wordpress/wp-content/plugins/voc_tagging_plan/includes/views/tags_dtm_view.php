<?php
echo '
<div class="container-fluid">
  <form>
    <fieldset>
        <div class="row">
            <div class="form-group">
            <label for="listVariables">Seleccionar URL</label>
            <select id="listVariables" class="form-control" name="listVariables">
                <option class="listoptions">Selecciona un patron para editarlo</option>
            </select>
            </div>
        </div>
        <div class="row">
            <div class"col-md-8">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Variable | Valor</span>
                    </div>
                    <input type="text" class="form-control valueVar">
                    <input type="text" class="form-control valueVal">
                    <input type="hidden" class="ajaxurl" value="'.admin_url( "admin-ajax.php" ).'" />
                    <input class="btn btn-primary addNew" type="button" value="+">
                </div>
                <div class="primarycontent"></div>
            </div>
        </div>
        <button type="button" class="btn btn-primary btnguardartags">Guardar</button>
    </fieldset>
    </form>
</div>


';