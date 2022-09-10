requirejs.config({
    deps: ["app/main"],
    baseUrl: '/dist/js',
    paths: {        
        "app": "/ctrl/app",
        "c": "/dist/c",
        'facebook': 'https://connect.facebook.net/fr_BE/sdk'
    },    
    "shim": {
        "jquery.jsonrpcclient": ['jquery'],
        /*"bstrap": ["jquery","popper"],*/
        "masonry": ['jquery'],
        /*"jquery.socialfeed": ['doT'],*/
        'facebook' : {
            exports: 'FB'
        },
        'parsley/fr':['parsley'],
        'datetimepicker/bootstrap-datetimepicker.fr':['bootstrap-datetimepicker']
    }
});