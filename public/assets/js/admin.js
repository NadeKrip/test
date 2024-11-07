function updateTable(link, copyBtn = false){
    console.log('/admin/' + link + '/get-list');
    $.ajax({
        url: '/admin/' + link + '/get-list',
        method: 'POST',
        success: function (response) {
            response = JSON.parse(response);
            let data = response['data'];
            console.log(data);

            let html = ``;

            for(let i = 0; i < data.length; i++){
                if(link == 'users'){
                    html +=
                        `<tr data-id="${data[i]['id']}">
                            <td>
                                ${data[i]['login']}
                            </td>
                            <td>
                                ${data[i]['name']} ${data[i]['surname']}
                            </td>
                            <td>
                                ${data[i]['email']}
                            </td>
                            <td>
                                ${data[i]['role']}
                            </td>
                            <td style="width: 200px;">
                                <a href="#" class="js-edit" data-id="${data[i]['id']}" 
                                data-bs-toggle="modal" data-bs-target="#editModal" data-name="${link}">Редактировать</a>
                            </td>
                        </tr>`;
                }
                else {
                    if (copyBtn) {
                        html +=
                            `<tr data-id="${data[i]['id']}">
                            <td style="width: 10px;">
                                <input type="checkbox" class="form-check-input" name="chekbox[]" value="${data[i]['id']}">
                            </td>
                            <td>
                                ${data[i]['name']}
                            </td>
                            <td style="width: 200px;">
                                <a href="/admin/${link}/copy?id=${data[i]['id']}">Копировать</a>
                                <a href="/admin/${link}/edit?id=${data[i]['id']}">Редактировать</a>
                            </td>
                        </tr>`;
                    } else {
                        html +=
                            `<tr data-id="${data[i]['id']}">
                            <td style="width: 10px;">
                                <input type="checkbox" class="form-check-input" name="chekbox[]" value="${data[i]['id']}">
                            </td>
                            <td>
                                ${data[i]['name']}
                            </td>
                            <td style="width: 200px;">
                                <a href="#" class="js-edit" data-id="${data[i]['id']}" 
                                data-bs-toggle="modal" data-bs-target="#editModal" data-name="${link}">Редактировать</a>
                            </td>
                        </tr>`;
                    }
                }
            }
            $('#tbody').html(html);
            $('#count').text(response['count']);
        }
    });
}

function updateEditModal(id,link){
    console.log('/admin/'+ link +'/get');
    $.ajax({
        url: '/admin/'+ link +'/get',
        method: 'POST',
        data: {id: id},
        success: function (response) {
            let data = JSON.parse(response);

            $('#idEdit').val(data['id']);
            $('#nameEdit').val(data['name']);
            switch (link){
                case 'users':
                    console.log(data)
                    $('#loginEdit').val(data['login']);
                    $('#emailEdit').val(data['email']);
                    $('#surnameEdit').val(data['surname']);
                    $('#lastnameEdit').val(data['lastname']);
                    $('#phoneEdit').val(data['phone']);
                    $('#roleEdit').val(data['role']);
                    $('#salaryEdit').val(data['salary']);
                    $('#procentEdit').val(data['procent']);
                    $('#avatar').attr('src', data['img_avatar']);
                    break;
                case 'customer':
                    $('#innEdit').val(data['inn']);
                    $('#mailingAddressEdit').val(data['mailing_address']);
                    $('#legalAddressEdit').val(data['legal_address']);
                    $('#contactFaceEdit').val(data['contact_face']);
                    $('#initialsEdit').val(data['initials']);
                    $('#phoneEdit').val(data['phone']);
                    $('#img-seal-customer-edit').attr('src', data['link_seal']);
                    $('#img-signature-customer-edit').attr('src', data['link_signature']);
                    break;
            }

            console.log(data);

        }
    })
}

function generatePassword(length = 12) {
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
}
$(function(){

    $('body').on('click', '.js-edit',function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        console.log('test')
        updateEditModal(id, name);
    });

    $('#form-edit-modal').submit(function (e){
        e.preventDefault();
        let name = $(this).data('name');
        let data = $(this).serializeArray();

        console.log(data);

        $.ajax({
            url: '/admin/'+ name +'/edit',
            method: 'POST',
            data: data,
            success: function (response) {
                updateTable(name);
            }
        })
    });
    $('#btn-submit-edit-form').click(function () {
        $('#form-edit-modal').trigger('submit');
    });


    $('#check-all-checkbox').change(function () {
        if($(this).is(':checked')){
            $('#tbody .form-check-input').prop('checked', true);
        }
        else{
            $('#tbody .form-check-input').prop('checked', false);
        }
    });
    $('#btn-submit-add-form').click(function () {

        setTimeout(function () {
            $('#form-add').trigger('submit');

        },1000)
    });
    $('#delete').click(function () {
        let checkboxArray = $('.form-check-input[name="chekbox[]"]:checked');
        let name = $(this).data('name');
        let data = [];
        checkboxArray.each(function () {
            data.push($(this).val());
        })
        $.ajax({
            url:'/admin/'+ name +'/delete',
            method: 'POST',
            data: {data:data},
            success: function (response) {
                console.log(response)
                updateTable( name);
            }
        })
    });
    $('#form-add').submit(function (e) {
        e.preventDefault();

        let name = $(this).data('name');

        let data = $(this).serializeArray();

        console.log(name);
        console.log('/admin/'+ name +'/add');

        $.ajax({
            url: '/admin/'+ name +'/add',
            method: 'POST',
            data: data,
            success: function (response) {
                console.log(response)
                if(response){
                    updateTable(name);
                }
            }
        });
    });
})