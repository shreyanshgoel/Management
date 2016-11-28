$(document).ready(function() {

    $('#add_item').on('click', function() {

        no_items = $('#no_items');

        target = $('#items_added');

        target2 = $('#inventory');

        inventory = target2.val();

        target3 = $('#item');

        item = target3.val();

        target4 = $('#price');

        price = target4.val();

        target5 = $('#quantity');

        quantity = target5.val();

        target6 = $('#item_name');

        name = target6.val();

        if (inventory && item && price && quantity) {

            no_items.css('display', 'none');

            target.append('<div class="alert alert-success"><input type="text" name="item_id[]" value="' + item + '" hidden><input type="text" name="price[]" value="' + price + '" hidden><input type="text" name="quantity[]" value="' + quantity + '" hidden><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>' + name + '!</strong> ' + quantity + ' pieces for Rs ' + price + '</div>');

        } else {

            alert('Select Item');
        }

    });

    $('#inventory').on('change', function() {

        var self = $(this),
            target = $('#item');

        target.html('<option value="">Select Item</option>');

        Request.post({ action: "ajax/get_inventory_items/" + self.val() }, function(data) {

            if(data != null){
                $.each(data, function(index, value) {

                    if (value._entry1 != undefined) {

                        target.append('<option value="' + value.__id + '">' + value._entry1 + '</option>');

                    }
                });
            }
        });

    });


    $('#item').on('change', function() {

        var self = $(this),
            target = $('#quantity');

        target.html('<option value="">Select Quantity</option>');

        Request.post({ action: "ajax/get_item_quantity_and_price/" + self.val() }, function(data) {

            $.each(data, function(index, value) {

                if (value._entry1 != undefined) {

                    var i = 1;

                    var q = value._entry2 - value._entry3;

                    while (i <= q) {
                        target.append('<option value="' + i + '">' + i + '</option>');
                        i = i + 1;
                    }

                    target2 = $('#price');

                    target2.val(value._entry4);

                    target3 = $('#item_name');

                    target3.val(value._entry1);

                }
            });
        });

    });

    $('.add').on('click', function() {

        var self = $(this),
            target = $('#data-body');

        var type = $('#type').val();

        var v = parseInt(self.val()) + 1;

        var n = parseInt($('.row-number').val());

        var n_dup = n;
        var n2 = parseInt($('.numberofrows').val());

        while (n < n_dup + n2) {

            target.append('<tr id="data-td' + n + '"><td></td></tr>');

            var i = 1;
            while (i < v) {
                var target2 = $('#data-td' + n);

                if (i == 5 || i == 6 || i == 7 || i == 8 || type == 'other') {

                    target2.append('<td><input type="text" class="form-control" name="entry' + n + '_' + i + '"></td>');

                } else {

                    target2.append('<td><input type="text" class="form-control" name="entry' + n + '_' + i + '" required></td>');

                }

                i = i + 1;
            }

            n = n + 1;
        }

        target2.append('</tr>');

        var target3 = $('#row-id');

        target3.html('<input type="text" class="row-number" name="row-number" value="' + n + '"  hidden="">');

        document.getElementById("saveandcancel").style.display = "block";


    });


    $('.cancel').on('click', function() {


        var n = parseInt($('.row-number').val());

        var i = 1;
        while (i < n) {
            var target = $('#data-td' + i);

            target.remove();

            i = i + 1;
        }

        var target2 = $('#row-id');

        target2.html('<input type="text" class="row-number" name="row-number" value="1"  hidden="">');

        document.getElementById("saveandcancel").style.display = "none";


    });

    $('.edit').on('click', function() {

        var self = $(this),
            target = $('.modal-body');
        target.html('');

        var v = self.val();

        Request.post({ action: "ajax/edit/" + v }, function(data) {

            var i = 0;
            var column1, column2, column3, column4, column5, column6, column7, column8, column9, column10;

            $.each(data, function(index, value) {

                if (value._column1_name != null && value._column1_name != undefined) {

                    column1 = value._column1_name;
                    i = i + 1;
                }

                if (value._column2_name != null) {

                    column2 = value._column2_name;
                    i = i + 1;
                }
                if (value._column3_name != null) {

                    column3 = value._column3_name;
                    i = i + 1;
                }
                if (value._column4_name != null) {

                    column4 = value._column4_name;
                    i = i + 1;
                }
                if (value._column5_name != null) {

                    column5 = value._column5_name;
                    i = i + 1;
                }
                if (value._column6_name != null) {

                    column6 = value._column6_name;
                    i = i + 1;
                }
                if (value._column7_name != null) {

                    column7 = value._column7_name;
                    i = i + 1;
                }
                if (value._column8_name != null) {

                    column8 = value._column8_name;
                    i = i + 1;
                }
                if (value._column9_name != null) {

                    column9 = value._column9_name;
                    i = i + 1;
                }
                if (value._column10_name != null) {

                    column10 = value._column10_name;
                    i = i + 1;
                }

            });

            $.each(data, function(index, value) {

                if (i > 0 && value._entry1 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column1 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry1" value="' + value._entry1 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry2 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column2 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry2" value="' + value._entry2 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry3 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column3 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry3" value="' + value._entry3 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry4 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column4 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry4" value="' + value._entry4 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry5 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column5 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry5" value="' + value._entry5 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry6 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column6 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry6" value="' + value._entry6 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry7 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column7 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry7" value="' + value._entry7 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry8 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column8 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry8" value="' + value._entry8 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry9 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column9 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry9" value="' + value._entry9 + '"></div></div><br><br>');
                    i = i - 1;
                }

                if (i > 0 && value._entry10 != undefined) {

                    target.append('<div class="form-group"><label class="col-sm-2 control-label">' + column10 + '</label><div class="col-sm-10"><input type="text" class="form-control" name="edit_entry10" value="' + value._entry10 + '"></div></div><br><br>');
                    i = i - 1;
                }

            });

            target.append('<input type="text" name="edit_entry_number" value="' + v + '"  hidden="">');



        });
    });

    $('.edit_sc').on('click', function() {

        var self = $(this),
            target = $('.modal-body');
        target.html('');

        var v = self.val();

        Request.post({ action: "ajax/edit_sc/" + v }, function(data) {

            $.each(data, function(index, value) {

                if (value._name != undefined) {
                    target.append('<div class="form-group"><label class="col-sm-2 control-label">Name</label><div class="col-sm-10"><input type="text" class="form-control" name="name" value="' + value._name + '" required></div></div><br><br>');
                    target.append('<div class="form-group"><label class="col-sm-2 control-label">Email</label><div class="col-sm-10"><input type="email" class="form-control" name="email" value="' + value._email + '" required></div></div><br><br>');
                    target.append('<div class="form-group"><label class="col-sm-2 control-label">Phone</label><div class="col-sm-10"><input type="text" class="form-control" name="phone" value="' + value._phone + '" required></div></div><br><br>');
                    target.append('<div class="form-group"><label class="col-sm-2 control-label">States</label><div class="col-sm-10"><select name="state" class="form-control" id="edit_state" required></select></div></div><br><br>');

                    Request.post({ action: "ajax/states" }, function(data2) {

                        target2 = $('#edit_state');

                        $.each(data2, function(index2, value2) {

                            if (value2._name != undefined) {

                                if (value._state == value2._name) {

                                    target2.append('<option selected>' + value2._name + '</option>');

                                } else {

                                    target2.append('<option>' + value2._name + '</option>');
                                }

                            }

                        });

                    });


                    target.append('<div class="form-group"><label class="col-sm-2 control-label">Address</label><div class="col-sm-10"><input type="text" class="form-control" name="address" value="' + value._address + '" required></div></div><br><br>');
                }
            });

            target.append('<input type="text" name="edit_sc_id" value="' + v + '"  hidden="">');



        });
    });

    $('.edit_ps').on('click', function() {

        var self = $(this),
            target = $('.modal-body');
        target.html('');

        var v = self.val();

        Request.post({ action: "ajax/edit_sc/" + v }, function(data) {


        });
    });

    $('#check_email_btn').on('click', function() {

        document.getElementById('loading').style.display = 'block';
        document.getElementById('cross_sign').style.display = 'none';
        document.getElementById('check_sign').style.display = 'none';

        var v = $('#check_email').val();

        Request.post({ action: "ajax/check_email", data: {email: v}}, function(data) {

            $.each(data, function(index, value){

                if(value == 1){

                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('cross_sign').style.display = 'none';
                    document.getElementById('check_sign').style.display = 'block';

                }

                if(value == 0){

                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('cross_sign').style.display = 'block';
                    document.getElementById('check_sign').style.display = 'none';

                }
            });

        });

    });
});

