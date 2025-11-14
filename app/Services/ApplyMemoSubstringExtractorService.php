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

namespace App\Services;

/**
 * ApplyMemoSubstringExtractorService
 *
 * @package App\Services
 */
class ApplyMemoSubstringExtractorService
{
    /** @var int $numberOfExtractedCharacters 抽出文字数 */
    private int $numberOfExtractedCharacters;

    /**
     * __construct
     *
     * @param int $numberOfExtractedCharacters
     */
    public function __construct(int $numberOfExtractedCharacters = 24)
    {
        $this->numberOfExtractedCharacters = $numberOfExtractedCharacters;
    }

    /**
     * __invoke
     *
     * @param string|null $memo
     * @return string
     * @author m.shomura <m.shomura@balocco.info>
     */
    public function __invoke(string $memo = null): string
    {
        if (is_null($memo)) {
            return '';
        }

        $memos = [];
        $memoPerLine = explode(PHP_EOL, $memo);

        // 先頭二行の先頭文字取得
        for ($i = 0; $i < 2; $i++) {
            if (!isset($memoPerLine[$i])) {
                continue;
            }
            $memos[] = $this->extractFirstFewCharacters($memoPerLine[$i]);
        }
        return implode(PHP_EOL, $memos);
    }

    /**
     * extractFirstFewCharacters
     * 指定の文字数で文字を切り出す
     *
     * @return string
     */
    private function extractFirstFewCharacters(string $memo): string
    {
        // UTF8だと全角文字が2～6バイトなのでSJISに変換して計算
        $memoSJIS = mb_convert_encoding($memo, 'SJIS', 'UTF-8');
        return mb_convert_encoding(
            mb_strcut($memoSJIS, 0, $this->numberOfExtractedCharacters, 'SJIS'),
            'UTF-8',
            'SJIS'
        );
    }
}
