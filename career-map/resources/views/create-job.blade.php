@extends('layouts.app')
@section('content')
    <br><br>
    <div class="container">
        <link href="libs/sweetalert2/dist/sweetalert2.min.css"/>
        <h1>Create a New Job</h1>

        <div id="success-message" class="alert alert-success" style="display: none;"></div>
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>

        <form id="create-job-form" action="{{ route('jobs.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>

            <button type="button" class="createBtn btn btn-primary">Create Job</button>
        </form>
    </div>

    <br><br>
    <div class="container">
        <h1>Job List</h1>
        <br>
        <ol id="job-list">
        </ol>

    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Handle form submission using AJAX
            $('.createBtn').on('click', function(event) {
                event.preventDefault();
                createJob();
            });

            function createJob() {
                let form = $('#create-job-form');
                let url = form.attr('action');

                let formData = new FormData(form[0]);

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you wish to create the job?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    confirmButtonClass: 'btn-danger'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX request
                        $.ajax({
                            type: 'POST',
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {
                                Swal.fire({
                                    title: 'Saving Job',
                                    text: 'Please wait...',
                                    icon: 'info',
                                    showConfirmButton: false
                                });
                            },
                            success: function(response) {
                                // Success response
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Job created successfully',
                                    icon: 'success'
                                }).then(() => {
                                    // Reload the page to display the form again
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                // Error response
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Please refresh the page and try again. If the problem persists, contact tech support.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: '{{ route('jobs.index') }}',
                success: function(response) {
                    let jobs = response.jobs;
                    let jobList = $('#job-list');

                    if (jobs.length > 0) {
                        $.each(jobs, function(index, job) {
                            let jobLink = $('<a>').attr('href', '#').text(job.title).on('click', function(event) {
                                event.preventDefault();
                                showJobDetails(job);
                            });
                            let jobListItem = $('<li>').append(jobLink);
                            jobList.append(jobListItem);
                        });
                    } else {
                        jobList.html('No jobs found.');
                    }
                },
                error: function(xhr, status, error) {
                }
            });


            function showJobDetails(job) {
                let createdAt = new Date(job.created_at).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                let swalContent = `<strong>Created At:</strong> ${createdAt}<br><br><strong>Description:</strong><br>${job.description}`;

                swalContent += `<br><br><strong>Status:</strong> ${job.status ? 'Accepted' : 'Not Accepted'}`;

                Swal.fire({
                    title: job.title,
                    html: swalContent,
                    icon: 'info',
                    showCancelButton: !job.status,
                    confirmButtonText: job.status ? 'OK' : 'Accept',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            resolve();
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed && !job.status) {
                        Swal.fire({
                            title: 'Confirmation',
                            text: 'Are you sure you want to accept this job?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Accept',
                            cancelButtonText: 'Cancel'
                        }).then((confirmation) => {
                            if (confirmation.isConfirmed) {
                                let orderedListItem = $(`#job-list li:contains('${job.title}')`);
                                let acceptButton = orderedListItem.find('.swal2-confirm');
                                let acceptedBadge = $('<span>').addClass('badge badge-success ml-2').text('Accepted');
                                orderedListItem.append(acceptedBadge);
                                acceptButton.text('OK');
                                job.status = 'Accepted';

                                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                                $.ajax({
                                    url: `/jobs/${job}/accept`,
                                    type: 'POST',
                                    data: {
                                        _token: csrfToken,
                                        id: job.id,
                                        status: 'Accepted'
                                    },
                                    success: function (response) {
                                        job = response.job;
                                    },
                                    error: function (xhr, status, error) {
                                        console.log(error);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

    </script>
@endsection

