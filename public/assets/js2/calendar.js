$('#calendar').fullCalendar({
    events: "http://clutchmanage.tk/ajax/calendar_events",

    droppable: true,

    editable: true,

    drop: function( date, jsEvent, ui, resourceI) { 

        var obj = JSON.parse(this.getAttribute("data-event"));

        var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        var date2 =  [year, month, day].join('-');        

        Request.post({ action: "ajax/calendar_save/", data: {date: date2, title: obj.title, color: obj.color} }, function(data) {

        });


    },

    eventClick: function(event, jsEvent, view) {
       var title = prompt('Event Title:', event.title, { buttons: { Ok: true, Cancel: false} });
       
    },
    
    eventDrop: function(event, delta, revertFunc) {

        alert(event.id);

    }

});