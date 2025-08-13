<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Back Link -->
                <a href="{{ url('/templates') }}" class="back-link">
                    <i class="bi bi-arrow-left me-2"></i>
                    Edit Template
                </a>

                <!-- Template Form -->
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success mt-3" id="successMessage" style="display: none;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div>
                                    <strong>Success!</strong> Template has been saved successfully.
                                </div>
                            </div>
                        </div>
                        <form id="template-form" action="{{ route('templates.update', $template) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="templateName" class="form-label">Template Name</label>
                                    <input type="text" class="form-control" id="templateName" name="name"
                                        value="{{ old('name', $template->name) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject"
                                        value="{{ old('subject', $template->subject) }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Email Content</label>
                                <div class="editor-container">
                                    {{-- <div id="editor">
                                        {{ old('body', $template->body) }}
                                    </div> --}}
                                    <textarea name="body" id="body" class="form-control" rows="6">{{ old('body', $template->body) }}</textarea>
                                </div>
                            </div>

                            {{-- <div class="mb-4">
                                <button type="button" class="btn btn-outline-secondary">
                                    Set Default
                                </button>
                            </div> --}}

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Variable Panel -->
            <div class="col-lg-4">
                <div class="variable-panel">
                    <h5 class="mb-3">Allowed Variable</h5>
                    <h6 class="text-muted mb-3">Carrier (Loads):</h6>
                    <div class="variable-tags">
                        <span class="variable-tag" data-id="company">Company</span>
                        <span class="variable-tag" data-id="deadhead">Deadhead</span>
                        <span class="variable-tag" data-id="dest">Destination</span>
                        <span class="variable-tag" data-id="email">Email</span>
                        <span class="variable-tag" data-id="origin">Origin</span>
                        <span class="variable-tag" data-id="pickupdate">Pickup Date</span>
                        <span class="variable-tag" data-id="rate">Rate</span>
                        <span class="variable-tag" data-id="referencenumber">Reference Number</span>
                        <span class="variable-tag" data-id="trip">Trip</span>
                        <span class="variable-tag" data-id="truck">Truck </span>
                        <span class="variable-tag" data-id="weight">Weight</span>
                        <span class="variable-tag" data-id="length">Length</span>
                        <span class="variable-tag" data-id="tripdeadhead">Trip + deadhead</span>
                        <span class="variable-tag" data-id="includeinemail">Include Gross in Email</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let lastFocused = 'editor'; // default
        let ckeditorInstance;

        // Track focus on subject input
        document.getElementById('subject').addEventListener('focus', function() {
            lastFocused = 'subject';
        });

        ClassicEditor.create(document.querySelector('#body'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'undo', 'redo'
                ]
            })
            .then(editor => {
                ckeditorInstance = editor;
                editor.model.document.on('change:data', () => {
                    lastFocused = 'editor';
                });
                editor.editing.view.document.on('focus', () => {
                    lastFocused = 'editor';
                });
            })
            .catch(error => {
                console.error(error);
            });

        // Variable button logic for CKEditor
        document.querySelectorAll('.variable-tag').forEach(btn => {
            btn.addEventListener('click', function() {
                const dataId = this.getAttribute('data-id');
                if (lastFocused === 'subject') {
                    const subjectInput = document.getElementById('subject');
                    const start = subjectInput.selectionStart;
                    const end = subjectInput.selectionEnd;
                    const text = subjectInput.value;
                    const insertText = `{-${dataId}-}`;
                    subjectInput.value = text.slice(0, start) + insertText + text.slice(end);
                    subjectInput.selectionStart = subjectInput.selectionEnd = start + insertText.length;
                    subjectInput.focus();
                } else if (ckeditorInstance) {
                    ckeditorInstance.model.change(writer => {
                        const insertText = `{-${dataId}-}`;
                        const selection = ckeditorInstance.model.document.selection;
                        writer.insertText(insertText, selection.getFirstPosition());
                    });
                    ckeditorInstance.editing.view.focus();
                }
            });
        });

        // Form validation for CKEditor
        document.getElementById('template-form').addEventListener('submit', function(e) {
            if (ckeditorInstance) {
                const content = ckeditorInstance.getData().replace(/<[^>]*>/g, '').trim();
                if (!content) {
                    e.preventDefault();
                    alert('Body is required.');
                    ckeditorInstance.editing.view.focus();
                    return false;
                }
            }
        });
    </script>
</x-app-layout>
