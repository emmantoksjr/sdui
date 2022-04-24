<?php

namespace App\Repositories\News;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\News\Concerns\NewsRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface
{
    public function getAllNews(): ?Collection
    {
        return News::all();
    }

    public function createNews(array $payload)
    {
        return News::create($payload);
    }

    public function updateNews(array $payload, News $news)
    {
        return $news->update($payload);
    }

    public function delete(News $news)
    {
        return $news->delete();
    }

}
