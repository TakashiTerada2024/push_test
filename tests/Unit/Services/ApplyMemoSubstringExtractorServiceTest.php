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
 * 〒104-0061　東京都中央区銀座1丁目12番4号 N&E BLD.7階
 * TEL: 03-4570-3121
 *
 * 大阪営業所
 * 〒540-0026　大阪市中央区内本町1-1-10 五苑第二ビル901
 *
 * https://www.balocco.info/
 * © Balocco Inc. All Rights Reserved.
 */

namespace Tests\Unit\Gateway;

use Illuminate\Support\Facades\App;
use App\Services\ApplyMemoSubstringExtractorService;
use Tests\TestCase;

/**
 * ApplyMemoSubstringExtractorServiceTest
 *
 * @package Tests\Unit\Services
 * @coversDefaultClass \App\Services\ApplyMemoSubstringExtractorService
 */
class ApplyMemoSubstringExtractorServiceTest extends TestCase
{
    /**
     * test___invoke
     * @covers ::__invoke
     * @covers ::extractFirstFewCharacters
     * @dataProvider dataProvider
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function test___invoke(string $memoText = null, string $expect)
    {
        $targetClass = App::make(ApplyMemoSubstringExtractorService::class);
        $this->assertEquals($expect, $targetClass->__invoke($memoText));
    }

    /**
     * dataProvider
     *
     * @return array[]
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function dataProvider(): array
    {
        $memoText = <<<EOT
        %s
        %s
        EOT;

        $memoText3Lines = <<<EOT
        %s
        %s
        %s
        EOT;

        $expectText = <<<EOT
        %s
        %s
        EOT;

        return [
            '全角文字12文字' => ['あいうえおかきくけこさし', 'あいうえおかきくけこさし'],
            '全角文字13文字' => ['たちつてとなにぬねのはひふ', 'たちつてとなにぬねのはひ'],
            '半角文字24文字' => ['abcdefghijklmnopqrstuvwx', 'abcdefghijklmnopqrstuvwx'],
            '半角文字25文字' => ['1234567890123456789012345', '123456789012345678901234'],
            '全角半角混在24バイト' => ['あいうえおabcdefghij一二', 'あいうえおabcdefghij一二'],
            '全角半角混在25バイト' => ['一二三四五1234567890あいう', '一二三四五1234567890あい'],
            '0文字' => ['', ''],
            'null' => [null, ''],
            '全角文字12文字_2行' => [
                sprintf($memoText, 'あいうえおかきくけこさし', 'はひふへほまみむめもやゆ'),
                sprintf($expectText, 'あいうえおかきくけこさし', 'はひふへほまみむめもやゆ')
            ],
            '半角文字25文字_2行' => [
                sprintf($memoText, 'abcdefghijklmnopqrst12345', '25characters'),
                sprintf($memoText, 'abcdefghijklmnopqrst1234', '25characters')
            ],
            '2行目が空欄' => [
                sprintf($memoText, '2行目空欄テスト', ''),
                sprintf($expectText, '2行目空欄テスト', '')
            ],
            '1行目が空欄' => [
                sprintf($memoText, '', '1行目空欄テスト'),
                sprintf($expectText, '', '1行目空欄テスト')
            ],
            '全角文字12文字_3行' => [
                sprintf($memoText3Lines, '一二三四五六七八九十一二', 'あああああいいいいいうう', 'しさこけくきかおえういあ'),
                sprintf($expectText, '一二三四五六七八九十一二', 'あああああいいいいいうう')
            ],
        ];
    }
}
