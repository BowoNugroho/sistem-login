<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-6">
            <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>
            <span id="success_message"></span>
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newMenuModal">Add New Menu</a>

            <table class="table table-hover  table-striped" id="mydata">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="table">

                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal -->
<!-- Tambah -->
<div class="modal fade" id="newMenuModal" tabindex="-1" aria-labelledby="newMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newMenuModalLabel">Add New Menu</h5>
            </div>
            <form method="POST" id="addMenu">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu name">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        <span class="text-danger ">
                            <strong id="menu_error"></strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="Menu">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>
            </div>
            <form method="POST" id="editMenu">
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="menu_edit" name="menu_edit" placeholder="Menu name">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        <span class="text-danger ">
                            <strong id="menu_edit_error"></strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="editMenu">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Ajax Jquery -->
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>js/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        view_data();
        //menampilkan data di tabel dangn ajax

        function view_data() {
            $.ajax({
                type: 'ajax',
                url: '<?= base_url(); ?>index.php/menu/getmenu',
                async: true,
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var i;
                    var no = 0;
                    var html = '';
                    for (i = 0; i < data.length; i++) {
                        console.log(data[i].menu);
                        no++;
                        html += '<tr>' +
                            '<td>' + no + '</td>' +
                            '<td>' + data[i].menu + '</td>' +
                            '<td "text-align:right;">' + '<a class="btn btn-info btn-sm btn_edit" data-id="' + data[i].id + '" data-toggle="modal" data-target="#editMenuModal" >Edit</a>' + ' ' +
                            '<a href="javascript:void(0);" class="btn btn-danger btn-sm btn_delete" data-id="' + data[i].id + '">Delete</a>' + '</td>' +
                            '</tr>';
                    }
                    $("#table").html(html);

                }
            });
        }
        //Save Menu
        $('#addMenu').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '<?= base_url(); ?>index.php/menu/saveMenu',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        console.log(data.error);
                        if (data.menu_error != '') {
                            $('#menu_error').html(data.menu_error);
                        }
                    }
                    if (data.success) {
                        console.log(data.success);
                        location.reload();
                    }
                }
            });
            return false;
        });
        // get data Menu
        $('#table').on('click', '.btn_edit', function() {
            var id = $(this).attr('data-id');
            //console.log(id);
            $.ajax({
                url: '<?= base_url(); ?>index.php/menu/getMenuById/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $("#editMenuModal").modal('show');
                    $('input[name="id"]').val(response.id);
                    $('input[name="menu_edit"]').val(response.menu);
                }
            })
        });
        // update data Menu
        $('#editMenu').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '<?= base_url(); ?>index.php/menu/updateMenu',
                type: 'POST',
                data: $(this).serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.error) {
                        console.log(data.error);
                        if (data.menu_edit_error != '') {
                            $('#menu_edit_error').html(data.menu_edit_error);
                        }
                    }
                    if (data.success) {
                        console.log(data.success);
                        location.reload();
                    }
                }
            });
            return false;
        });
        // delete data Menu
        $('#table').on('click', '.btn_delete', function() {
            var id = $(this).attr('data-id');
            //console.log(id);
            var status = confirm('Yakin ingin menghapus?');
            if (status) {
                $.ajax({
                    url: '<?= base_url(); ?>index.php/menu/delete/' + id,
                    type: 'GET',
                    success: function(response) {
                        view_data();
                    }
                })
            }
        });

    });
</script>