@extends('layouts.master')

@section('title', 'Artikel')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Artikel</h3>
            <h6 class="op-7 mb-0">Manajemen artikel konten</h6>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Artikel</li>
            </ol>
        </nav>
    </div>

    {{-- ACTION BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted">
            Total artikel: <strong>{{ count($articles) }}</strong>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i> Tambah Artikel
        </button>
    </div>

    {{-- TABLE --}}
    <div class="card card-round shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="articles-table">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th width="90">Thumbnail</th>
                            <th width="160">Gallery</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $index => $article)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $article->title }}</td>
                                <td>{{ $article->category->name ?? '-' }}</td>

                                <td>
                                    @if ($article->thumbnail)
                                        <img src="{{ asset($article->thumbnail) }}" class="img-thumbnail"
                                            style="width:70px;height:45px;object-fit:cover">
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @foreach ($article->media->where('type', 'gallery')->take(3) as $media)
                                        <img src="{{ asset('images/' . $media->images) }}" class="img-thumbnail me-1 mb-1"
                                            style="width:50px;height:35px;object-fit:cover">
                                    @endforeach
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $article->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- EDIT MODAL --}}
                            <div class="modal fade" id="editModal{{ $article->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('articles.update', $article->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Artikel</h5>
                                                <button class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row g-3">

                                                    <div class="col-md-6">
                                                        <label class="form-label">Judul</label>
                                                        <input type="text" class="form-control" name="title"
                                                            value="{{ $article->title }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Kategori</label>
                                                        <select class="form-control" name="category_id">
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ $category->id == $article->category_id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">Konten</label>
                                                        <textarea name="content" class="form-control" rows="4">{{ $article->content }}</textarea>
                                                    </div>

                                                    {{-- THUMBNAIL --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label">Thumbnail</label>
                                                        <input type="file" class="form-control thumbnail-input"
                                                            name="thumbnail" accept="image/*">

                                                        <div class="mt-2">
                                                            <img class="img-thumbnail thumbnail-preview"
                                                                src="{{ $article->thumbnail ? asset($article->thumbnail) : '' }}"
                                                                style="width:120px;height:80px;object-fit:cover;{{ $article->thumbnail ? '' : 'display:none' }}">
                                                        </div>
                                                    </div>

                                                    {{-- GALLERY --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label">Gallery</label>
                                                        <input type="file" class="form-control gallery-input"
                                                            name="images[]" multiple accept="image/*">

                                                        <div class="mt-2 d-flex flex-wrap gap-2 gallery-preview">
                                                            @foreach ($article->media->where('type', 'gallery') as $media)
                                                                <img src="{{ asset('images/' . $media->images) }}"
                                                                    class="img-thumbnail"
                                                                    style="width:80px;height:60px;object-fit:cover">
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Tanggal Publish</label>
                                                        <input type="date" class="form-control" name="published_at"
                                                            value="{{ optional($article->published_at)->format('Y-m-d') }}">
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-primary">Update</button>
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Artikel</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Judul</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" name="category_id">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Konten</label>
                                <textarea name="content" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" class="form-control thumbnail-input" name="thumbnail"
                                    accept="image/*">
                                <div class="mt-2">
                                    <img class="img-thumbnail thumbnail-preview d-none"
                                        style="width:120px;height:80px;object-fit:cover">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gallery</label>
                                <input type="file" class="form-control gallery-input" name="images[]" multiple
                                    accept="image/*">
                                <div class="mt-2 d-flex flex-wrap gap-2 gallery-preview"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Publish</label>
                                <input type="date" class="form-control" name="published_at">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Simpan</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#articles-table').DataTable({
                pageLength: 10,
                responsive: true
            });

            // DELETE SWAL
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Hapus artikel?',
                    text: 'Artikel akan dihapus permanen',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563EB',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

        });

        // THUMBNAIL PREVIEW
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('thumbnail-input')) {
                const input = e.target;
                const preview = input.closest('.col-md-6').querySelector('.thumbnail-preview');

                if (input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        preview.src = ev.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });

        // GALLERY PREVIEW
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('gallery-input')) {
                const input = e.target;
                const container = input.closest('.col-md-6').querySelector('.gallery-preview');
                container.innerHTML = '';

                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'img-thumbnail';
                        img.style.width = '80px';
                        img.style.height = '60px';
                        img.style.objectFit = 'cover';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
