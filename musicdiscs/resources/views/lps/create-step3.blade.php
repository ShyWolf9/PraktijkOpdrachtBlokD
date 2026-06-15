@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Add LP Images - Step 3/5</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="alert alert-info mb-3">
                            <strong>LP Summary So Far:</strong><br>
                            <strong>Artist:</strong> {{ session('lp_create.artist') }}<br>
                            <strong>Album:</strong> {{ session('lp_create.album') }}
                        </div>

                        <form action="{{ route('lps.create-step4') }}" method="POST" enctype="multipart/form-data" id="imageForm">
                            @csrf

                            <div class="mb-3">
                                <label for="cover_images" class="form-label fw-bold">Upload LP Cover Images</label>
                                <input type="file" class="form-control @error('cover_images.*') is-invalid @enderror"
                                       id="cover_images" name="cover_images[]" multiple accept="image/*">
                                @error('cover_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Optional: Upload one or more images (first one will be used as cover). You can upload multiple files at once.</small>
                            </div>

                            <!-- Image preview section -->
                            <div id="imagePreview" class="mb-4" style="display:none;">
                                <label class="form-label fw-bold">Selected Images Preview</label>
                                <div id="previewContainer" class="d-flex gap-2 flex-wrap">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1" onclick="history.back();">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button>
                                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                        <i class="bi bi-arrow-right"></i> Next: LP Details
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('cover_images');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';

            if (this.files.length === 0) {
                imagePreview.style.display = 'none';
                return;
            }

            imagePreview.style.display = 'block';

            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.style.cssText = 'position:relative; width:120px; margin-bottom:10px;';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.cssText = 'width:100%; height:120px; object-fit:cover;';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm';
                    removeBtn.style.cssText = 'position:absolute; top:2px; right:2px; padding:2px 6px;';
                    removeBtn.innerHTML = '×';
                    removeBtn.onclick = function(e) {
                        e.preventDefault();
                        const dataTransfer = new DataTransfer();
                        Array.from(fileInput.files).forEach((f, i) => {
                            if (i !== index) dataTransfer.items.add(f);
                        });
                        fileInput.files = dataTransfer.files;
                        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    };
                    
                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection

