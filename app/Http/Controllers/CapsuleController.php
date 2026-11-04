<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCapsuleRequest;
use App\Services\CapsuleService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CapsuleController extends Controller
{
    public function __construct(private CapsuleService $capsules) {}

    public function create(): View
    {
        return view('capsules.create', ['minimum' => CarbonImmutable::now('Asia/Jakarta')->addMinute()->format('Y-m-d\TH:i')]);
    }

    public function store(StoreCapsuleRequest $request): RedirectResponse
    {
        $token = $this->capsules->store($request->validated());

        return to_route('capsules.saved', $token);
    }

    public function saved(string $token): View
    {
        $capsule = $this->capsules->find($token);

        return view('capsules.saved', ['title' => $capsule->title, 'opensLabel' => $this->capsules->label($capsule->opens_at), 'url' => route('capsules.show', $token)]);
    }

    public function show(string $token): View
    {
        return view('capsules.show', ['capsule' => $this->capsules->display($this->capsules->find($token)), 'statusUrl' => route('capsules.status', $token)]);
    }

    public function status(string $token): JsonResponse
    {
        return response()->json($this->capsules->clock($this->capsules->find($token)));
    }
}
