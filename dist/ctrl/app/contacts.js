requirejs(["jquery","doT","app/modules/Profile"],function(e,s,t){var a=new t;function n(t,a){if(null!=a&&!0===a.result){e("#profiles_list").empty();var n=e("#tpl_profile").html(),r=s.template(n);e.each(a.data,function(t,a){var n=r({prolnk:"/profile/"+a.UUID,proimg:a.PICTURE,ndisp:a.NDISP,uuid:a.UUID,sub:a.SUBSCRIPTION,btndesc:"Follow"});e("#profiles_list").append(n)})}}function o(t,a){if(null!==a&&!0===a.result){if(a.data.length<=0)return void e("#panel_no_contact").show();e("#contacts_list").empty();var n=e("#tpl_contact").html(),r=s.template(n);e.each(a.data,function(t,a){var n=r({prolnk:"/profile/"+a.UUID,proimg:a.PICTURE,ndisp:a.NDISP,uuid:a.UUID,sub:a.SUBSCRIPTION,req:a.REQ});e("#contacts_list").append(n)})}}!function(){var r=this;a.getContacts(r,o,null),a.search(r,n,""),e(function(){e(document).on("click","button[name='btnfollow']",function(){var n=e(this),t=n.attr("data-x-uuid"),a=n.attr("data-x-sub");"null"===a||"-1"===a?Follow(r,function(t,a){null!==a&&!0===a.result?(console.log(a.status),0===a.status?n.html('<i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-question" aria-hidden="true"></i>'):1===a.status&&n.html('<i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-check" aria-hidden="true"></i>'),n.removeClass("btn-secondary").addClass("btn-success"),n.attr("data-x-sub",a.status)):toastr.error("Error de suscripción!")},t):UnFollow(r,function(t,a){null!==a&&!0===a.result?(n.html('<i class="fa fa-user-plus" aria-hidden="true"></i>'),n.removeClass("btn-success").addClass("btn-secondary"),n.attr("data-x-sub",-1)):toastr.error("Error de suscripción!")},t)}),e(document).on("click","button[name='btncancel']",function(){var n=e(this),t=n.attr("data-x-uuid");"0"===n.attr("data-x-sub")&&UnFollow(r,function(t,a){null!==a&&!0===a.result?n.parent().parent().parent().remove():toastr.error("Error de cancelacion !")},t)}),e(document).on("click","button[name='btnconfirm']",function(){var n=e(this),t=n.attr("data-x-uuid");"0"===n.attr("data-x-sub")&&ConfirmRelation(r,function(t,a){null!==a&&!0===a.result?(n.hide(),toastr.success("Confirmacion !")):toastr.error("Error de confirmacion !")},t)}),e("#onProfileSearch").submit(function(t){a.search(r,n,r.searchData()),t.preventDefault()})})}()});