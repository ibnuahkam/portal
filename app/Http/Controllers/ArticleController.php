<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'media'])->latest()->get();
        $categories = Category::all();
        return view('article.index', compact('articles', 'categories'));
    }

    public function store(Request $request)
    {
        \Log::info('=== START STORE METHOD ===');
        \Log::info('Incoming fields: ' . json_encode($request->except(['_token', 'thumbnail', 'images'])));

        try {
            // Log MIME types untuk debug
            if ($request->hasFile('thumbnail')) {
                \Log::info('Thumbnail MIME: ' . $request->file('thumbnail')->getMimeType());
            }
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $file) {
                    \Log::info("Image $i MIME: " . $file->getMimeType());
                }
            }

        $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'category_id' => 'required|exists:categories,id',
                'thumbnail' => 'nullable|mimetypes:image/jpeg,image/png,image/jpg|max:5120',
                'images' => 'nullable|array',
                'images.*' => 'nullable|mimetypes:image/jpeg,image/png,image/jpg|max:5120',
            ], [
                'thumbnail.mimetypes' => 'Thumbnail harus berupa gambar jpeg/jpg/png',
                'images.*.mimetypes' => 'Gambar tambahan hanya boleh berekstensi jpeg/jpg/png',
            ]);

            \Log::info('Validated data: ' . json_encode($validated));

            $imagePath = public_path('images');
            if (!File::exists($imagePath)) {
                File::makeDirectory($imagePath, 0755, true);
            }

            // Simpan article tanpa media dulu
            $data = [
                'title' => $validated['title'],
                'content' => $validated['content'],
                'category_id' => $validated['category_id'],
                'published_at' => $validated['published_at'] ?? null,
                'user_id' => Auth::id(),
            ];

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = 'thumb_' . time() . '_' . $thumbnail->getClientOriginalName();
                $thumbnail->move($imagePath, $thumbnailName);
                $data['thumbnail'] = 'images/' . $thumbnailName;

                \Log::info('Thumbnail saved: ' . $thumbnailName);
            }

            $article = Article::create($data);
            \Log::info('Article created with ID: ' . $article->id);

            // Gambar tambahan masuk ke tabel media
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    if ($image && $image->isValid()) {
                        $imageName = 'img_' . time() . '_' . $index . '_' . $image->getClientOriginalName();
                        $image->move($imagePath, $imageName);

                        Media::create([
                            'article_id' => $article->id,
                            'type' => 'gallery',
                            'images' => $imageName,
                        ]);

                        \Log::info("Gallery image saved: $imageName");
                    }
                }
            }

            return redirect()->route('articles.index')->with('success', 'Artikel berhasil ditambahkan!');
        }

        // Tangkap error validasi
        catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('VALIDATION FAILED:', $e->errors());
            return back()->withInput()->withErrors($e->errors());
        }

        // Tangkap error lain
        catch (\Exception $e) {
            \Log::error('STORE ERROR: ' . $e->getMessage());
            return back()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Article $article)
    {
        \Log::info('=== START UPDATE METHOD ===');
        \Log::info('Incoming update fields: ' . json_encode($request->except(['_token', '_method'])));

        if ($request->hasFile('thumbnail')) {
            \Log::info('Update Thumbnail MIME: ' . $request->file('thumbnail')->getMimeType());
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                \Log::info("Update Image $i MIME: " . $img->getMimeType());
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => ['nullable', 'file', function ($attribute, $value, $fail) use ($request) {
                if ($request->hasFile('thumbnail') && !$request->file('thumbnail')->isValid()) {
                    $fail('Thumbnail tidak valid.');
                }
            }, 'mimes:jpeg,jpg,png', 'max:5120'],
            'images' => 'nullable|array',
            'images.*' => ['nullable', 'file', function ($attribute, $value, $fail) {
                if ($value instanceof \Illuminate\Http\UploadedFile && !$value->isValid()) {
                    $fail('Setiap gambar tambahan harus berupa file gambar yang valid.');
                }
            }, 'mimes:jpeg,jpg,png', 'max:5120'],
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only('title', 'content', 'category_id', 'published_at');

        $imagePath = public_path('images');
        if (!File::exists($imagePath)) {
            File::makeDirectory($imagePath, 0755, true);
        }

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail && file_exists(public_path($article->thumbnail))) {
                unlink(public_path($article->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = 'thumb_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move($imagePath, $filename);
            $data['thumbnail'] = 'images/' . $filename;

            Media::updateOrCreate(
                ['article_id' => $article->id, 'type' => 'thumbnail'],
                ['images' => $filename]
            );

            \Log::info("Thumbnail updated: $filename");
        }
        if ($request->hasFile('images')) {
            $oldGallery = Media::where('article_id', $article->id)->where('type', 'gallery')->get();
            foreach ($oldGallery as $media) {
                $oldPath = public_path('images/' . $media->images);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $media->delete();
            }
            foreach ($request->file('images') as $index => $img) {
                if ($img->isValid()) {
                    $imgName = 'img_' . uniqid() . '_' . $img->getClientOriginalName();
                    $img->move($imagePath, $imgName);

                    Media::create([
                        'article_id' => $article->id,
                        'type' => 'gallery',
                        'images' => $imgName,
                    ]);

                    \Log::info("Gallery image replaced with: $imgName");
                }
            }
        }


        $article->update($data);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        if ($article->thumbnail && file_exists(public_path($article->thumbnail))) {
            unlink(public_path($article->thumbnail));
        }

        foreach ($article->media as $media) {
            $filePath = public_path('images/' . $media->images);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $media->delete();
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

}