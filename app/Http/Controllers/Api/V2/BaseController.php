<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Http\Controllers\Api\V2;

use Mews\Captcha\Captcha;
use App\Constant\CacheConstant;
use App\Constant\FrontendConstant;
use App\Exceptions\ApiV2Exception;
use Illuminate\Support\Facades\Auth;
use App\Services\Base\Services\CacheService;
use App\Services\Member\Services\UserService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Api\V2\Traits\ResponseTrait;
use App\Services\Base\Interfaces\CacheServiceInterface;
use App\Services\Member\Interfaces\UserServiceInterface;

/**
 * @OpenApi\Annotations\Swagger(
 *     @OA\Info(
 *         title="MeEdu API V2",
 *         version="2.0"
 *     ),
 *     @OA\Server(
 *         url="http://127.0.0.1:8000/api/v2",
 *         description="local"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="BasicAuth",
 *     in="header",
 *     type="http",
 *     scheme="scheme",
 *     bearerFormat="bearer",
 *     name="bearer"
 * )
 * @OA\Tag(
 *     name="课程",
 *     description="课程相关接口",
 * ),
 * @OA\Tag(
 *     name="视频",
 *     description="视频相关接口",
 * ),
 * @OA\Tag(
 *     name="用户",
 *     description="用户相关接口",
 * ),
 * @OA\Tag(
 *     name="Auth",
 *     description="登录/注册相关接口",
 * ),
 * @OA\Tag(
 *     name="其它",
 *     description="其它接口",
 * ),
 */

/**
 * Class BaseController
 * @package App\Http\Controllers\Api\V2
 */
class BaseController
{
    use ResponseTrait;

    protected $guard = FrontendConstant::API_GUARD;

    protected $user;

    /**
     * 图形验证码校验
     * @throws ApiV2Exception
     */
    protected function checkImageCaptcha()
    {
        $imageKey = request()->input('image_key');
        if (!$imageKey) {
            throw new ApiV2Exception(__('图形验证码错误'));
        }
        $imageCaptcha = request()->input('image_captcha', '');
        if (!app()->make(Captcha::class)->check_api($imageCaptcha, $imageKey)) {
            throw new ApiV2Exception(__('图形验证码错误'));
        }
    }

    /**
     * 检测是否登录
     *
     * @return boolean
     */
    protected function check(): bool
    {
        return Auth::guard($this->guard)->check();
    }

    /**
     * @return mixed
     */
    protected function id()
    {
        return Auth::guard($this->guard)->id();
    }

    protected function user()
    {
        if (!$this->user) {
            /**
             * @var $userService UserService
             */
            $userService = app()->make(UserServiceInterface::class);
            $this->user = $userService->find($this->id(), ['role']);
        }
        return $this->user;
    }

    /**
     * @param $list
     * @param $total
     * @param $page
     * @param $pageSize
     *
     * @return LengthAwarePaginator
     */
    protected function paginator($list, $total, $page, $pageSize)
    {
        return new LengthAwarePaginator($list, $total, $pageSize, $page, ['path' => request()->path()]);
    }

    /**
     * @throws ApiV2Exception
     */
    protected function mobileCodeCheck()
    {
        $mobile = request()->input('mobile');
        $mobileCode = request()->input('mobile_code');
        if (!$mobileCode) {
            throw new ApiV2Exception(__('短信验证码错误'));
        }

        /**
         * @var $cacheService CacheService
         */
        $cacheService = app()->make(CacheServiceInterface::class);
        $key = get_cache_key(CacheConstant::MOBILE_CODE['name'], $mobile);
        $code = $cacheService->get($key);
        if (!$code || $code !== $mobileCode) {
            throw new ApiV2Exception(__('短信验证码错误'));
        }
        $cacheService->forget($key);
    }
}
