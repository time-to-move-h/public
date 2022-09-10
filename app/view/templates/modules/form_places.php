<!--<div class="form-group">        -->
<!--    <label for="location">--><?php //=$this->e($info->_e('form_field_location'));?><!--</label> -->
<!--    <div class="clearfix"></div>        -->
<!--    <input type="search" id="locsearch" class="form-control" style="border: 1px solid black" size="250" maxlength="250">                       -->
<!--</div>      -->
<!--    <div id="map" style="width: 500px;height: 400px"></div>-->

<div style="display: block">
<div class="form-group">
    <label for="venue"><?=$this->e($info->_e('form_field_venue'));?></label>
    <input id="venue" class="form-control" type="text" maxlength="250" name="VENUE">                                    
</div>        
<div class="form-group">            
    <label for="country_code"><?=$this->e($info->_e('form_field_country'));?></label>
<!--    <input id="country" name="COUNTRY" class="form-control" type="text" maxlength="250" required="">-->
<!--    <input type="hidden" value="0" id="country_code" name="COUNTRY_CODE">-->
    <?php if ($lang === 'fr-BE') { ?>
        <select id="country_code" name="COUNTRY_CODE" class="form-control"><option value="NL" selected="selected">Pays-Bas</option></select>
    <?php } else if ($lang === 'es-ES') { ?>
        <select id="country_code" name="COUNTRY_CODE" class="form-control"><option value="NL" selected="selected">Países bajos</option></select>
    <?php } else { ?>
        <select id="country_code" name="COUNTRY_CODE" class="form-control"><option value="NL" selected="selected">Netherlands</option></select>
    <?php } ?>
</div>     
<!-- State and PostCode -->
<div class="form-group">                
    <label class="col-form-label" for="state"><?=$this->e($info->_e('form_field_state'));?></label>
    <input id="state" name="STATE" type="text" class="form-control">
</div>    
<!-- City-->
<div class="form-group row">
    <div class="col-sm-8">
        <label class="col-form-label" for="city"><?=$this->e($info->_e('form_field_city'));?></label>
        <input id="city" name="CITY" class="form-control" type="text" maxlength="250">
    </div>        
    <div class="col-sm-4">
        <div class=""><label class="col-form-label" for="postcode"><?=$this->e($info->_e('form_field_postcode'));?></label></div>
        <div class=""><input id="postcode" name="PCODE" type="text" class="form-control"></div>
    </div>        
</div>     
<!-- Address Line 1 -->
<div class="form-group">
<!--    <div class="col-sm-8">-->
        <label class="col-form-label" for="addr1"><?=$this->e($info->_e('form_field_addr1'));?></label>
        <input id="addr1" name="STREET" maxlength="250" type="text" class="form-control">
<!--    </div>        -->
<!--    <div class="col-sm-4">
        <div class=""><label class="col-form-label" for="hn1"><?=$this->e($info->_e('form_field_housen'));?></label></div>
        <div class=""><input id="hn1" name="STREETN" type="text" class="form-control" maxlength="5"></div>       
    </div>        -->
</div>     
<!-- Address Line 2 -->  
<div class="form-group">                
    <div class=""><label for="address2"><?=$this->e($info->_e('form_field_addr2'));?></label></div>
    <div class=""><input id="address2" name="STREET2" type="text" placeholder="<?=$this->e($info->_e('form_field_addr2_placeholder'));?>" class="form-control"></div>        
</div>            
<!-- Latitude + Longitud -->
<div class="form-group row" style="display: none">        
    <div class="col-sm-6">
        <div class=""><label class="col-form-label" for="latitude"><?=$this->e($info->_e('form_field_latitude'));?></label></div>
        <div class=""><input id="latitude" name="LAT" maxlength="250" type="text" class="form-control" required=""></div>
    </div>        
    <div class="col-sm-6">
        <div class=""><label class="col-form-label" for="longitud"><?=$this->e($info->_e('form_field_longitud'));?></label></div>
        <div class=""><input id="longitud" name="LON" type="text" class="form-control" required=""></div>
        <input id="osmid" name="OSMID" type="hidden" value="0">
    </div>        
</div>
</div>