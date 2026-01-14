<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        // ================= VALIDATION =================
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required',
            'category_id'  => 'required|exists:categories,id',
            'thumbnail'    => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg|max:2048',
            'images'       => 'nullable|array|max:5',
            'images.*'     => 'file|mimetypes:image/jpeg,image/png,image/jpg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        // ================= CREATE ARTICLE =================
        $article = Article::create([
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'category_id'  => $validated['category_id'],
            'published_at' => $validated['published_at'] ?? null,
            'user_id'      => Auth::id(),
        ]);

        // ================= THUMBNAIL =================
        if ($request->hasFile('thumbnail')) {
            $thumbPath = $request->file('thumbnail')
                ->store('articles/thumbnails', 'public');

            $article->update([
                'thumbnail' => $thumbPath,
            ]);
        }

        // ================= GALLERY =================
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('articles/gallery', 'public');

                Media::create([
                    'article_id' => $article->id,
                    'type'       => 'gallery',
                    'images'     => $path,
                ]);
            }
        }

        return redirect()
            ->route('articles.index')
            ->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function update(Request $request, Article $article)
    {
        // ================= VALIDATION =================
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'content'      => 'required',
            'category_id'  => 'required|exists:categories,id',
            'thumbnail'    => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg|max:2048',
            'images'       => 'nullable|array|max:5',
            'images.*'     => 'file|mimetypes:image/jpeg,image/png,image/jpg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        // ================= UPDATE ARTICLE =================
        $article->update([
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'category_id'  => $validated['category_id'],
            'published_at' => $validated['published_at'] ?? null,
        ]);

        // ================= UPDATE THUMBNAIL =================
        if ($request->hasFile('thumbnail')) {


            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            $thumbPath = $request->file('thumbnail')
                ->store('articles/thumbnails', 'public');

            $article->update([
                'thumbnail' => $thumbPath,
            ]);
        }

        // ================= UPDATE GALLERY (REPLACE) =================
        if ($request->hasFile('images')) {

            $oldGallery = Media::where('article_id', $article->id)
                ->where('type', 'gallery')
                ->get();

            foreach ($oldGallery as $media) {
                Storage::disk('public')->delete($media->images);
                $media->delete();
            }

            foreach ($request->file('images') as $image) {
                $path = $image->store('articles/gallery', 'public');

                Media::create([
                    'article_id' => $article->id,
                    'type'       => 'gallery',
                    'images'     => $path,
                ]);
            }
        }

        return redirect()
            ->route('articles.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Article $article)
    {
        if ($article->thumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        foreach ($article->media as $media) {
            Storage::disk('public')->delete($media->images);
            $media->delete();
        }

        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }
}
