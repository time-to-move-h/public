"use strict";
define (["jquery","jquery.jsonrpcclient","popper"],function($,rpcclient,popper) {
    return function(form,self,callBack,url,method) {
        try {
            var rpc = new $.JsonRpcClient({ ajaxUrl: url });
            rpc.call(
              method, form,
              function(model) {        
                  //console.log("c bon");           
                  callBack(self,model);
              },
              function(error)  {           
                  //console.log("error " + error);
                  //console.log(error);
                  callBack(self,null);          
              }
            );
         } catch (ex) {
            //console.log("error catch " + ex);
            callBack(self,null);
         }  
    }
});