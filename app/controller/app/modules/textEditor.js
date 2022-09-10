"use strict";
define (['jquery','quill'],function($,Quill) {
    return function() {

    var text_editor = {
        init: function(a) {
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                /*[{ 'list': 'ordered'}, { 'list': 'bullet' }],*/
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                /*[{ 'color': [] }, { 'background': [] }],*/          // dropdown with defaults from theme
                /*[{ 'align': [] }],*/
                /*['link','video'],*/
                ['clean'],                                         // remove formatting button
            ];
            var b = new Quill(a, {
                modules: { toolbar: toolbarOptions },
                theme: 'snow'
            });
            return b;
        }
    };

    return text_editor;
    }
});