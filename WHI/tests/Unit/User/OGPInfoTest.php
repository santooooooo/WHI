<?php
declare(strict_types=1);

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Services\User\GetOGP;

class OGPInfoTest extends TestCase
{
    /**
     * URLからOGPを取得できているかのテスト
     *
     * @test
     * @return void
     */
    public function OGPInfo()
    {
        $url = 'https://qiita.com/';
        $domain = new GetOGP();
        $result = $domain->OGPInfo($url);

        $trueResult = [
        'title' => 'エンジニアに関する知識を記録・共有するためのサービス - Qiita',
        'description' => 'Qiitaは、エンジニアに関する知識を記録・共有するためのサービスです。 プログラミングに関するTips、ノウハウ、メモを簡単に記録 &amp; 公開することができます。',
        'image' => 'https://cdn.qiita.com/assets/qiita-ogp-3b6fcfdd74755a85107071ffc3155898.png',
        'url' => 'https://qiita.com/'
        ];
        $this->assertSame($result, $trueResult);
    }
}
