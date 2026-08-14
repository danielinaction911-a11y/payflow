<?php

namespace Tests\Unit;

use App\Models\WithdrawGateway;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WithdrawGatewayUploadTest extends TestCase
{
    public function test_withdraw_gateway_logo_uploads_and_returns_relative_path(): void
    {
        $file = UploadedFile::fake()->create('logo.png', 50, 'image/png');
        $prefix = 'test_gateway_' . time();

        $handler = new class {
            use \App\Traits\HandlesFileUploads;
        };

        $path = $handler->uploadFile($file, 'images/gateway', null, $prefix);

        $this->assertStringStartsWith('images/gateway/', $path);
        $this->assertFileExists(public_path($path));

        @unlink(public_path($path));
    }
}
