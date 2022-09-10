"use strict";
requirejs(['jquery','parsley','jqsobject','app/modules/Utils','app/modules/Profile'], function($,parsley,jqsobject,Utils,Profile) {
        
// function login_redirect() {
//     var utils = new Utils();
//     var lang = utils.getUserLanguage();
//     url = "/"+lang+"/signin"; // Default page
//     setTimeout(function () {window.location.href = url;}, 5000);
// }
//
// function UserSignupValViewModel() {
//     // Data
//     var self = this;
//     // Operations
//
//     $(document).on('submit', "#fsignup_validation", function(e) {
//         var btnsubmit = $("#bsubmit");
//         try {
//             var instance = $(this).parsley();
//             if (! instance.isValid()) return;
//             var jsonObj = $(this).serializeJSON();
//             if (Object.keys(jsonObj).length === 0) return;
//             btnsubmit.prop("disabled",true);
//
//             var profile = new Profile();
//             profile.validateAccount(self,function(self,model) {
//                 if (model !== null && model.result === true) {
//                     // Validation Success
//                     alert('Votre compte est confirmé ! vous allez etre redirige dans 5 secondes.');
//                     jQuery('#sub-params').css("display","none");
//                     jQuery('#sub_success').css("display","block");
//                     login_redirect();
//                 } else {
//                     // Validation Error
//                     alert('Erreur de validation, svp veuillez verifier l\'information envoye sur votre email !');
//                 }
//             },jsonObj);
//         } finally {
//             e.preventDefault();
//             btnsubmit.prop("disabled",false);
//         }
//     });
// }
//
// new UserSignupValViewModel();
        
});