define (['croppie','app/modules/Upload'],function(croppie,Upload) {
    return function() {
        var $uploadCrop = null;
        var $uploadCropZone = null;
        var callback_result = null;
        var options = null;
        var uid = null;
        var self = this;

        function CropImage() {}

        CropImage.setOptions = function(options) {
            self.options = options;
        }

        CropImage.setUid = function(uid) {
            self.uid = uid;
        }

        CropImage.setCallBack = function(callback) {
            callback_result = callback;
        }
        CropImage.setUploadToken = function(upload_token) {
            self.upload_token = upload_token;
        }

        CropImage.setUploadCropZone = function($uploadCropZone) {
            self.$uploadCropZone = $uploadCropZone;
            $uploadCropZone.croppie('destroy');
            self.$uploadCrop = $uploadCropZone.croppie(self.options);
        }

        CropImage.crop = function() {
            self.$uploadCropZone.croppie('result', {
                type: 'blob',
                size: 'viewport',
                format: 'jpeg',
                backgroundColor:'white'
            }).then(function (resp) {
                //console.log(resp);
                var upload = new Upload();
                //console.log(upload);
                upload.init(self.upload_token,self.uid);
                var myFile = upload.blobToFile(resp, "x.jpg");
                //----------------------------------------------------------------------
                //console.log(myFile);
                upload.setCallBack(function(message) {
                    if (callback_result != null) callback_result(message);
                });
                upload.addFile(myFile);
                upload.upload();
            });
        }

        CropImage.readFile = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    self.$uploadCropZone.addClass('ready');
                    self.$uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        //console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(input.files[0]);
                return true;
            } else {
                console.log("Sorry - you're browser doesn't support the FileReader API");
            }
            return false;
        }
        return CropImage;
    }
});