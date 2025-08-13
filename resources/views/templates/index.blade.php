<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">My Templates</h2>

                <!-- Tabs -->
                <ul class="nav nav-pills mb-4" id="templateTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="template-tab" data-bs-toggle="pill"
                            data-bs-target="#template" type="button" role="tab">
                            Template
                        </button>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="email-tab" data-bs-toggle="pill" data-bs-target="#email"
                            type="button" role="tab">
                            Email
                        </button>
                    </li> --}}
                </ul>

                <!-- Description -->
                <div class="mb-4">
                    <p class="text-muted mb-2">
                        In this section, you can generate customized email templates that you will send. These templates
                        are linked to the extension.
                    </p>
                </div>
                @if (session('success'))
                    <div class="alert alert-success mt-3" id="successMessage">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                <strong>Success!</strong> {{ session('success') }}
                            </div>
                        </div>
                    </div>
                    <script>
                        const successMessage = document.getElementById('successMessage');
                        setTimeout(() => {
                            successMessage.style.display = 'none';
                        }, 3000);
                    </script>
                @endif
                <!-- Templates Table -->
                <div class="template-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templates as $template)
                                    <tr>
                                        <td>{{ $template->id }}</td>
                                        <td>{{ $template->name }}</td>
                                        <td>{{ $template->subject }}</td>
                                        <td class="action-buttons">
                                            <a href="{{ route('templates.edit', $template) }}"
                                                class="btn btn-outline-secondary btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
