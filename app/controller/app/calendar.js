requirejs(['jquery','moment','calendar/fc','masonry','doT','app/modules/Event','app/modules/Channel','app/modules/Utils'], function($,moment,fc,Masonry,doT,Event,Channel,Utils) {
       
var event = new Event();    
var channel = new Channel();    
var utils = new Utils();
var msnry = null;

$('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,basicWeek,basicDay'
    },
    /*defaultDate: '2016-09-12',*/
    navLinks: true, // can click day/week names to navigate views
    editable: false,    
    events: '/data/eventscalfeed'        
    /*eventLimit: true, // allow "more" link when too many events*/					
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    if (msnry) msnry.layout();      
})



// Contacts List View Model
function CalendarListViewModel() {    
    var self = this;        
    event.getCalendar(self,onLoadEvents,null);    
    //channel.getChannelsCombo(self, onLoadChannels, null);    
}

new CalendarListViewModel();
        
});