$(function () {
    $('#side-menu').metisMenu();
});
window.opts = {};

$(function () {
    $('select[value]').each(function () {
        $(this).val(this.getAttribute("value"));
    });
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function () {
    $(window).bind("load resize", function () {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1)
            height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function () {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});

$(document).ready(function () {

    $('.add').on('click', function () {

        var self = $(this), target = $('#data-body');
        var v = parseInt(self.val()) + 1;

        var n = parseInt($('.row-number').val());

        var n_dup = n;
        var n2 = parseInt($('.numberofrows').val());

        while(n < n_dup + n2){
            
            target.append('<tr id="data-td' + n + '"><td></td></tr>');

            var i = 1;
            while(i < v){
                var target2 = $('#data-td' + n);
                target2.append('<td><input type="text" class="form-control" name="entry' + n + '_' + i + '"></td>');
                i = i + 1;
            }

            n = n +1;
        }

        target2.append('</tr>');

        var target3 = $('#row-id');

        target3.html('<input type="text" class="row-number" name="row-number" value="' + n + '"  hidden="">');

        document.getElementById("saveandcancel").style.display = "block";


    });

    $('.cancel').on('click', function () {

        
        var n = parseInt($('.row-number').val());

        var i = 1;
        while(i < n){
            var target = $('#data-td' + i);

            target.remove();

            i = i +1;
        }

        var target2 = $('#row-id');

        target2.html('<input type="text" class="row-number" name="row-number" value="1"  hidden="">');

        document.getElementById("saveandcancel").style.display = "none";


    });

    $('.edit').on('click', function () {

        var self = $(this), target = $('.modal-body');
        target.html('');

        var v = self.val();

        Request.post({ action: "users/edit/" + v }, function(data) {
        
            var i = 0;
            var column1, column2, column3, column4, column5, column6, column7, column8, column9, column10;
            
            $.each(data, function(index, value){


                if(value._column1_name != null && value._column1_name != undefined){

                    column1 = value._column1_name;
                    i = i+1;
                }
                if(value._column2_name != null){

                    column2 = value._column2_name;
                    i = i+1;
                }
                if(value._column3_name != null){

                    column3 = value._column3_name;
                    i = i+1;
                }
                if(value._column4_name != null){

                    column4 = value._column4_name;
                    i = i+1;
                }
                if(value._column5_name != null){

                    column5 = value._column5_name;
                    i = i+1;
                }
                if(value._column6_name != null){

                    column6 = value._column6_name;
                    i = i+1;
                }
                if(value._column7_name != null){

                    column7 = value._column7_name;
                    i = i+1;
                }
                if(value._column8_name != null){

                    column8 = value._column8_name;
                    i = i+1;
                }
                if(value._column9_name != null){

                    column9 = value._column9_name;
                    i = i+1;
                }
                if(value._column10_name != null){

                    column10 = value._column10_name;
                    i = i+1;
                }

            });

            $.each(data, function(index, value){

                if(i > 0 && value._entry1 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column1 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry1" value="' + value._entry1 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry2 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column2 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry2" value="' + value._entry2 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry3 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column3 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry3" value="' + value._entry3 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry4 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column4 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry4" value="' + value._entry4 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry5 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column5 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry5" value="' + value._entry5 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry6 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column6 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry6" value="' + value._entry6 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry7 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column7 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry7" value="' + value._entry7 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry8 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column8 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry8" value="' + value._entry8 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry9 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column9 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry9" value="' + value._entry9 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if(i > 0 && value._entry10 != undefined){

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column10 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry10" value="' + value._entry10 + '"></div></div><br><br>');
                    i = i - 1;
                }
            
            });

            target.append('<input type="text" name="edit_entry_number" value="' + v + '"  hidden="">');



        });
    });

    $("#category").change(function() {
        var self = $(this), target = $('#sub_category');
        target.html('<option> Select a Sub Category </option>');
        Request.post({ action: "admin/sub/" + self.val() }, function(data) {
             
            $.each(data, function(index, value){

                if(value._name != undefined){

                    target.append('<option value="' + value._id + '">' + value._name + '</option>');
                }
            });
            
        });
    });

    $("#sub_category").change(function() {
        var self = $(this), target = $('#sub_sub_category');
        target.html('<option> Select a Sub Sub Category </option>');
        Request.post({ action: "admin/sub_sub/" + self.val() }, function(data) {
             
             $.each(data, function(index, value){

                if(value._name != undefined){

                    target.append('<option value="' + value._id + '">' + value._name + '</option>');
                }
            });
            
        });
    });

    
    //initialize beautiful datetime picker
    $("input[type=date]").datepicker();
    $("input[type=date]").datepicker("option", "dateFormat", "yy-mm-dd");
    var dateFormat = $("input[type=date]").datepicker( "option", "dateFormat" );
    $("input[type=date]").datepicker( "option", "dateFormat", "yy-mm-dd" );

    $('#created_stats').submit(function (e) {
        $('#stats').html('<p class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></p>');
        e.preventDefault();
        var data = $(this).serializeArray();
        Request.get({
            action: "admin/dataAnalysis",
            data: data
        }, function (data) {
            $('#stats').html('');
            if (data.data) {
                Morris.Bar({
                    element: 'stats',
                    data: toArray(data.data),
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: ['Total']
                });
            }
        });
    });

    $('#getstats').submit(function (e) {
        $('#stats').html('<p class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></p>');
        e.preventDefault();
        var data = $(this).serializeArray();
        Request.get({
            action: "member/stats",
            data: data
        }, function (data) {
            $('#stats').html('');
            if (data.data) {
                Morris.Bar({
                    element: 'stats',
                    data: toArray(data.data),
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: ['Total']
                });
            }
        });
    });

    $('button[name=message]').click(function (e) {
        var self = this;
        window.opts.subject = $(this).data("subject");
        window.opts.email = $(this).data("from");
        $('#message_modal').modal('show');
    });

    $('#messageform').submit(function (e) {
        e.preventDefault();
        var body = $('#body').val();
        Request.post({
            action: "employer/messages",
            data: {action: 'support', subject: window.opts.subject, email: window.opts.email, body: body}
        }, function (data) {
            $('#status').html('Message Sent Successfully!!!');
            $('#message_modal').modal('hide');
        });
    });

    // find all the selectors 
    var types = $('#addOptions select');
    types.on("change", function () { // bind the change function
        var value = $(this).val();

        // if text box is selected then show it and hide the file upload or vice-versa
        if (value === "text") {
            $("#type").find("input[type='text']").toggleClass("hide").attr("required", "");
            $("#type").find("input[type='file']").toggleClass("hide");
        } else if (value === "image") {
            $("#type").find("input[type='file']").toggleClass("hide");
            $("#type").find("input[type='text']").toggleClass("hide").removeAttr("required");
        }
    });

   

    $("#searchModel").change(function() {
        var self = $(this), target = $('#searchField');
        target.html('');
        Request.get({action: "admin/fields/" + self.val()}, function(data) {
            for (obj in data) {
                if (data[obj].name) {
                    target.append('<option value="' + data[obj].name + '">' + data[obj].name + '</option>');
                }
            }
        });
    });

    $(document).on('change', '#searchField', function(event) {
        var fields = ["created", "modified"],
            date = $.inArray(this.value, fields);
        if (date !== -1) {
            $("input[name=value]").val('');
            $("input[name=value]").datepicker();
            $("input[name=value]").datepicker("option", "dateFormat", "yy-mm-dd");
        };
    });

    $('.pingStats').on('click', function (e) {
        e.preventDefault();
        var item = $(this),
            status = $('#status_' + item.data('pingid'));
        item.html('<i class="fa fa-spinner fa-pulse"></i>');

        Request.get({
            action: 'analytics/ping',
            data: {link: item.data('record')}
        }, function (data) {
            if (data.success) {
                item.html('Pinged: ' + data.count);
            } else {
                item.html('Pinged: 0');
            }

            if (data.status == "up") {
                status.html('<span class="label label-success"><i class="fa fa-arrow-up"></i> UP</span>');
            } else if (data.status == "down") {
                status.html('<span class="label label-danger"><i class="fa fa-arrow-down"></i> DOWN</span>')
            }
        });
    });

    $('#serp_stats').submit(function (e) {
        $('#stats').html('<p class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></p>');
        e.preventDefault();
        var data = $(this).serializeArray();
        Request.get({
            action: $(this).attr("action"),
            data: data
        }, function (data) {
            $('#stats').html('');
            if ($('#socialType').length !== 0) {
                $('#socialType').html(data.social.type + " of " + data.social.media);
            }
            if (data.data) {
                Morris.Line({
                    element: 'stats',
                    data: toArray(data.data),
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: [data.label || 'Rank']
                });
            }
        });
    });

    $('.addIP').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        $('#serverID').val(self.data('serverid'));
        $('#allotServerIP').modal('show');
    });

    $('.delete').click(function(e) {
        e.preventDefault();
        var self = $(this), message = '';

        if (self.data('message')) {
            message += self.data('message');
        } else {
            message += 'Are you sure, you want to proceed with the action?!';
        }
        bootbox.confirm(message, function(result) {
            if (result) {
                window.location.href = self.attr('href');
            }
        });
    });
});

function toArray(object) {
    var array = $.map(object, function (value, index) {
        return [value];
    });
    return array;
}


