<?php

namespace App\Services;

use App\Models\Capsule;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

class CapsuleService
{
    public function store(array $input): string
    {
        $token = Str::random(64);
        Capsule::create(['title' => $input['title'], 'body' => $input['body'], 'token_hash' => hash('sha256', $token),
            'opens_at' => CarbonImmutable::createFromFormat('!Y-m-d\TH:i', $input['opens_at'], 'Asia/Jakarta')->utc()]);

        return $token;
    }

    public function find(string $token): Capsule
    {
        return Capsule::select(['id', 'title', 'opens_at', 'created_at'])->where('token_hash', hash('sha256', $token))->firstOrFail();
    }

    public function clock(Capsule $capsule): array
    {
        $now = CarbonImmutable::now('UTC');

        return ['serverNow' => $now->getTimestampMs(), 'opensAt' => $capsule->opens_at->getTimestampMs(), 'unlocked' => $now->greaterThanOrEqualTo($capsule->opens_at)];
    }

    public function display(Capsule $capsule): array
    {
        $clock = $this->clock($capsule);
        $data = [...$clock, 'title' => $capsule->title, 'opensLabel' => $this->label($capsule->opens_at), 'createdLabel' => $this->label(CarbonImmutable::instance($capsule->created_at))];
        if ($clock['unlocked']) {
            $data['body'] = Capsule::findOrFail($capsule->id)->body;
        }

        return $data;
    }

    public function label(CarbonImmutable $time): string
    {
        return $time->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H.i').' WIB';
    }
}
