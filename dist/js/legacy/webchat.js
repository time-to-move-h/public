/*xyz*/
     
  var chat = {
    messageToSend: '',
    init: function() {
      this.cacheDOM();
      this.bindEvents();
      this.render();
    },   
    cacheDOM: function() {
      this.$chatHistory = $('.chat-history');
      this.$button = $('button');
      this.$textarea = $('#message-to-send');
      this.$chatHistoryList =  this.$chatHistory.find('ul');    
      this.$peopleList = $('#people-list').find('ul');
      this.$peopleAction = $("[name$='usr']");
      this.$username = $('#chat-with');  
      this.$useruuid = $('#chat-uuid');        
      this.$currrentUUID = '0';
      this.$currrentUser = 'Djam';
    },
    bindEvents: function() {
      this.$button.on('click', this.addMessage.bind(this));
      this.$textarea.on('keyup', this.addMessageEnter.bind(this)); 
    }, 
    clearContacts: function() {
      this.$peopleList.empty();  
    },
    loadPeople: function(nname,img,uuid,status) {
        // Load People List
        //console.log($("#people-template"));        
        var template = Handlebars.compile( $("#people-template").html());
        var context = { 
          ndisp: nname,
          img: img,
          uuid: uuid,
          status: status,
          status_left: ''
        };
        this.$peopleList.append(template(context));
        searchFilter.init();
    }, 
    onReceive: function(data) {
        //console.log(this.$currrentUUID);     
        for (i = 0; i < data.length; i++) { 
            //console.log(data[i]);            
            var isResponse = (data[i]['UUID1'] === this.$currrentUUID);                 
            this.renderMsg(data[i]['MSG'],data[i]['DATSND'],data[i]['USR1'],isResponse);            
        }    
    },    
    render: function() {         
      var usr = getUserInfo();
      //console.log(usr.uuid);        
      this.scrollToBottom();      
      this.renderMsg(this.messageToSend.trim(),this.getCurrentTime(),usr.ndisp,false);
      this.$textarea.val('');      
    },    
    renderMsg: function(msg,tme,usr,res) {      
      this.scrollToBottom();
      if (msg.trim() !== '') {          
        var context = { 
          messageOutput: msg,
          time: tme,
          user: usr
        };
        if (res != true) {  
            // message
            var template = Handlebars.compile( $("#message-template").html());
            this.$chatHistoryList.append(template(context));
        } else {            
            // responses
            var templateResponse = Handlebars.compile( $("#message-response-template").html());               
            this.$chatHistoryList.append(templateResponse(context));
        }                                
        this.scrollToBottom();                
     }
        
    },   
    addMessage: function() {         
        var msg = this.$textarea.val();
        try {
            if (msg.trim() !== '') {              
                //alert(this.$currrentUUID);       
              sendMsg(this.$currrentUUID,msg);       
              this.messageToSend = msg;
              this.render();
            }
        } catch (ex) {} 
    },
    addMessageEnter: function(event) {
        // enter was pressed
        if (event.keyCode === 13) {
          this.addMessage();
        }
    },selectUsr: function(obj) {
        //console.log(obj.currentTarget);
        var ndisp = $(obj.currentTarget).attr("data-usr-ndisp");
        var uuid = $(obj.currentTarget).attr("data-usr-uuid");
        var img = $(obj.currentTarget).attr("data-usr-img");        
        this.$currrentUUID = uuid;        
        //console.log(uuid);        
        this.$username.text(ndisp);
        this.$useruuid.val(uuid);        
        $("#chat-img").attr("src", img);  
        
        this.$chatHistoryList.empty(); // Clear Messages
        // Check Msg
        readMsgHist(uuid,chat);        
    },   
    scrollToBottom: function() {
       if (this.$chatHistory.length > 0) {
           this.$chatHistory.scrollTop(this.$chatHistory[0].scrollHeight);
       }
    },
    getCurrentTime: function() {
      return new Date().toLocaleTimeString().
      replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
    },
    getRandomItem: function(arr) {
      return arr[Math.floor(Math.random()*arr.length)];
    }    
  };
  
   
  

  


  
  
  


//  $("#usr").on( "click", function() {
//    console.log( "zerzer" );
//  });
   
  var searchFilter = {
    options: { valueNames: ['name'] },
    init: function() {        
     var userList = new List('people-list', this.options);     
     //console.log(userList);     
     var noItems = $('<li id="no-items-found">No items found</li>');      
      userList.on('updated', function(list) {                  
        if (list.matchingItems.length === 0) {
          $(list.list).append(noItems);
        } else {
          noItems.detach();
        }
      });
    }
  }; 
    
  //var usr = getUserInfo();
  //console.log(usr);




$( document ).ready(function(){    
  
   $(document).on( "click", "div[name='usr']", function(event) {    
    chat.selectUsr(event);    
   });  
  
   $("#showchat").click(function() {
    chat.init();
    chat.clearContacts();
    getContactsList(chat);
    $("#msgModal").modal();    
  });     
  
});