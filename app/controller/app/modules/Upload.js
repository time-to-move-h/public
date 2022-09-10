"use strict";
define (["flow"],function(Flow) {
    return function() {        
        var flow = null; 
        var callback_result = null;
        function Upload() {}                 
        Upload.init = function(upload_token,uid) {                
            flow = new Flow({
                 target:'/upload', 
                 chunkSize: 1024*1024,
                 testChunks: false,
                 query:{upload_token: upload_token,uid: uid}
            });           
            if (!flow.support) {  
              //console.log("Error: No support Image");
              return;
            }            
            //backimg.assignBrowse(document.getElementById('edit_background'), false, false, {accept: 'image/*'});            
            flow.on('fileAdded', function(file, event){
                //console.log(file, event);
            });
            flow.on('filesSubmitted', function(file) {
                //console.log("filesSubmitted");
            });
            flow.on('fileSuccess', function(file,message) {                
                if (callback_result) {
                    callback_result(message);
                }              
                //console.log(file,message);
            });
            flow.on('fileError', function(file, message){
                //console.log(file, message);
            });                        
        }           
        Upload.setCallBack = function(callback) {                        
            callback_result = callback;
        }        
        Upload.addFile = function(myFile) {                        
            if (flow) flow.addFile(myFile);
        }        
        Upload.upload = function() { 
            if (flow) flow.upload();           
        }        
        Upload.blobToFile = function blobToFile(theBlob, fileName){            
            theBlob.lastModifiedDate = new Date();
            theBlob.name = fileName;
            return theBlob;
        }            
        return Upload;        
    }
});

//            function dataURItoBlob(dataURI, callback) {
//                // convert base64 to raw binary data held in a string
//                // doesn't handle URLEncoded DataURIs - see SO answer #6850276 for code that does this
//                var byteString = atob(dataURI.split(',')[1]);
//
//                // separate out the mime component
//                var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]
//
//                // write the bytes of the string to an ArrayBuffer
//                var ab = new ArrayBuffer(byteString.length);
//                var ia = new Uint8Array(ab);
//                for (var i = 0; i < byteString.length; i++) {
//                    ia[i] = byteString.charCodeAt(i);
//                }
//
//                // write the ArrayBuffer to a blob, and you're done
//                var bb = new Blob([ab]);
//                return bb;
//            }