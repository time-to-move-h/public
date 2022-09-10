requirejs(['jquery','parsley','jqsobject','c/jqvmap/jquery.vmap.min','c/jqvmap/maps/jquery.vmap.world','app/modules/signup_invite'], function(jQuery,parsley,jqsobject,vmap,vmapw,signup_invite) {

    jQuery(document).ready(function() {
//     jQuery('#vmap').vectorMap({
//         map: 'world_en',
//         backgroundColor: 'rgba(0, 0, 255, 0)',
//         hoverColor: '#EE2525',
//         selectedColor: '#EE2525',
//         enableZoom: true,
//         selectedRegions: 'BE',
//         onRegionClick: function(element, code, region)
//         {
//             //console.log(document.getElementById("country").value);
//             document.getElementById('country').value = "" + code.toUpperCase();
//         }
//     });

    var txt_input = document.getElementById('email');
    txt_input.addEventListener('change', function() {
        if (txt_input.value === '') {
            document.getElementById('email').value = '';
            hideErrors();
            txt_input.focus();
        }
    });
 });

function showSuccess() {
    document.getElementById('form_success').style.visibility = 'visible';
    document.getElementById('form_success').style.display = 'block';
}

function hideErrors() {
    document.getElementById('form_success').style.visibility = 'hidden';
    document.getElementById('form_success').style.display = 'none';
    document.getElementById('error_exists').style.visibility = 'hidden';
    document.getElementById('error_exists').style.display = 'none';
    document.getElementById('error_unknown').style.visibility = 'hidden';
    document.getElementById('error_unknown').style.display = 'none';
}

function showErrorUnknown() {
    document.getElementById('error_unknown').style.visibility = 'visible';
    document.getElementById('error_unknown').style.display = 'block';
}

function showErrorExists() {
    document.getElementById('error_exists').style.visibility = 'visible';
    document.getElementById('error_exists').style.display = 'block';
}

function disableButton() {
    document.getElementById('bsubmit').disabled = true;
}

function enableButton() {
    document.getElementById('bsubmit').disabled = false;
}

$(document).on('submit', "#fsubscribe", function(e) {

    try {
       var self = this;
       var instance = $(this).parsley();
       if (! instance.isValid()) return;
       var jsonObj = $(this).serializeJSON();
       if (Object.keys(jsonObj).length === 0) return;
       console.log(jsonObj);

       disableButton();
       hideErrors();

       signup_invite(self,function(self,model) {
           //console.log(model);
           enableButton();
           if (model !== null && model.result === true) {
               $("#email").val("");
               showSuccess();
           } else if (model !== null && model.result === false) {
               if (model.code === 307) {
                   showErrorExists();
               } else {
                   showErrorUnknown();
               }
           } else {
               showErrorUnknown();
           }
       },jsonObj);

   } finally {
       e.preventDefault();
   }
});

});