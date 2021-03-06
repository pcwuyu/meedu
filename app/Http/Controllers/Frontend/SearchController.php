<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Frontend\SearchRequest;
use App\Services\Course\Services\CourseService;
use App\Services\Course\Interfaces\CourseServiceInterface;

class SearchController extends BaseController
{
    /**
     * @var CourseService
     */
    protected $courseService;

    public function __construct(CourseServiceInterface $courseService)
    {
        parent::__construct();

        $this->courseService = $courseService;
    }

    public function searchHandler(SearchRequest $request)
    {
        ['keywords' => $keywords] = $request->filldata();
        $courses = [];
        $keywords && $courses = $this->courseService->titleSearch($keywords, 20);

        $title = __('搜索');

        return v('frontend.search.index', compact('courses', 'title'));
    }
}
