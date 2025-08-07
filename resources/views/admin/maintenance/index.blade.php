@extends('admin.maintenance.layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('dashboard/css/json-formatter-maintenance.css') }}">
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-10 col-sm-10 col-md-4">
            <div class="card rounded-0">
                <div class="card-header text-center">
                    <h4 class="card-title py-0 mb-0">Maintenance</h4>
                </div>
                <div class="card-body px-3">
                    <div class="row">
                        @include('admin.maintenance.action', ['action' => 'cacheClear', 'name' => 'Clear Cache'])
                        @include('admin.maintenance.action', ['action' => 'optimizeClear', 'name' => 'Optimize Cache'])
                        @include('admin.maintenance.action', ['action' => 'viewClear', 'name' => 'View Cache'])
                        @include('admin.maintenance.action', ['action' => 'routeClear', 'name' => 'Route Cache'])
                        @include('admin.maintenance.action', ['action' => 'configClear', 'name' => 'Config Cache'])
                        @include('admin.maintenance.action', ['action' => 'clearCompiled', 'name' => 'Clear Compiled'])
                        @include('admin.maintenance.action', ['action' => 'clearResetTokens', 'name' => 'Flush Reset Tokens'])
                        @include('admin.maintenance.action', ['action' => 'cronJob', 'name' => 'Run Cron Job'])
                        @include('admin.maintenance.action', ['action' => 'migrate_fresh', 'name' => 'Migrate Fresh'])
                        @include('admin.maintenance.action', ['action' => 'migrate', 'name' => 'Migrate Database'])
                        @include('admin.maintenance.action', ['action' => 'databaseBackup', 'name' => 'Database Backup'])
                        @include('admin.maintenance.action', ['action' => 'storage_link', 'name' => 'Storage Link'])
                        @include('admin.maintenance.action', ['action' => 'seed', 'name' => 'DB Seed'])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-10 col-sm-10 col-md-8">
            <div class="card h-100 rounded-0">
                <div class="card-header text-center">
                    <h4 class="card-title py-0 mb-0">Output</h4>
                </div>
                <div class="card-body p-0 h-100 rounded-0">
                    <div class="bg-dark text-success p-2 h-100">
                        <pre id="output" data-output="null">Nothing happend yet!</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Error / Exception</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 12px">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('dashboard/js/json-formatter.umd-maintenance.js') }}"></script>
    <script>
        $(function() {
            const output = (string) => $('#output').text(string);

            $('form').on('submit', function(e) {
                e.preventDefault();
                $this = $(this);

                output('Processing...');
                $.ajax({
                    url: $this.attr('action'),
                    type: 'POST',
                    data: $this.serialize(),
                    success: function(response) {
                        output(response);
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);

                        $('#staticBackdrop').modal('show');
                        const formatter = new JSONFormatter(JSON.parse(xhr.responseText));
                        $('#staticBackdrop .modal-body').html(formatter.render());
                    }
                });
            });

            $('[data-bs-dismiss="modal"]').on('click', function() {
                $('#staticBackdrop').modal('hide');
            });
        });
    </script>
@endpush
