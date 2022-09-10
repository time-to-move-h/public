"use strict";
define (["./callrpc"],function(callrpc) {
    return function(self,callback,jsonObj) {
        function Ticket() {}
        Ticket.create = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/ticket','create');
        }

        Ticket.delete = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/ticket','delete');
        }

        Ticket.modify = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/ticket','modify');
        }

        Ticket.getAllTickets = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/ticket','getAllTickets');
        }

        Ticket.getTicket = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/ticket','getTicket');
        }

        Ticket.getMyTickets = function(self,callback,jsonObj) {
            callrpc([],self,callback,'/backend/ticket','getMyTickets');
        }

        Ticket.updateTicketDetails = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/ticket','updateTicketDetails');
        }

        Ticket.unlockTicket = function(self,callback,jsonObj) {
            callrpc([jsonObj],self,callback,'/backend/ticket','unlockTicket');
        }

        return Ticket;
    };
});