<?php

namespace Database\Factories;

use App\Models\AttachmentLock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ncc01\Apply\Enterprise\Classification\AttachmentTypes;
use RuntimeException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttachmentLock>
 */
class AttachmentLockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttachmentLock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attachmentTypes = new AttachmentTypes();
        $typeIds = $attachmentTypes->listOfId()->all();

        return [
            'apply_id' => \App\Models\Apply::factory(),
            'attachment_type_id' => $this->faker->randomElement($typeIds),
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
     * 添付ファイル種別を指定
     */
    public function forAttachmentType(int $attachmentTypeId): self
    {
        return $this->state(function (array $attributes) use ($attachmentTypeId) {
            return [
                'attachment_type_id' => $attachmentTypeId,
            ];
        });
    }

    /**
     * 指定された申請IDに対して、すべての添付ファイル種別のロックデータを作成
     *
     * @param int $applyId 申請ID
     * @param int $lastUpdatedBy 最終更新者ID
     * @param mixed $isLocked ロック状態（デフォルトはfalse）数値の場合、trueのレコード数。
     * @return array 作成されたAttachmentLockモデルの配列
     */
    public function createForAllAttachments(int $applyId, int $lastUpdatedBy, mixed $isLocked = false): array
    {
        $attachmentTypes = new AttachmentTypes();
        $typeIds = $attachmentTypes->listOfId()->all();
        $result = [];

        if (is_bool($isLocked)) {
            foreach ($typeIds as $typeId) {
                $result[] = $this->state([
                    'apply_id' => $applyId,
                    'attachment_type_id' => $typeId,
                    'is_locked' => $isLocked,
                    'last_updated_by' => $lastUpdatedBy,
                ])->create();
            }
        } elseif (is_int($isLocked)) {
            $trueCount = min($isLocked, count($typeIds));
            $index = 0;
            foreach ($typeIds as $typeId) {
                $result[] = $this->state([
                    'apply_id' => $applyId,
                    'attachment_type_id' => $typeId,
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
