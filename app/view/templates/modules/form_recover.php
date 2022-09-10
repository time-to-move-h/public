<!-- Modal -->
<div class="modal fade" id="RForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">        
        <div class="modal-content">            
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?=$this->e($info->_e('form_forgot_title'));?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                
            </div>

            <form id="frecover" data-parsley-validate>            

                <div class="modal-body">

                    <div id="sub_account">
                        <div class="form-group">
                            <label for="raccount"><?=$this->e($info->_e('form_field_account'));?></label>
                            <input type="text" id="raccount" name="account" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="<?=$this->e($info->_e('form_field_account_placeholder'));?>" required="">
                        </div>
                    </div>

                    <!-- Success-->
                    <div id="sub_success" style="display: none">
                        <p class="lead text-center"><?=$this->e($info->_e('form_recover_success'));?></p>
                    </div>

                    <div id="sub_failed" class="card card-error" style="visibility: hidden;display: none">
                        <div class="card-body" style="color: #FF0000;">
                            <?=$this->e($info->_e('form_recover_failed'));?>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$this->e($info->_e('form_close'));?></button>
                    <button id="btn_frecover" type="submit" class="btn btn-primary"><?=$this->e($info->_e('form_send'));?></button>
                </div>

            </form>

        </div>        
    </div>
</div>