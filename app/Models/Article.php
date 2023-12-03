<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'author', 'url', 'url_to_image', 'source_id', 'category_id', 'content', 'published_at'];
    
    public function source() {
        return $this->belongsTo(Source::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilterByDate($query, $date) {
        return $query->whereDate('published_at', $date);
    }

    public function scopeFilterByCategory($query, $category) {
        return $query->where('category_id', $category);
    }
    
    public function scopeFilterBySource($query, $sourceName)
    {
        return $query->whereHas('source', function ($query) use ($sourceName) {
            $query->where('name', 'like', '%' . $sourceName . '%');
        });
    }

    public function scopeFilterByCategoryPreferrence($query, $preferred_categories) {
        return $query->whereIn('category_id', $preferred_categories);
    }

    public function scopeFilterBySourcePreferrence($query, $preferred_sources)
    {
        return $query->whereIn('source_id', $preferred_sources);
    }

    public function scopeFilterByAuthorPreference($query, $preferred_authors)
    {
        $query->where(function ($q) use ($preferred_authors) {
            foreach ($preferred_authors as $author) {
                $q->orWhere('author', 'like', '%' . $author . '%');
            }
        });
    }


    public function scopeFilterBySearchQuery($query, $q) {
        return $query->where('title', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%');
    }
}
