<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Spatie\ImageOptimizer\OptimizerChainFactory;


class ImageCompressionService
{
    public function compressImage(string $path): void
    {
        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->optimize($path);
    }
}