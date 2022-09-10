<!-- Modal -->
<div class="modal fade" id="modal_edit_tags" role="dialog" aria-labelledby="modalLabel2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel2"><?=$this->e($info->_e('page_legend_params'));?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="panel_edit_tags">
                    <form id="feventtags" data-parsley-validate>
                        <div class="form-group">
                            <label for="tags"><?=$this->e($info->_e('form_field_tags'));?></label>
                            <select class="form-control" id="tags" name="TAGS[]" required=""></select>
                        </div>
                        <button id="btn_save_tags" type="submit" class="btn btn-primary pull-right btn-margin-left5"><?=$this->e($info->_e('btn_save'));?></button>
<!--                        <button id="btn_cancel_tags" type="button" class="btn btn-sm btn-danger pull-right">Annuler</button>-->
                        <input type="hidden" name="UID" value="<?php echo $urllink;?>">
                    </form>
                </div>
            </div>
<!--            <div class="modal-footer">-->
<!--                <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>-->
<!--            </div>-->
        </div>
    </div>
</div>