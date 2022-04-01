<?php

namespace App\Services\Auth;

use App\Models\ResetPassword;
use Carbon\Carbon;

class CheckResetPasswordAuth
{
    public function __construct()
    {
        $this->resetPassword = new ResetPassword();
    }

    /**
     * パスワード再設定ページ用の認証情報の確認
     *
     * @return string | null
     */
    public function check(string $identification)
    {
        $record = $this->resetPassword->where('identification', $identification)->first();
        if(!is_null($record)) {
            $limit = new Carbon('-10 minutes');
            $created = new Carbon($record->created_at);
            $isValid = $created->timestamp > $limit->timestamp;
            if($isValid) {
                return $record->email;
            }
        }
        return null;
    }
}
