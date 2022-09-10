"use strict";
define (["jquery"],function($) {
    return function() {

    var form_section = {
        $sections : $('.form-section'),
        callback_before_navigate: null,
        callback_after_navigate: null,

        init: function(callback_before_navigate,callback_after_navigate) {

            form_section.callback_before_navigate = callback_before_navigate;
            form_section.callback_after_navigate = callback_after_navigate;

            $('.form-navigation .previous').click(function() {
                form_section.navigateTo(form_section.curIndex() - 1);
            });

            $('.form-navigation .next').click(function() {
                if (form_section.callback_before_navigate != null) {
                    form_section.callback_before_navigate(form_section.curIndex());
                }
                if ($('.step-form').parsley().validate({group: 'block-' + form_section.curIndex()})) {
                    form_section.navigateTo(form_section.curIndex() + 1);
                }
            });
            form_section.$sections.each(function(index, section) {
                $(section).find(':input').attr('data-parsley-group', 'block-' + index);
            });
        },

        navigateTo: function(index) {
            form_section.$sections
                .removeClass('current')
                .eq(index).addClass('current');
            $('.form-navigation .previous').toggle(index > 0);
            var atTheEnd = index >= form_section.$sections.length - 1;
            $('.form-navigation .next').toggle(!atTheEnd);
            $('.form-navigation [type=submit]').toggle(atTheEnd);

            if (form_section.callback_after_navigate != null) {
                form_section.callback_after_navigate(index);
            }
        },

        curIndex: function() {
            return form_section.$sections.index(form_section.$sections.filter('.current'));
        }
    };

    return form_section;
    }
});