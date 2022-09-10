<!-- Modal -->
<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
          
<div class="container-chat clearfix">
    <div class="people-list" id="people-list">      
    <div class="search">
        <input type="text" placeholder="search" />
        <i class="fa fa-search"></i>
    </div>                
    <ul class="list">          
      
<!--        <li class="clearfix">
          <img src="" alt="avatar" />
          <div class="about">
            <div class="name">Vincent Porter</div>
            <div class="status">
              <i class="fa fa-circle online"></i> online
            </div>
          </div>
        </li>        -->
 
    </ul>
    </div>
    
    <div class="chat">
      <div class="chat-header clearfix">
            <img id="chat-img" width="50" height="50" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="" />        
            <div class="chat-about">
                <div id="chat-with" class="chat-with"></div>
                <input id="chat-uuid" type="hidden" value="">
              <div class="chat-num-messages"></div>
            </div>
            <i class="fa fa-star"></i>
      </div>  
<!--        end chat-header -->
      
      <div class="chat-history">
        <ul>
            
<!--          <li class="clearfix">
            <div class="message-data align-right">
              <span class="message-data-time" >10:10 AM, Today</span> &nbsp; &nbsp;
              <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>              
            </div>
            <div class="message other-message float-right">
              Hi Vincent, how are you? How is the project coming along?
            </div>
          </li>-->

          
        </ul>
        
      </div> 
      <!-- end chat-history -->
      
      <div class="chat-message clearfix">
        <textarea name="message-to-send" id="message-to-send" placeholder ="Type your message" rows="1"></textarea>        
        <button>Send</button>
      </div>  
      <!-- end chat-message -->      
    </div>  
    <!-- end chat -->    
  </div>  
  <!-- end container -->

<script id="people-template" type="text/x-handlebars-template">
    <li class="clearfix">
    <div name="usr" data-usr-uuid="{{uuid}}" data-usr-ndisp="{{ndisp}}" data-usr-img="{{img}}" >
      <img width="55" height="55" src="{{img}}" class="img-circle" alt="" />
      <div class="about">
        <div class="name">{{ndisp}}</div>
        <div class="status">
          <i class="fa fa-circle {{status}}"></i> {{status_left}}
        </div>
      </div>
      </div>
    </li>
</script>
    
<script id="message-template" type="text/x-handlebars-template">
  <li class="clearfix">
    <div class="message-data align-right">
      <span class="message-data-time" >{{time}}, Today</span> &nbsp; &nbsp;
      <span class="message-data-name" >{{user}}</span> <i class="fa fa-circle me"></i>
    </div>
    <div class="message other-message float-right">
      {{messageOutput}}
    </div>
  </li>
</script>

<script id="message-response-template" type="text/x-handlebars-template">
  <li>
    <div class="message-data">
      <span class="message-data-name"><i class="fa fa-circle online"></i> {{user}}</span>
      <span class="message-data-time">{{time}}, Today</span>
    </div>
    <div class="message my-message">
      {{messageOutput}}
    </div>
  </li>
</script>

</div>
</div>