<?php
declare(strict_types=1);

namespace App\Services\User;

use Embed\Embed;

final class GetOGP
{
    public function __construct()
    {
        $this->embed = new Embed();
    }

    /**
     * URLからOGPを取得
     *
     * @return array<int, array{
     * title: string,
     * description: string,
     * image: string,
     * url: url,
     * }> | null
     */
    public function OGPInfo(string $url)
    {
	    if(str_starts_with($url, 'http://') | str_starts_with($url, 'https://')) {
        $info = $this->embed->get($url);
        $metas = $info->getMetas();

        $title = $metas->get('og:title');
        $description = $metas->get('og:description');
        $image = $metas->get('og:image');
        $url = $metas->get('og:url');
        $data = [
        'title' => $title[0],
        'description' => $description[0],
        'image' => $image[0],
        'url' => $url[0],
        ];
        return $data;
	    }
	    return null;
    }
}
