<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

final class SignUp
{
    public function __construct()
    {
        $this->user = new User();
        $this->profile = new Profile();
    }

    /**
     * 新規登録の値が正常である場合は登録したユーザーのIDを、正常でなければ何もせずに0を返す
     */
    public function record(string $name, string $email, string $password): int
    {
        $isNew = $this->isNew($email);

        if($isNew) {
            try {
                DB::beginTransaction();
                // 新規ユーザーの作成
                $this->user->create(
                    [
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password)
                    ]
                );

                // 新規ユーザー用のプロフィールの作成
                $id = $this->user->where('email', $email)->value('id');
                $this->profile->create(
                    [
                    'user_id' => $id,
                    ]
                );
                DB::commit();
                return $id;
            } catch(\Exception $e) {
                DB::rollBack();
                return 0;
            }
        }
        return 0;
    }

    private function isNew(string $email): bool
    {
        return !$this->user->where('email', $email)->exists();
    }
}
?>
