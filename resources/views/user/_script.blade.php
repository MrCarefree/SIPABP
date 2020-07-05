<script>
    function initDatatable(){
        var option = {

        }
    }
    $(document).ready(function () {
        $('#user_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('user.datatable') }}',
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'username'},
                {data: 'role'},
                {data: 'created_at'},
                {data: 'action'},
            ]
        })
    })
</script>
