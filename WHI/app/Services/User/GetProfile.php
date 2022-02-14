<?php
declare(strict_types=1);

namespace App\Services\User;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;

final class GetProfile
{
    public function __construct()
    {
        $this->user = new User();
        $this->profile = new Profile();
    }

    public function getInfo(int $id): array
    {
    }
}
