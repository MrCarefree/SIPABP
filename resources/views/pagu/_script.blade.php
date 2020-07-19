<script>
    let table, alertUtil, formEdit, loadingBtn;

    function initBtnEvents() {
        $('#form-edit input, #form-edit select').keypress(function (e) {
            if (e.which === 13) {
                $('#btn-edit-submit').click()
            }
        })
    }

    function initDTEvents() {
        $('.btn_edit').on('click', function () {
            const targetId = $(this).data('id');
            $.get('{{ route('pagu.get') }}', {id: targetId}, 'json')
                .done(function (response) {
                    if (response.status) {
                        formEdit.populateForm(response.data);
                        $('#modal-edit').modal('show');
                    } else {
                        alertUtil.showFailedAlert(response.message)
                    }
                })
                .fail(function (response) {
                    let errorMessage;
                    if (response.responseJSON.hasOwnProperty('errors')) {
                        if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                            errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                return error.join("<br>")
                            }).join("<br>");
                            alertUtil.showFailedAlert(errorMessage)
                        } else if (typeof response.responseJSON.errors === 'string') {
                            alertUtil.showFailedAlert(response.responseJSON.errors)
                        }
                    } else if (response.responseJSON.hasOwnProperty('message')) {
                        alertUtil.showFailedAlert(response.responseJSON.message);
                    } else {
                        alertUtil.showFailedAlert('Gagal menghubungkan ke server, silahkan coba kembali')
                    }
                });
        });
    }

    function initLoadingBtn() {
        loadingBtn = {
            startEdit: () => {
                const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                $('#btn-edit-submit').attr('disabled', true).html(spinner + ' Loading...')
            },
            stopEdit: () => {
                $('#btn-edit-submit').attr('disabled', false).text('Update')
            },
        }
    }

    function initAlert() {
        alertUtil = {
            showFailedAlert: (message) => {
                $('#failed-message').html(message);
                $('#failed-alert').show();
                $("html, body").animate({scrollTop: 0}, 600);
            },
            showFailedAlertAdd: (message) => {
                $('#failed-message-add').html(message);
                $('#failed-alert-add').show();
                $("#modal-add .modal-body").animate({scrollTop: 0}, 600);
            },
            showFailedAlertEdit: (message) => {
                $('#failed-message-edit').html(message);
                $('#failed-alert-edit').show();
                $("#modal-edit .modal-body").animate({scrollTop: 0}, 600);
            },
            showSuccessToast: (message) => {
                Toast.fire({icon: 'success', title: message})
            }
        }
    }

    function initDataTable() {
        table = $('#user-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('pagu.datatable') }}',
            columns: [
                {data: 'id', width: '25px'},
                {data: 'name'},
                {data: 'pagu'},
                {data: 'action', width: '70px'},
            ],
        });

        table.on('draw', function () {
            initDTEvents()
        })
    }

    function initEditForm() {
        const editForm = $('#form-edit');

        const validator = editForm.validate({
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            highlight: function (element) {
                $(element).addClass('is-invalid')
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid')
            },
            rules: {
                id: {
                    required: true,
                    digits: true
                },
                pagu: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                id: {
                    required: 'Id tidak boleh kosong',
                    digits: 'Id hanya boleh angka'
                },
                pagu: {
                    required: 'Pagu tidak boleh kosong',
                    digits: 'Pagu hanya boleh angka'
                },
            },
            invalidHandler: function () {
                $("#modal-edit .modal-body").animate({scrollTop: 0}, 600);
            }
        });

        $('#btn-edit-submit').click(function () {
            const isValid = editForm.valid();
            if (isValid) {
                loadingBtn.startEdit();
                $.ajax({
                    type: 'PUT',
                    url: '{{ route('pagu.update') }}',
                    data: editForm.serialize(),
                    dataType: 'json'
                }).done(function (response) {
                    loadingBtn.stopEdit();
                    if (response.status) {
                        $('#modal-edit').modal('hide');
                        table.ajax.reload();
                        alertUtil.showSuccessToast(response.message)
                    } else {
                        alertUtil.showFailedAlertEdit(response.message)
                    }
                }).fail(function (response) {
                    loadingBtn.stopEdit();
                    let errorMessage;
                    if (response.responseJSON.hasOwnProperty('errors')) {
                        if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                            errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                return error.join("<br>")
                            }).join("<br>");
                            alertUtil.showFailedAlertEdit(errorMessage)
                        } else if (typeof response.responseJSON.errors === 'string') {
                            alertUtil.showFailedAlertEdit(response.responseJSON.errors)
                        }
                    } else if (response.responseJSON.hasOwnProperty('message')) {
                        alertUtil.showFailedAlertEdit(response.responseJSON.message);
                    } else {
                        alertUtil.showFailedAlertEdit('Gagal menghubungkan ke server, silahkan coba kembali')
                    }
                });
            }
        });

        const modalEdit = $('#modal-edit');
        modalEdit.on('hide.bs.modal', function () {
            validator.resetForm();
            editForm.trigger('reset')
        });

        const populateForm = userObject => {
            $('#edit-id').val(userObject.id);
            $('#edit-pagu').val(userObject.pagu);
        };

        return {
            validator: validator,
            populateForm: populateForm
        }
    }

    function initAjaxToken() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    }

    $(document).ready(function () {
        initAjaxToken();
        initBtnEvents();
        initLoadingBtn();
        initAlert();
        initDataTable();
        formEdit = initEditForm()
    })
</script>
