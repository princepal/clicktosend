<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Dashboard</h2>

                <!-- Open your Loadboard Section -->
                <div class="mb-5">
                    <h4 class="mb-4">Open your Loadboard</h4>
                    <div class="row" id="userLoadboards">
                        {{-- <div class="col-md-6 mb-4">
                            <div class="loadboard-card">
                                <div class="mb-3">
                                    <h5 class="text-primary fw-bold">DAT One</h5>
                                </div>
                                <p class="text-muted mb-4">DAT One</p>
                                <button class="btn btn-outline-primary">Connect</button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="loadboard-card">
                                <div class="mb-3">
                                    <h5 class="text-primary fw-bold">Loadlink Technologies</h5>
                                </div>
                                <p class="text-muted mb-4">Loadlink Web 4 (New)</p>
                                <button class="btn btn-outline-primary">Connect</button>
                            </div>
                        </div> --}}
                        <div class="col-md-3 mb-4">
                            <div class="loadboard-card add-more-card">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <div class="add-icon mb-3">
                                        <i class="bi bi-plus-circle"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">Add More Loadboards</h5>
                                    <p class="text-muted text-center mb-4">Connect additional loadboard services to
                                        expand your options</p>
                                    <button class="btn btn-outline-primary" id="addLoadboardBtn">
                                        <i class="bi bi-plus me-2"></i>
                                        Add More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tutorial & Guides Section -->
                <div class="mb-5">
                    <h4 class="mb-4">Tutorial & Guides</h4>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="video-container">
                                <div class="ratio ratio-16x9">
                                    <iframe width="100%" height="400" class="rounded-lg"
                                        src="https://www.youtube.com/embed/74Wi-lQciLE?si=XwwhYDEj0yRJKLI6"
                                        title="LoadConnect.io - is the ultimate Google Chrome extension for Carriers and Dispatchers!"
                                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Video Content Preview</h6>
                                    <p class="card-text text-muted small">
                                        The video shows a spreadsheet with data including:
                                    </p>
                                    <ul class="list-unstyled small">
                                        <li>• Weight data (23,000 lbs, 40,000 lbs)</li>
                                        <li>• Numerical values (95 21, 97 20)</li>
                                        <li>• Dollar amounts ($3,200.00, $2,960.42)</li>
                                        <li>• Status indicators (Book, ✓)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addLoadboardModal" tabindex="-1" aria-labelledby="addLoadboardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLoadboardModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Add New Loadboard
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="availableLoadboards">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Load user's loadboards
            loadUserLoadboards();

            // Load available loadboards when modal opens
            /*$('#loadboardModal').on('show.bs.modal', function() {
                loadAvailableLoadboards();
            });*/

            $('#addLoadboardBtn').click(function() {
                loadAvailableLoadboards();
            });

            function loadUserLoadboards() {
                $.get('{{ route('user.loadboards') }}')
                    .done(function(data) {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(function(loadboard) {
                                html += `
                                <div class="col-md-3 mb-4 myloadboards">
                                    <div class="loadboard-card">
                                        <div class="mb-3">
                                            <img src="/build/assets/loadboardlogo/${loadboard.logo}" alt="${loadboard.name}" class="img-fluid mb-2" style="max-height: 60px;">
                                        </div>
                                        <p class="text-muted mb-4">${loadboard.name}</p>
                                        <button class="btn btn-outline-primary remove-loadboard" data-id="${loadboard.id}">Remove</button>
                                    </div>
                                </div>
                            `;
                            });
                        } else {
                            html = '';
                        }
                        /*$('#userLoadboards').html(html);*/
                        $('.myloadboards').remove();
                        $('#userLoadboards').append(html);
                    })
                    .fail(function() {
                        $('#userLoadboards').html(
                            '<div class="col-12"><p class="text-danger">Error loading loadboards.</p></div>'
                        );
                    });
            }

            function loadAvailableLoadboards() {
                // Fetch both all loadboards and user's loadboards
                $.when(
                    $.get('{{ route('loadboards.index') }}'),
                    $.get('{{ route('user.loadboards') }}')
                ).done(function(allLoadboardsRes, userLoadboardsRes) {
                    var allLoadboards = allLoadboardsRes[0];
                    var userLoadboards = userLoadboardsRes[0];
                    var userLoadboardIds = userLoadboards.map(function(lb) {
                        return lb.id;
                    });

                    let html = '';
                    allLoadboards.forEach(function(loadboard) {
                        let isAdded = userLoadboardIds.includes(loadboard.id);
                        html += `
                        <div class="col-md-3 mb-3">
                            <div class="loadboard-option-card" data-loadboard="${loadboard.name}">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="loadboard-icon mb-3">
                                            <img src="/build/assets/loadboardlogo/${loadboard.logo}" alt="${loadboard.name}" class="img-fluid mb-2" style="max-height: 60px;">
                                        </div>
                                        <h6 class="card-title">${loadboard.name}</h6>
                                        ${
                                        isAdded
                                        ? `<button class="btn btn-outline-primary btn-sm connect-btn remove-loadboard" data-id="${loadboard.id}">Remove</button>`
                                        : `<button class="btn btn-outline-primary btn-sm connect-btn add-loadboard" data-id="${loadboard.id}" data-name="${loadboard.name}">Add Loadboard</button>`
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    });
                    $('#availableLoadboards').html(html);
                }).fail(function() {
                    $('#availableLoadboards').html(
                        '<div class="col-12"><p class="text-danger">Error loading available loadboards.</p></div>'
                    );
                });
            }

            // Add loadboard
            $(document).on('click', '.add-loadboard', function() {
                const loadboardId = $(this).data('id');
                const loadboardName = $(this).data('name');

                $.post('{{ route('user.loadboards.attach') }}', {
                        loadboard_id: loadboardId,
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function(response) {
                        // Close modal and reload user loadboards
                        $('.btn-close').trigger('click');
                        loadUserLoadboards();
                        alert('Loadboard added successfully!');
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 400) {
                            alert('This loadboard is already added.');
                        } else {
                            alert('Error adding loadboard.');
                        }
                    });
            });

            // Remove loadboard (works in both dashboard and popup)
            $(document).on('click', '.remove-loadboard', function() {
                const loadboardId = $(this).data('id');
                const $btn = $(this);

                if (confirm('Are you sure you want to remove this loadboard?')) {
                    $.ajax({
                        url: '{{ route('user.loadboards.detach') }}',
                        type: 'DELETE',
                        data: {
                            loadboard_id: loadboardId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            loadUserLoadboards();
                            alert('Loadboard removed successfully!');

                            // If in popup, replace Remove with Add Loadboard
                            if ($btn.closest('#availableLoadboards').length) {
                                $btn
                                    .removeClass('remove-loadboard')
                                    .addClass('add-loadboard')
                                    .text('Add Loadboard');
                            }
                        },
                        error: function() {
                            alert('Error removing loadboard.');
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
