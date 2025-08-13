<x-app-layout>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5> Add Template </h5>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @php
                        $role = auth()->user()->role;
                    @endphp
                    <div class="col-lg-7">
                        <div class="ibox-content">
                            <form id="template-form" action="{{ route('templates.store') }}" method="POST"
                                class="form-horizontal">
                                @csrf
                                <div id="detail" class="">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Name:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" id="name" class="form-control"
                                                required value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Subject:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="subject" id="subject" class="form-control"
                                                required value="{{ old('subject') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Body :</label>
                                        <div class="col-sm-10">
                                            <textarea name="body" id="body" class="form-control" rows="6">{{ old('body') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end mt-4 form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <x-primary-button class="ms-4 btn btn-primary">
                                            {{ __('Submit') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                                <a href="{{ route('templates.index') }}" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="ibox-content">
                            <h3>Allowed Variables</h3>
                            <p>Carrier (Loads):</p>
                            <ul class="variablelist">
                                <li><a class="variable-btn" data-id="company">Company</a></li>
                                <li><a class="variable-btn" data-id="deadhead">Deadhead</a></li>
                                <li><a class="variable-btn" data-id="dest">Destination</a></li>
                                <li><a class="variable-btn" data-id="email">Email</a></li>
                                <li><a class="variable-btn" data-id="origin">Origin</a></li>
                                <li><a class="variable-btn" data-id="pickupdate">Pickup Date</a></li>
                                <li><a class="variable-btn" data-id="rate">Rate</a></li>
                                <li><a class="variable-btn" data-id="referencenumber">Reference Number</a></li>
                                <li><a class="variable-btn" data-id="trip">Trip</a></li>
                                <li><a class="variable-btn" data-id="truck">Truck </a></li>
                                <li><a class="variable-btn" data-id="weight">Weight</a></li>
                                <li><a class="variable-btn" data-id="length">Length</a></li>
                                <li><a class="variable-btn" data-id="tripdeadhead">Trip + deadhead</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.tiny.cloud/1/uh3dl3j9976zmucaefjjxhx7dsvjobbow7o5znz7hjr7s24y/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script>

        <script>
            let lastFocused = 'editor'; // default

            // Track focus on subject input
            document.getElementById('subject').addEventListener('focus', function() {
                lastFocused = 'subject';
            });

            tinymce.init({
                selector: 'textarea',
                plugins: [
                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media',
                    'searchreplace', 'table', 'visualblocks', 'wordcount',
                    'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker',
                    'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage',
                    'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags',
                    'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [{
                        value: 'First.Name',
                        title: 'First Name'
                    },
                    {
                        value: 'Email',
                        title: 'Email'
                    },
                ],
                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                    'See docs to implement AI Assistant')),
                setup: function(editor) {
                    editor.on('focus', function() {
                        lastFocused = 'editor';
                    });
                }
            });
        </script>

        <script>
            document.querySelectorAll('.variable-btn').forEach(btn => {
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
                    } else {
                        tinymce.activeEditor.execCommand('mceInsertContent', false, `{-${dataId}-}`);
                        tinymce.activeEditor.focus();
                    }
                });
            });
        </script>

        <script>
            document.getElementById('template-form').addEventListener('submit', function(e) {
                if (tinymce.get('body')) {
                    tinymce.get('body').save();
                    const content = tinymce.get('body').getContent({
                        format: 'text'
                    }).trim();
                    if (!content) {
                        e.preventDefault();
                        alert('Body is required.');
                        tinymce.get('body').focus();
                        return false;
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
