<?php

namespace AdventOfCode\Common\WolframAlpha;

use WolframAlpha\Engine;

class EngineFactory
{
    public static function create(): ?Engine
    {
        $ini = parse_ini_file(__DIR__.'/../../../config/app.ini');
        if ($id = $ini['wolfram_alpha_app_id'] ?? null) {
            return new Engine($id);
        }
        return null;
    }
}