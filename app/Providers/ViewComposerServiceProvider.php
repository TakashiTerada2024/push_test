<?php

/**
 * Balocco Inc.
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 株式会社バロッコはシステム設計・開発会社として、
 * 社員・顧客企業・パートナー企業と共に企業価値向上に全力を尽くします
 *
 * 1. プロフェッショナル集団として人間力・経験・知識を培う
 * 2. システム設計・開発を通じて、顧客企業の成長を活性化する
 * 3. 顧客企業・パートナー企業・弊社全てが社会的意義のある事業を営み、全てがwin-winとなるビジネスをする
 * ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～
 * 本社所在地
 * 〒101-0032　東京都千代田区岩本町2-9-9 TSビル4F
 * TEL:03-6240-9877
 *
 * 大阪営業所
 * 〒530-0063　大阪府大阪市北区太融寺町2-17 太融寺ビル9F 902
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace App\Providers;

use App\Http\View\Composers\Contents\Apply\Detail\OverviewShowComposer;
use App\Http\View\Composers\Contents\Apply\Detail\Section01Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section02Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section03Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section04Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section05Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section06Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section07Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section08Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section09Composer;
use App\Http\View\Composers\Contents\Apply\Detail\Section10Composer;
use App\Http\View\Composers\Contents\Apply\Lists\AcceptedComposer;
use App\Http\View\Composers\Contents\Apply\Detail\BasicInfoComposer;
use App\Http\View\Composers\Contents\Apply\Lists\CreatingLinkageComposer;
use App\Http\View\Composers\Contents\Apply\Lists\CreatingStatisticsComposer;
use App\Http\View\Composers\Contents\Apply\Lists\MyListComposer;
use App\Http\View\Composers\Contents\Apply\Lists\PriorConsultationComposer;
use App\Http\View\Composers\Contents\Apply\Lists\SubmittingComposer;
use App\Http\View\Composers\Contents\Apply\Start\DisplayComposer;
use App\Http\View\Composers\Contents\Apply\Lock\ManagementComposer;
use App\Http\View\Composers\AuthenticatedUserComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * ViewComposerServiceProvider
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) このクラスの責務が単一であるので問題ないと判断した。
 * @package App\Providers
 */
class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * boot
     *
     * @author kenji yamamoto <k.yamamoto@balocco.info>
     * @SuppressWarnings(PHPMD.StaticAccess) Viewファサード利用OK
     */
    public function boot()
    {
        View::composer('navigation-menu', AuthenticatedUserComposer::class);
        View::composer('contents.apply.detail.*.overview', OverviewShowComposer::class);
        View::composer('contents.apply.detail.*.section01', Section01Composer::class);
        View::composer('contents.apply.detail.*.section02', Section02Composer::class);
        View::composer('contents.apply.detail.*.section03', Section03Composer::class);
        View::composer('contents.apply.detail.*.section04', Section04Composer::class);
        View::composer('contents.apply.detail.*.section05', Section05Composer::class);
        View::composer('contents.apply.detail.*.section06', Section06Composer::class);
        View::composer('contents.apply.detail.*.section07', Section07Composer::class);
        View::composer('contents.apply.detail.*.section08', Section08Composer::class);
        View::composer('contents.apply.detail.*.section09', Section09Composer::class);
        View::composer('contents.apply.detail.*.section10', Section10Composer::class);
        View::composer('contents.apply.detail.*.basic_info', BasicInfoComposer::class);
        View::composer('contents.apply.lists.my_list', MyListComposer::class);
        View::composer('contents.apply.lists.prior-consultation', PriorConsultationComposer::class);
        View::composer('contents.apply.lists.creating-linkage', CreatingLinkageComposer::class);
        View::composer('contents.apply.lists.creating-statistics', CreatingStatisticsComposer::class);
        View::composer('contents.apply.lists.submitting', SubmittingComposer::class);
        View::composer('contents.apply.lists.accepted', AcceptedComposer::class);
        View::composer('contents.apply.start', DisplayComposer::class);
        View::composer('contents.apply.lock.management', ManagementComposer::class);
    }
}
