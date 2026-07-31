<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Site\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

final class SiteSettingsController extends Controller
{
    private const string KEY_HERO = 'home.hero';

    private const string KEY_INTRO = 'home.intro';

    private const string KEY_SECTION_TITLES = 'home.section_titles';

    private const string KEY_FEATURES = 'home.features';

    private const string KEY_TRANSPORT_CTA = 'home.transport_cta';

    private const string KEY_BRAND = 'footer.brand';

    private const string KEY_CONTACT = 'footer.contact';

    /**
     * @var list<string>
     */
    private const array ALL_KEYS = [
        self::KEY_HERO,
        self::KEY_INTRO,
        self::KEY_SECTION_TITLES,
        self::KEY_FEATURES,
        self::KEY_TRANSPORT_CTA,
        self::KEY_BRAND,
        self::KEY_CONTACT,
    ];

    public function show(): JsonResponse
    {
        $rows = SiteSetting::query()
            ->whereIn('key', self::ALL_KEYS)
            ->get();

        $payload = [];
        foreach ($rows as $row) {
            /** @var array<mixed>|null $value */
            $value = $row->value;
            $payload[$row->key] = $value ?? [];
        }

        return $this->successResponse(data: $payload);
    }

    public function update(UpdateSiteSettingsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->writeArray(self::KEY_SECTION_TITLES, $validated['home']['section_titles'] ?? null);
        $this->writeMixed(self::KEY_FEATURES, $validated['home']['features'] ?? null);
        $this->writeArray(self::KEY_BRAND, $request->input('footer.brand'));
        $this->writeArray(self::KEY_CONTACT, $request->input('footer.contact'));

        $this->writeWithImage(self::KEY_HERO, $validated['home']['hero'] ?? null, $request);
        $this->writeWithImage(self::KEY_INTRO, $validated['home']['intro'] ?? null, $request);
        $this->writeWithImage(self::KEY_TRANSPORT_CTA, $validated['home']['transport_cta'] ?? null, $request);

        return $this->successResponse(
            message: 'Pengaturan situs berhasil diperbarui.',
            status: Response::HTTP_OK,
        );
    }

    private function sanitizeScalars(mixed $data): mixed
    {
        if (is_string($data)) {
            return strip_tags($data);
        }

        if (is_array($data)) {
            return array_map([$this, 'sanitizeScalars'], $data);
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>|null  $data
     */
    private function writeArray(string $key, ?array $data): void
    {
        if ($data === null || $data === []) {
            return;
        }

        $payload = [];
        foreach ($data as $k => $v) {
            if (is_scalar($v)) {
                $payload[$k] = is_string($v) ? strip_tags($v) : $v;
            } elseif (is_array($v)) {
                $payload[$k] = json_encode($v);
            }
        }

        if ($payload === []) {
            return;
        }

        $existing = SiteSetting::query()->where('key', $key)->first();
        if ($existing !== null) {
            /** @var array<string, mixed> $existingValue */
            $existingValue = $existing->value;
            $payload = array_merge($existingValue, $payload);
        }

        SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $payload],
        );
    }

    /**
     * @param  array<mixed>|null  $data
     */
    private function writeMixed(string $key, mixed $data): void
    {
        if ($data === null) {
            return;
        }

        $sanitized = $this->sanitizeScalars($data);

        if (is_array($sanitized)) {
            $existing = SiteSetting::query()->where('key', $key)->first();
            if ($existing !== null) {
                /** @var mixed $existingValue */
                $existingValue = $existing->value;
                if (is_array($existingValue)) {
                    $sanitized = array_replace_recursive($existingValue, $sanitized);
                }
            }
        }

        SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $sanitized],
        );
    }

    /**
     * @param  array<string, mixed>|null  $data
     */
    private function writeWithImage(string $key, ?array $data, Request $request): void
    {
        if ($data === null) {
            return;
        }

        $payload = [];
        foreach ($data as $k => $v) {
            if ($k === 'image') {
                continue;
            }
            if (is_scalar($v)) {
                $payload[$k] = is_string($v) ? strip_tags($v) : $v;
            }
        }

        $file = $this->extractFileForKey($request, $key);

        $existing = SiteSetting::query()->where('key', $key)->first();

        if ($file !== null) {
            if ($existing !== null) {
                /** @var array<string, mixed> $existingValue */
                $existingValue = $existing->value;
                $oldImage = $existingValue['image'] ?? null;
                if (is_string($oldImage) && $oldImage !== '') {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $payload['image'] = $file->store('home', 'public');
        }

        if ($existing !== null) {
            /** @var array<string, mixed> $existingValue */
            $existingValue = $existing->value;
            $payload = array_merge($existingValue, $payload);
        }

        if ($payload !== []) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $payload],
            );
        }
    }

    private function extractFileForKey(Request $request, string $key): mixed
    {
        $files = $request->allFiles();
        $segments = explode('.', $key);

        $cursor = $files;
        foreach ($segments as $segment) {
            if (! is_array($cursor) || ! isset($cursor[$segment])) {
                return null;
            }
            $cursor = $cursor[$segment];
        }

        return is_array($cursor) ? ($cursor['image'] ?? null) : null;
    }
}
