@extends('layouts.master')

@section('title', 'Artikel')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold">Artikel</h4>
        <small class="text-muted">Manajemen artikel konten</small>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa fa-plus"></i> Tambah Artikel
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="articles-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Thumbnail</th>
                        <th>Images</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $index => $article)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->category->name ?? '-' }}</td>
                            <td>
                                @if($article->thumbnail)
                                    <img src="{{ asset($article->thumbnail) }}" width="60" height="40" class="img-thumbnail">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($article->media->where('type', 'gallery')->count())
                                    @foreach($article->media->where('type', 'gallery') as $media)
                                        <img src="{{ asset('images/' . $media->images) }}" width="60" height="40" class="img-thumbnail me-1 mb-1">
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $article->id }}">
                                    <i class="fa fa-edit" style="color: white"> <span style="color:white">View</span></i>
                                </button>
                                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus artikel ini?')">
                                        <i class="fa fa-trash"> <span>Delete</span></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $article->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Artikel</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="title">Judul</label>
                                                <input type="text" class="form-control" id="title" name="title"
                                                    value="{{ old('title', $article->title) }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="category">Kategori</label>
                                                <select class="form-control" id="category" name="category_id" required>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ $category->id == $article->category_id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="content">Konten</label>
                                                <textarea name="content" class="form-control" id="content" rows="4"
                                                    required>{{ old('content', $article->content) }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="thumbnail">Thumbnail</label>
                                                <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                                                    accept=".png,.jpg,.jpeg,image/png,image/jpg,image/jpeg">
                                                @if($article->thumbnail)
                                                    <div class="mt-2">
                                                        <img src="{{ asset($article->thumbnail) }}" width="100" class="img-thumbnail">
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label for="images">Gambar Tambahan</label>
                                                <input type="file" class="form-control" id="images" name="images[]" multiple
                                                    accept=".png,.jpg,.jpeg,image/png,image/jpg,image/jpeg">
                                                @if($article->media)
                                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                                        @foreach($article->media->where('type', 'gallery') as $media)
                                                            <img src="{{ asset('images/' . $media->images) }}" width="100" class="img-thumbnail">
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label for="published_at">Tanggal Publish</label>
                                                <input type="date" class="form-control" id="published_at" name="published_at"
                                                    value="{{ optional($article->published_at)->format('Y-m-d') }}">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Artikel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select class="form-control" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Konten</label>
                        <textarea name="content" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail" accept=".png,.jpg,.jpeg,image/png,image/jpg,image/jpeg">
                    </div>
                    <div class="mb-3">
                        <label>Gambar Tambahan</label>
                        <input type="file" class="form-control" name="images[]" multiple accept=".png,.jpg,.jpeg,image/png,image/jpg,image/jpeg">
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Publish</label>
                        <input type="date" class="form-control" name="published_at">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#articles-table').DataTable();
    });
</script>
@endpush
