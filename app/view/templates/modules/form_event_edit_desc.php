<!-- Modal -->
<div class="modal fade" id="modal_edit_desc" role="dialog" aria-labelledby="modalLabel3" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="feventdesc" data-parsley-validate>

            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel3"><?=$this->e($info->_e('modal_title_edit_desc'));?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="panel_edit_desc">

                        <div class="form-group">
                            <label for="title"><?=$this->e($info->_e('form_field_title'));?></label>
                            <input id="title" name="TITLE" class="form-control" placeholder="<?=$this->e($info->_e('form_field_title_placeholder'));?>" type="text" minlength="3" maxlength="150" required="" value="<?php echo strip_tags($dataView['TITLE']);?>">
                        </div>
                        <div class="form-group">
                            <label for="descl"><?=$this->e($info->_e('form_field_descl'));?></label>
                            <div style="background-color: #ffffff">
                                <div id="EVTDESC" name="DESCL" style="height: 150px">
                                    <?php echo $dataView['DESCL'];  //strip_tags($dataView['DESCL'], '<h1><h2><h3><h4><strong><i><p><u><ul><li><span><blockquote><a>'); ?>
                                </div>
                            </div>
                        </div>



                        <!-- <div class="form-group">
                            <label for="url"><?=$this->e($info->_e('form_field_url'));?></label>
                            <input id="url" name="URL" class="form-control" placeholder="<?=$this->e($info->_e('form_field_url_placeholder'));?>" maxlength="150" type="text" value="<?php if ($dataView['URL'] != null) echo  strip_tags(filter_var($dataView['URL'], FILTER_SANITIZE_URL));?>">
                        </div> -->


                        <?php if (isset($csrf_token)) echo $csrf_token; ?>
                </div>
            </div>

            <div class="modal-footer">
                <button id="btn_save_desc" type="submit" class="btn btn-primary pull-right btn-margin-left5"><?=$this->e($info->_e('btn_save'));?></button>
                <!-- <button id="btn_cancel_desc" type="button" class="btn btn-sm btn-danger pull-right">Cancel</button>-->
                <input type="hidden" name="UID" value="<?php echo $urllink;?>">
            </div>

            </form>
        </div>
    </div>
</div>