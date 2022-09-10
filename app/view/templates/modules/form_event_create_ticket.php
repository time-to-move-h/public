<?php
declare(strict_types=1);
// Translation -------------------------------
$t = new \JsonI18n\Translate($lang);
$t->addResource('app/view/templates/trans/form_event_create_ticket.json');
//--------------------------------------------

$return_dates = null;
$form = new stdClass();

$commonData = new \Moviao\Data\CommonData();
$commonData->iniDatabase();
$commonData->setSession($sessionUser);
$data = $commonData->getDBConn();

if ($data->connectDBA()) {
    // Event
    $event_utils = new \Moviao\Data\Util\EventsUtils($commonData);


    $form->idevent = $dataView['ID'];
    $form->lang = $commonData->getSession()->getLanguage();

    $return_dates = $event_utils->getEventDates($form);

    if (null !== $data) {
        $data->disconnect();
    }
}

?>
<div id="panel_edit_admin_tickets" style="display: none">

<div style="width: 600px;">

<h5><?php echo $this->e($t->_e('page_legend'));?></h5>

<div id="fcreateticket_panel_view">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?=$this->e($t->_e('form_view_name'));?></th>
            <th scope="col"><?=$this->e($t->_e('form_view_quantity'));?></th>
            <th scope="col"><?=$this->e($t->_e('form_view_price'));?></th>
            <th scope="col">#</th>
        </tr>
        </thead><tbody id="event_tickets_edit_list"></tbody>
    </table>
</div>

<div id="fcreateticket_panel_create" style="display: none">
    <form id="fcreateticket" data-parsley-validate>
    <input id="fcreateticket_id" name="ID" type="hidden" value="">
    <fieldset>


    <div class="form-group row">
        <label for="fcreateticket_datbeg" class="col-sm-2 col-form-label"><?=$this->e($t->_e('form_view_period'));?></label>
        <div class="col-sm-10">
            <select id="fcreateticket_datbeg" name="DATBEG" class="form-control" required>

                <?php
                $selected = '';
                $first = true;
                //echo var_dump($return_dates);
                foreach ($return_dates as $obj) {

                    if ($first) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }

                    echo '<option value="' . $obj['DATBEG'] . '" ' . $selected . '>' . $obj['DATFORMATTED'] . '</option>';
                    $first = false;
                }
                ?>
            </select>

        </div>
    </div>





    <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label"><?=$this->e($t->_e('form_view_name'));?></label>
        <div class="col-sm-10"><input type="text" id="fcreateticket_name" name="NAME" class="form-control" minlength="1" maxlength="250" required=""></div>
    </div>





    <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label"><?=$this->e($t->_e('form_view_desc'));?></label>
        <div class="col-sm-10"><textarea type="text" id="fcreateticket_descl" name="DESCL" class="form-control" minlength="1" maxlength="3000"></textarea></div>
    </div>
    <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label"><?=$this->e($t->_e('form_view_quantity'));?></label>
        <div class="col-sm-10"><input type="number" id="fcreateticket_qte" name="QTE" class="form-control" min="0" minlength="1" required=""></div>
    </div>
    <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label"><?=$this->e($t->_e('form_view_price'));?></label>
        <div class="col-sm-10"><input type="text" id="fcreateticket_price" name="PRICE" class="form-control" minlength="1" required=""></div>
    </div>
    <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label"><?=$this->e($t->_e('form_view_quantity_min'));?></label>
        <div class="col-sm-10"><input type="number" id="fcreateticket_minqte" name="MINQTE" class="form-control" min="1" minlength="1" required="" value="1"></div>
    </div>
    <div class="form-group row">
        <label for="" class="col-sm-2 col-form-label"><?=$this->e($t->_e('form_view_quantity_max'));?></label>
        <div class="col-sm-10"><input type="number" id="fcreateticket_maxqte" name="MAXQTE" class="form-control" min="1" minlength="1" required="" value="10"></div>
    </div>
    <input type="hidden" name="UID" value="<?php echo $urllink;?>">
    </fieldset>
    <div class="input-group">
        <button id="btn_fcreateticket_save" type="submit" class="btn btn-primary pull-right" style="display: none"><?=$this->e($t->_e('btn_save'));?></button>
        <button id="btn_fcreateticket_cancel" type="submit" class="btn btn-primary pull-right btn-margin-left5" style="display: none"><?=$this->e($t->_e('btn_cancel'));?></button>
    </div>
    </form>
</div>
<div class="input-group">
    <button id="btn_fcreateticket_create" type="submit" class="btn btn-primary pull-right" style="display: none"><?=$this->e($t->_e('btn_create'));?></button>
</div>
</div>

</div>