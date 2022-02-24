<?php
declare(strict_types=1);

namespace App\Services\User;
use App\Models\User;

class GetSection
{
    public function __construct(int $id)
    {
        $this->user = User::find($id);
    }

    /**
     * プロフィールのすべてのセクション名の取得
     */
    public function index(): array
    {
	    $data = [];
	    $sections = $this->user->sections;
	    foreach($sections as $section) {
		    $data[] = $section->name;
	    }
	    return $data;
    }
}
