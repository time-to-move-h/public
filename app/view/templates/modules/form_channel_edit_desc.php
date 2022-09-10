<!-- Modal -->
<div id="panel_channel_edit_desc" style="visibility: hidden;display: none">
    <div>
        <div>
            <form id="fchanneldesc" data-parsley-validate>

            <div><h5 id="modalLabel3"><?=$this->e($info->_e('modal_title_edit_desc'));?></h5></div>

            <div>
                <div id="panel_edit_desc">

                        <div class="form-group">
                            <label for="title"><?=$this->e($info->_e('form_field_title'));?></label>
                            <input id="title" name="TITLE" class="form-control" placeholder="<?=$this->e($info->_e('form_field_title_placeholder'));?>" type="text" minlength="3" maxlength="50" required="" value="<?php echo strip_tags($dataView['TITLE']);?>">
                        </div>
                        <div class="form-group">
                            <label for="descl"><?=$this->e($info->_e('form_field_descl'));?></label>
                            <div id="descl" style="height: 150px">
                                <?php echo $dataView['DESCL'];  //strip_tags($dataView['DESCL'], '<h1><h2><h3><h4><strong><i><p><u><ul><li><span><blockquote><a>'); ?>
                            </div>
                        </div>
                        <?php if (isset($csrf_token)) echo $csrf_token; ?>
                </div>
            </div>

                <div>
                    <button id="btn_save_desc" type="submit" class="btn btn-primary pull-right btn-margin-left5"><?=$this->e($info->_e('btn_save'));?></button>
                    <input type="hidden" name="UID" value="<?php echo $uid;?>">
                </div>

            </form>
        </div>
    </div>
</div>