<?php

namespace App\Http\Controllers;

use App\Events\NewsCreated;
use App\Models\News;
use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Repositories\News\Concerns\NewsRepositoryInterface;

class NewsController extends Controller
{
    private NewsRepositoryInterface $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_news = $this->newsRepository->getAllNews();

       return $this->jsonResponse(HTTP_SUCCESS, "News Retrieved Successfully.", $all_news);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\News\StoreNewsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(StoreNewsRequest $request)
    {
        $news = $this->newsRepository->createNews($request->validated());

        NewsCreated::dispatch($news);

        return $this->jsonResponse(HTTP_CREATED, "News Created Successfully", $news);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return $this->jsonResponse(HTTP_SUCCESS, "News Fetched Successfully", $news);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\News\UpdateNewsRequest  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $this->newsRepository->updateNews($request->validated(), $news);

        return $this->jsonResponse(HTTP_SUCCESS, "News Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $this->newsRepository->delete($news);

        return $this->jsonResponse(HTTP_SUCCESS, "News Deleted Successfully");
    }
}
