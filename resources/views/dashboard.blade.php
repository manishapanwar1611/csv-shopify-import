<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Dashboard
        </h2>
    </x-slot>

    <div class="container mt-4 ">
        <div class="row g-3">
            <div class="col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title"><b>Imported Product </b></h1>
                       <table id="uploads-table" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Expand</th>
                                    <th>Upload ID</th>
                                    <th>Filename</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title"><b>Logs</b></h3>
                        <table id="log-table" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Title</th>
                                    <th>Data</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4">
               <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title"><b>Product</b></h1>
                       <table id="all-product-table" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Shopify ID</th>
                                    <th>Error</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
        $(document).ready(function () {

            var table = $('#uploads-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('uploads.datatable') }}",
                columns: [
                    { data: 'expand', orderable: false, searchable: false },
                    { data: 'id' },
                    { data: 'filename' },
                    { data: 'status' },
                    { data: 'created_at_formatted', name: 'created_at' }, // still sortable
                ]
            });

            // expand/collapse event
            $('#uploads-table').on('click', '.expand-btn', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var uploadId = $(this).data('id');

                if (row.child.isShown()) {
                    row.child.hide();
                    $(this).text('+');
                } else {
                    row.child(formatChild(uploadId)).show();
                    $(this).text('-');

                    loadChildTable(uploadId);
                }
            });

            // Child table template
            function formatChild(uploadId) {
                return `
                <div class="p-3">
                    <h5>Products in this upload</h5>
                    <table id="child-table-${uploadId}" class="table table-sm table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Shopify ID</th>
                                <th>Error</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                `;
            }

            // Load child table when expanded
            function loadChildTable(uploadId) {
                $(`#child-table-${uploadId}`).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax:"{{ route('uploads.products', ':uploadId') }}".replace(':uploadId', uploadId),
                    paging: false,
                    searching: false,
                      columns: [
                            { data: 'title' },
                            { data: 'status' },
                            { data: 'shopify_id' },
                            { data: 'errors' },
                            { data: 'created_at_formatted', name: 'created_at' }, 
                        ]
                });
            }

             
        });
         var table = $('#log-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('uploads.logs') }}",
                columns: [
                    { data: 'level'},
                    { data: 'title' },
                    { data: 'message' },
                    { data: 'created_at_formatted', name: 'created_at' }, 
                ]
            });
         var table = $('#all-product-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('uploads.all_products') }}",
                columns: [
                    { data: 'filename'},
                    { data: 'title' },
                    { data: 'status' },
                    { data: 'shopify_id' },
                    { data: 'errors' },
                    { data: 'created_at_formatted', name: 'created_at' }, 
                ]
            });

    </script>
</x-app-layout>
