<?php

namespace Database\Factories;

use App\Models\ScreenLock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ncc01\Apply\Enterprise\Classification\ScreenLocks;
use RuntimeException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScreenLock>
 */
class ScreenLockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScreenLock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $screenLocks = new ScreenLocks();
        $codes = [
            'basic',
            'section1',
            'section2',
            'section3',
            'section4',
            'section5',
            'section6',
            'section7',
            'section8',
            'section9',
            'section10',
            'attachment'
        ];

        return [
            'apply_id' => \App\Models\Apply::factory(),
            'screen_code' => $this->faker->randomElement($codes),
            'is_locked' => $this->faker->boolean,
            'last_updated_by' => User::factory(),
        ];
    }

    /**
     * ロック状態を指定
     */
    public function locked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_locked' => true,
            ];
        });
    }

    /**
     * アンロック状態を指定
     */
    public function unlocked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_locked' => false,
            ];
        });
    }

    /**
     * 画面コードを指定
     */
    public function forScreen(string $screenCode): self
    {
        return $this->state(function (array $attributes) use ($screenCode) {
            return [
                'screen_code' => $screenCode,
            ];
        });
    }

    /**
     * 指定された申請IDに対して、すべての画面のロックデータを作成
     *
     * @param int $applyId 申請ID
     * @param int $lastUpdatedBy 最終更新者ID
     * @param mixed $isLocked ロック状態（デフォルトはfalse）
     * @return array 作成されたScreenLockモデルの配列
     */
    public function createForAllScreens(int $applyId, int $lastUpdatedBy, mixed $isLocked = false): array
    {

        $screenLocks = new ScreenLocks();

        $screenCodes = $screenLocks->all();
        $result = [];

        if (is_bool($isLocked)) {
            foreach ($screenCodes as $screenCode) {
                $result[] = $this->state([
                    'apply_id' => $applyId,
                    'screen_code' => $screenCode,
                    'is_locked' => $isLocked,
                    'last_updated_by' => $lastUpdatedBy,
                ])->create();
            }
        } elseif (is_int($isLocked)) {
            $trueCount = min($isLocked, count($screenCodes));
            $index = 0;
            foreach ($screenCodes as $screenCode) {
                $result[] = $this->state([
                    'apply_id' => $applyId,
                    'screen_code' => $screenCode,
                    //引数で指定された数だけtrueのレコードを作る
                    'is_locked' => $index < $trueCount,
                    'last_updated_by' => $lastUpdatedBy,
                ])->create();
                $index++;
            }
        } else {
            throw new RuntimeException("isLocked must be a boolean or an integer");
        }
        return $result;
    }
}
