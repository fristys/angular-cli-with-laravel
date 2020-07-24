<?php

namespace App\Services;

use Exception;

class NgBuildService
{
    public $assets = array();

    public function __construct()
    {
        if (config('app.env') === 'production') {
            $this->extractAndCache();
        }
    }

    /**
     * Extracts all bundle assets from public/build/stats.json
     * in the format of
     * {
     *  "assetFileName": "assetHasheFileName"
     * }
     */
    private function extractAndCache()
    {
        $path = public_path('build') . '/stats.json';

        try {
            $json = json_decode(file_get_contents($path), true);

            if (isset($json['assets']) && count($json['assets'])) {
                foreach ($json['assets'] as $asset) {
                    $name = $asset['name'];

                    if ($asset['chunkNames'] && count($asset['chunkNames'])) {
                        $this->assets[$asset['chunkNames'][0]] = $name;
                    } else {
                        $this->assets[$name] = $name;
                    }
                }
            }
        } catch (Exception $e) {
        }
    }
}
