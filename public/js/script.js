$(document).ready(function() {
    //----------------SELECT 2-----------------//
    $('.js-example-basic-single').select2();


    //----------------STOCK REQUEST INPUT TEXT FIELD------------------//
    // let num = 0;
    
    $('#stockRequestSelect').change(function() {
        let name = $('#stockRequestSelect').find(':selected').html();
        let id = $('#stockRequestSelect').find(':selected').val();
        let num = $('#counter').val();
        num ++;
        $('#counter').val(num);
        $('#stockRequestTable tbody').append(
            '<tr id="stockRequestRow'+num+'">' +
            '<td>'+num+'<input type="hidden" name="item_id'+"[]"+'" value="'+id+'"></td>' +
            '<td>'+name+'</td>' +
            '<td><input type="number" name="qtySr'+"[]"+'" value="" required></td>' +
            '<td><button type="button" class="removeBtn btn btn-danger" id="stockRequestBtn'+num+'" data-id="'+num+'"><i class="bi bi-dash-square"></i></button></td>' +
            '</tr>'
        );
        $('#submitBtn').attr('disabled', false);
    });

    $('#stockRequestTable tbody').on('click', '.removeBtn', function() {
        let num = $('#counter').val();
        let id = $(this).data('id');
        $(this).closest('#stockRequestRow'+id+'').remove();
        num --;
        $('#counter').val(num);
        if (num === 0 ) {
            $('#submitBtn').prop('disabled', 'true');
        }
    });

    //--------------------STOCK REQUEST DETAIL FIELD----------------------//
    $('.detailBtnSr').click(function(){
        let kode = $(this).data('code');
        let name = $(this).data('name');
        let url = $(this).data('url');
        // console.log(url);
        $('#srCode').html(kode);
        $('#srName').html(name);
        let num = 1;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                for (let index = 0; index < data.length; index++) {
                    $('#srDetail tbody').append(
                        '<tr class="spRow" id="spRow'+num+'">' +
                        '<td>'+num+'</td>' +
                        '<td>'+data[index].itemName+'</td>' +
                        '<td>'+data[index].pivot.qtySr+'</td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
    });

    $('#closeBtn').click(function() {
        $('.spRow').remove();
    });

    //-----------------PURCHASE REQUEST INPUT TEXT FIELD-------------------//
    $('#purchaseRequestSelect').change(function() {
        let name = $('#purchaseRequestSelect').find(':selected').html();
        let id = $('#purchaseRequestSelect').find(':selected').val();
        let url = $(this).find(':selected').data('url');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let num = 1;
                // console.log(data);
                for (let index = 0; index < data.length; index++) {
                    $('#purchaseRequestTable tbody').append(
                        '<tr id="purchaseRequestRow'+num+'">' +
                        '<td>'+num+'<input type="hidden" name="item_id'+"[]"+'" value="'+data[index].id+'"></td>' +
                        '<td>'+data[index].itemName+'</td>' +
                        '<td><input type="number" name="qtyPr'+"[]"+'" value='+data[index].pivot.qtySr+' required></td>' +
                        '<td><button type="button" class="removeBtn btn btn-danger" id="purchaseRequestBtn'+num+'" data-id="'+num+'"><i class="bi bi-dash-square"></i></button></td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
        $('#submitBtn').attr('disabled', false);
    });

    $('#purchaseRequestTable tbody').on('click', '.removeBtn', function() {
        let id = $(this).data("id");
        $(this).closest('#purchaseRequestRow'+id+'').remove();
    });

    //----------------- DELETE PURCHASE REQUEST TEXT FIELD IN UPDATE-------------------//
    $('.deletePr').click(function() {
        let num = $('#counter').val();
        num--;
        // console.log(num);
        let id = $(this).attr('id');
        $('#prRow'+id+'').remove();
        if (num === 0 ) {
            $('#submitBtn').prop('disabled', 'true');
        }
    });

    //--------------------PURCHASE REQUEST DETAIL FIELD----------------------//
    $('.detailBtnPr').click(function(){
        let kode = $(this).data('code');
        let name = $(this).data('name');
        let description = $(this).data('description');
        let srCode = $(this).data('sr');
        let url = $(this).data('url');
        $('#prCode').html(kode);
        $('#prName').html(name);
        $('#srCode').html(srCode);
        $('#description').html(description);
        let num = 1;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                for (let index = 0; index < data.length; index++) {
                    $('#prDetail tbody').append(
                        '<tr class="prRow" id="prRow'+num+'">' +
                        '<td>'+num+'</td>' +
                        '<td>'+data[index].itemName+'</td>' +
                        '<td>'+data[index].pivot.qtyPr+'</td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
    });

    $('#closeBtn').click(function() {
        $('.prRow').remove();
    });

    //--------------------SUPPLIER TEXT FIELD----------------------//
    $('#supplierSelect').change(function() {
        let name = $('#supplierSelect').find(':selected').html();
        let id = $('#supplierSelect').find(':selected').val();
        let num = $('#counter').val();
        num++;
        $('#counter').val(num);
        $('#supplierTable tbody').append(
            '<tr id="supplierRow'+num+'">' +
            '<td>'+num+'<input type="hidden" name="item_id'+"[]"+'" value="'+id+'"></td>' +
            '<td>'+name+'</td>' +
            '<td><input type="number" name="harga'+"[]"+'" value="" required></td>' +
            '<td><button type="button" class="removeBtn btn btn-danger" id="supplierBtn'+num+'" data-id="'+num+'"><i class="bi bi-dash-square"></i></button></td>' +
            '</tr>'
        );
        // console.log(num);
        $('#submitBtn').attr('disabled', false);
    });

    $('#supplierTable tbody').on('click', '.removeBtn', function() {
        let num = $('#counter').val();
        let id = $(this).data("id");
        // console.log(id);
        $(this).closest('#supplierRow'+id+'').remove();
        num --;
        $('#counter').val(num);
        // console.log(num);
        if (num ===0 ) {
            $('#submitBtn').prop('disabled', 'true');
        }
    });

    //--------------------SUPPLIER DETAIL FIELD----------------------//
    $('.detailBtnSp').click(function(){
        let kode = $(this).data('code');
        let name = $(this).data('name');
        let address = $(this).data('address')
        let url = $(this).data('url');
        $('#spCode').html(kode);
        $('#spName').html(name);
        $('#spAddress').html(address);
        let num = 1;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                for (let index = 0; index < data.length; index++) {
                    $('#spDetail tbody').append(
                        '<tr class="spRow" id="spRow'+num+'">' +
                        '<td>'+num+'</td>' +
                        '<td>'+data[index].itemName+'</td>' +
                        '<td>'+data[index].pivot.harga+'</td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
    });

    $('#closeBtn').click(function() {
        $('.spRow').remove();
    });

    //--------------------PURCHASE ORDER TEXT FIELD----------------------//
    $('#prSelect').change(function() {
        $('#purchaseOrderTable tbody').empty();
        let userURL = $(this).find(':selected').data('url');
        let num = 1;
        $.ajax({
            url: userURL,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                for (let index = 0; index < data.length; index++) {
                    $('#purchaseOrderTable tbody').append(
                        '<tr id="purchaseOrderRow'+num+'">' +
                        '<td>'+num+'<input type="hidden" name="item_id'+"[]"+'" value="'+data[index].pivot.item_id+'"></td>' +
                        '<td>'+data[index].itemName+'</td>' +
                        '<td><input type="number" name="qtyPo'+"[]"+'" value="'+data[index].pivot.qtyPr+'" required></td>' +
                        '<td><input type="text" name="satuan'+"[]"+'" value="'+data[index].satuan+'" required></td>' +
                        '<td><input type="number" name="harga'+"[]"+'" value="'+data[index].harga+'" required></td>' +
                        '<td><button type="button" class="removeBtn btn btn-danger" id="supplierBtn'+num+'" data-id="'+num+'"><i class="bi bi-dash-square"></i></button></td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
        $('#submitBtn').attr('disabled', false);
    });

    $('#purchaseOrderTable tbody').on('click', '.removeBtn', function() {
        let id = $(this).data("id");
        $(this).closest('#purchaseOrderRow'+id+'').remove();
    });

    //----------------- DELETE PURCHASE ORDER TEXT FIELD IN UPDATE-------------------//
    $('.deletePo').click(function() {
        let id = $(this).attr('id');
        let num = $('#counter').val();
        num--;
        // console.log(num);
        $('#counter').val(num);
        $('#poRow'+id+'').remove();
        if (num === 0 ) {
            $('#submitBtn').prop('disabled', 'true');
        }
    });

    //--------------------PURCHASE ORDER DETAIL FIELD----------------------//
    $('.detailBtnPo').click(function(){
        let kode = $(this).data('code');
        let name = $(this).data('name');
        let prCode = $(this).data('pr');
        let description = $(this).data('description');
        let pymnt = $(this).data('pymnt');
        let url = $(this).data('url');
        $('#poCode').html(kode);
        $('#poName').html(name);
        $('#prCode').html(prCode);
        $('#description').html(description);
        $('#pymntTerms').html(pymnt);
        let num = 1;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                for (let index = 0; index < data.length; index++) {
                    $('#poDetail tbody').append(
                        '<tr class="poRow" id="poRow'+num+'">' +
                        '<td>'+num+'</td>' +
                        '<td>'+data[index].itemName+'</td>' +
                        '<td>'+data[index].pivot.qtyPo+'</td>' +
                        '<td>'+data[index].pivot.harga+'</td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
    });

    $('#closeBtn').click(function() {
        $('.poRow').remove();
    });

    //--------------------BERITA ACARA----------------------//
    $('#po_id').on('change', function() {
        // console.log('okeoke');
        var userURL = $(this).find(':selected').data('url');
        const id = $(this).find(':selected').data('id');
        // console.log(userURL);
        // console.log(id);
        $.ajax({
            url: userURL,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#spTxt').val(data[0]);
                $('#sp_id').val(data[2]);
                // console.log(data);

                for (let index = 0; index < data[1].length; index++) {
                    let num = 1;
                    $('#beritaAcaraTable tbody').append(
                        '<tr id="beritaAcaraRow'+num+'">' +
                        '<td>'+num+'<input type="hidden" name="item_id'+"[]"+'" value="'+data[1][index].id+'"></td>' +
                        '<td>'+data[1][index].itemName+'</td>' +
                        '<td><input type="number" name="qtyBa'+"[]"+'" value="" required></td>' +
                        '<td><input type="hidden" name="satuan'+"[]"+'" value="'+data[1][index].satuan+'">'+data[1][index].satuan+'</td>' +
                        '</tr>'
                    ); 
                    num++;
                }

            }
        });
    });

    //--------------------BERITA ACARA DETAIL FIELD----------------------//
    $('.detailBtnBa').click(function(){
        let kode = $(this).data('code');
        let name = $(this).data('name');
        let poCode = $(this).data('po');
        let description = $(this).data('description');
        let url = $(this).data('url');
        $('#baCode').html(kode);
        $('#baName').html(name);
        $('#poCode').html(poCode);
        $('#description').html(description);
        let num = 1;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                for (let index = 0; index < data.length; index++) {
                    $('#poDetail tbody').append(
                        '<tr class="baRow" id="baRow'+num+'">' +
                        '<td>'+num+'</td>' +
                        '<td>'+data[index].itemName+'</td>' +
                        '<td>'+data[index].pivot.qtyBa+'</td>' +
                        '<td>'+data[index].pivot.satuan+'</td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
    });

    $('#closeBtn').click(function() {
        $('.baRow').remove();
    });

    //----------------PEMAKAIAN INPUT TEXT FIELD------------------//

    $('#pemakaianSelect').change(function() {
        let name = $('#pemakaianSelect').find(':selected').html();
        let id = $('#pemakaianSelect').find(':selected').val();
        let num = $('#counter').val();
        num ++;
        $('#counter').val(num);
        $('#pemakaianTable tbody').append(
            '<tr id="pemakaianRow'+num+'">' +
            '<td>'+num+'<input type="hidden" name="item_id'+"[]"+'" value="'+id+'"></td>' +
            '<td>'+name+'</td>' +
            '<td><input type="number" name="qtyUse'+"[]"+'" value="" required></td>' +
            '<td><button type="button" class="removeBtnUse btn btn-danger" id="pemakaianBtn'+num+'" data-id="'+num+'"><i class="bi bi-dash-square"></i></button></td>' +
            '</tr>'
        );
        $('#submitBtn').attr('disabled', false);
    });

    $('#pemakaianTable tbody').on('click', '.removeBtnUse', function() {
        let id = $(this).data("id");
        let num = $('#counter').val();
        $(this).closest('#pemakaianRow'+id+'').remove();
        num --;
        $('#counter').val(num);
        // console.log(num);
        if (num ===0 ) {
            $('#submitBtn').prop('disabled', 'true');
        }
    });

    //--------------------PEMAKAIAN DETAIL FIELD----------------------//
    $('.detailBtnUse').click(function(){
        let kode = $(this).data('code');
        let name = $(this).data('name');
        let id = $(this).data('id');
        let poCode = $(this).data('po');
        let description = $(this).data('description');
        let url = $(this).data('url');
        $('#noUsed').html(kode);
        $('#useName').html(name);
        $('#useId').val(id);
        $('#poCode').html(poCode);
        $('#description').html(description);
        let num = 1;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $('#formApproved').attr('action', '/pemakaian/approved');
                for (let index = 0; index < data.length; index++) {
                    $('#useDetail tbody').append(
                        '<tr class="useRow" id="useRow'+num+'">' +
                        '<td><input type="hidden" name="item_id'+"[]"+'" value="'+data[index].pivot.item_id+'">'+num+'</td>' +
                        '<td><input type="text" name="itemName'+"[]"+'" value="'+data[index].itemName+'" readonly></td>' +
                        '<td><input type="number" name="qtyUse'+"[]"+'" value="'+data[index].pivot.qtyUse+'" readonly></td>' +
                        '</tr>'
                    );
                    num++;
                }
            }
        });
    });

    $('#closeBtn').click(function() {
        $('.useRow').remove();
    });

    //----------------- DELETE PEMAKAIAN TEXT FIELD IN UPDATE-------------------//
    $('.deleteUse').click(function() {
        let id = $(this).attr('id');
        $('#useRow'+id+'').remove();
    });
    
    //--------------------REGISTER----------------------//
    $('.registerBtn').on('click', function() {
        let id = $(this).data('link');
        let link1 = $(this).data('url');
        let url = '/register/update/'+id+'';
        $('#registerForm').attr('action', url);
        $.ajax({
            url: link1,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#username').val(data.username);
                $('#divisionSelect').val(data.division_id).change();
            }
        });
    });

    //--------------------DIVISION----------------------//
    $('.divisionBtn').on('click', function() {
        let id = $(this).data('id');
        let link = $(this).data('url');
        let url = '/division/update'+id+'';
        $('#divisionForm').attr('action', url);
        $.ajax({
            url: link,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // console.log(data);
                $('#divisionName').val(data.divisionName);
                $('#initials').val(data.initials);
                $('#divisionHead').val(data.divisionHead);
            }
        });
    });

});