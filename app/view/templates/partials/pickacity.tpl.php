<div class="container">
<div class="row">
<div class="col-lg-8 offset-lg-3 col-md-8">

    <div class="text-center"><p><h1><?=$this->e($info->_e('context_title'));?></h1></p></div>

    <hr>

    <form id="fpickacity" data-parsley-validate>

    <div class="card-body" style="padding-bottom: 150px">

        <div class="select-box">
            <div class="select-box__current" tabindex="1">
                <div class="select-box__value">
                    <input class="select-box__input" type="radio" id="0" value="Amsterdam" name="CITY" checked="checked"/>
                    <p class="select-box__input-text">Amsterdam</p>
                </div>
                <div class="select-box__value">
                    <input class="select-box__input" type="radio" id="1" value="Brussels" name="CITY" />
                    <p class="select-box__input-text">Brussels</p>
                </div>
<!--                <div class="select-box__value">-->
<!--                    <input class="select-box__input" type="radio" id="2" value="Paris" name="CITY" />-->
<!--                    <p class="select-box__input-text">Paris</p>-->
<!--                </div>-->
                <img class="select-box__icon" src="http://cdn.onlinewebfonts.com/svg/img_295694.svg" alt="Arrow Icon" aria-hidden="true"/>
            </div>

            <ul class="select-box__list">
                <li>
                    <label class="select-box__option" for="0" aria-hidden="aria-hidden">Amsterdam</label>
                </li>
                <li>
                    <label class="select-box__option" for="1" aria-hidden="aria-hidden">Brussels</label>
                </li>
<!--                <li>-->
<!--                    <label class="select-box__option" for="2" aria-hidden="aria-hidden">Paris</label>-->
<!--                </li>-->
            </ul>
        </div>
    </div>

<!--        btn-block-->

    <button id="bsubmit" class="btn btn-lg btn-primary btn-block text-white" type="submit">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
        <?=$this->e($info->_e('form_btn_save'));?>
    </button>

    <a href="/home" id="bcancel" class="btn btn-secondary btn-block text-white">
        <?=$this->e($info->_e('form_btn_cancel'));?>
    </a>

    </form>

</div>


</div></div>