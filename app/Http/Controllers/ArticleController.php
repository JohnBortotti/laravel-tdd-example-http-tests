<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticleStoreFormRequest;
use App\Http\Requests\ArticleUpdateFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{
    public function articles() {
        try {
            return ArticleResource::collection(Article::all());
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function article ($id) {
        try {
            $article = Article::findOrFail($id);

            return new ArticleResource($article);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'article not found'
            ], 404);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(ArticleStoreFormRequest $request) {
        try {
            $article = Article::create($request->all());

            return new ArticleResource($article);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(ArticleUpdateFormRequest $request, $id) {
        try {
            $article = Article::findOrFail($id);
            $article->update($request->all());

            return response($article, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'article not found'
            ], 404);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id) {
        try {
            $article = Article::findOrFail($id);

            return Article::destroy($article);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'article not found'
            ], 404);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}
