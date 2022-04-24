<?php

namespace App\Repositories\News\Concerns;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;

interface NewsRepositoryInterface
{
    public function getAllNews(): ?Collection;
    public function createNews(array $payload);
    public function updateNews(array $payload, News $news);
    public function delete(News $news);
}
