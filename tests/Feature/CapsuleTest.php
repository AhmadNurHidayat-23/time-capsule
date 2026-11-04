<?php

namespace Tests\Feature;

use App\Models\Capsule;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class CapsuleTest extends TestCase
{
    use RefreshDatabase;

    private string $token = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

    private function capsule(array $attributes = []): Capsule
    {
        return Capsule::factory()->create(['token_hash' => hash('sha256', $this->token), ...$attributes]);
    }

    public function test_stores_encrypted_message_and_utc_schedule_then_shares_unique_link(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-04 00:00:00', 'UTC'));
        $response = $this->post(route('capsules.store'), ['title' => 'Surat untuk esok', 'body' => 'Rahasia hanya untuk nanti.', 'opens_at' => '2026-11-04T09:00'])->assertRedirect();
        $capsule = Capsule::firstOrFail();
        $raw = DB::table('capsules')->first();
        $this->assertNotSame('Rahasia hanya untuk nanti.', $raw->body);
        $this->assertNotSame('Surat untuk esok', $raw->title);
        $this->assertSame('2026-11-04 02:00:00', $capsule->opens_at->format('Y-m-d H:i:s'));
        $this->assertSame('Rahasia hanya untuk nanti.', $capsule->body);
        $this->assertArrayNotHasKey('body', $capsule->toArray());
        $location = $response->headers->get('Location');
        preg_match('~/capsules/([A-Za-z0-9]{64})/saved$~', $location, $matches);
        $this->assertCount(2, $matches);
        $this->assertSame(hash('sha256', $matches[1]), $capsule->token_hash);
        $this->get($location)->assertOk()->assertSee('Pesan Anda telah disimpan dengan aman.')->assertSee('09.00 WIB')->assertDontSee('Rahasia hanya untuk nanti.');
    }

    public function test_locked_html_contains_no_body_or_ciphertext_even_with_fake_client_time(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-04 01:00:00', 'UTC'));
        $capsule = $this->capsule(['body' => 'rahasia-yang-tidak-boleh-bocor', 'opens_at' => now('UTC')->addHour()]);
        $this->get(route('capsules.show', $this->token).'?unlocked=1&now=9999999999999')->assertOk()->assertSee('Pesan ini belum dapat dibuka.')->assertDontSee('rahasia-yang-tidak-boleh-bocor')->assertDontSee($capsule->getRawOriginal('body'))->assertHeader('Referrer-Policy', 'no-referrer')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->getJson(route('capsules.status', $this->token))->assertOk()->assertExactJson(['serverNow' => 1793754000000, 'opensAt' => 1793757600000, 'unlocked' => false]);
    }

    public function test_opens_exactly_at_server_deadline_and_renders_complete_escaped_message(): void
    {
        $opening = CarbonImmutable::parse('2026-11-04 02:00:00', 'UTC');
        $this->capsule(['body' => "Baris pertama.\n<script>alert(1)</script>\nBaris terakhir.", 'opens_at' => $opening]);
        $this->travelTo($opening->subSecond());
        $this->get(route('capsules.show', $this->token))->assertOk()->assertDontSee('Baris pertama.');
        $this->travelTo($opening);
        $this->get(route('capsules.show', $this->token))->assertOk()->assertSee('Kapsul waktu telah terbuka.')->assertSee('Baris pertama.')->assertSee('Baris terakhir.')->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false)->assertSee('09.00 WIB');
        $this->getJson(route('capsules.status', $this->token))->assertOk()->assertJsonPath('unlocked', true)->assertJsonMissingPath('body');
    }

    public function test_status_never_returns_message_even_after_deadline_and_saved_page_stays_safe(): void
    {
        $this->capsule(['body' => 'Jangan bocorkan lewat status', 'opens_at' => now('UTC')->subDay()]);
        $this->getJson(route('capsules.status', $this->token))->assertOk()->assertJsonStructure(['serverNow', 'opensAt', 'unlocked'])->assertDontSee('Jangan bocorkan lewat status')->assertJsonMissingPath('title')->assertJsonMissingPath('token_hash');
        $this->get(route('capsules.saved', $this->token))->assertOk()->assertDontSee('Jangan bocorkan lewat status');
    }

    public function test_locked_reads_do_not_decrypt_body(): void
    {
        $capsule = $this->capsule();
        DB::table('capsules')->where('id', $capsule->id)->update(['body' => 'invalid-ciphertext']);
        $this->get(route('capsules.show', $this->token))->assertOk()->assertSee('Pesan ini belum dapat dibuka.');
        $this->getJson(route('capsules.status', $this->token))->assertOk()->assertJsonPath('unlocked', false);
    }

    #[TestWith(['2026-11-04T07:00'])]
    #[TestWith(['2026-11-04T06:59'])]
    #[TestWith(['2026-02-30T12:00'])]
    #[TestWith(['tomorrow'])]
    #[TestWith(['2026-11-05T12:00:00+07:00'])]
    public function test_rejects_nonfuture_or_invalid_dates_without_storing_message(string $date): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-11-04 00:00:00', 'UTC'));
        $this->postJson(route('capsules.store'), ['title' => 'Demo', 'body' => 'rahasia-validation', 'opens_at' => $date])->assertUnprocessable()->assertJsonValidationErrors('opens_at')->assertDontSee('rahasia-validation');
        $this->assertDatabaseCount('capsules', 0);
    }

    public function test_failed_validation_does_not_flash_body_into_session_or_html(): void
    {
        $this->from(route('home'))->post(route('capsules.store'), ['title' => '', 'body' => 'Rahasia dalam input', 'opens_at' => 'invalid'])->assertRedirect(route('home'))->assertSessionHasErrors(['title', 'opens_at'])->assertSessionMissing('_old_input.body');
        $this->get(route('home'))->assertOk()->assertDontSee('Rahasia dalam input');
        $this->assertDatabaseCount('capsules', 0);
    }

    public function test_rejects_empty_oversized_and_nonscalar_inputs(): void
    {
        $this->postJson(route('capsules.store'), [])->assertUnprocessable()->assertJsonValidationErrors(['title', 'body', 'opens_at']);
        $this->postJson(route('capsules.store'), ['title' => str_repeat('a', 121), 'body' => str_repeat('b', 20001), 'opens_at' => now('Asia/Jakarta')->addDay()->format('Y-m-d\TH:i')])->assertUnprocessable()->assertJsonValidationErrors(['title', 'body']);
        $this->postJson(route('capsules.store'), ['title' => ['bad'], 'body' => ['bad'], 'opens_at' => '2028-01-01T12:00'])->assertUnprocessable()->assertJsonValidationErrors(['title', 'body']);
        $this->assertDatabaseCount('capsules', 0);
    }

    public function test_tokens_are_distinct_and_unknown_links_do_not_expose_records(): void
    {
        $data = ['title' => 'Demo', 'body' => 'Rahasia', 'opens_at' => now('Asia/Jakarta')->addDay()->format('Y-m-d\TH:i')];
        $a = $this->post(route('capsules.store'), $data)->assertRedirect()->headers->get('Location');
        $b = $this->post(route('capsules.store'), $data)->assertRedirect()->headers->get('Location');
        $this->assertNotSame($a, $b);
        $this->get('/capsules/1')->assertNotFound();
        $this->get(route('capsules.show', $this->token))->assertNotFound()->assertDontSee('Rahasia');
        $this->getJson(route('capsules.status', $this->token))->assertNotFound();
    }

    public function test_save_rate_limit_prevents_extra_records(): void
    {
        $data = ['title' => 'Demo', 'body' => 'Isi demo', 'opens_at' => now('Asia/Jakarta')->addDay()->format('Y-m-d\TH:i')];
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('capsules.store'), $data)->assertRedirect();
        }
        $this->post(route('capsules.store'), $data)->assertTooManyRequests();
        $this->assertDatabaseCount('capsules', 5);
    }

    public function test_privacy_headers_prevent_storage_and_inline_scripts(): void
    {
        $this->capsule();
        $response = $this->get(route('capsules.show', $this->token))->assertOk()->assertHeader('X-Frame-Options', 'DENY');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString("script-src 'self'", $response->headers->get('Content-Security-Policy'));
    }
}
