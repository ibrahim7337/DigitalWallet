<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ReceiveMoney;

use App\Jobs\ProcessWebhook;
use App\Models\User;
use App\Services\ReceiveMoney\BanksWebhookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use RuntimeException;
use Tests\TestCase;

class BanksWebhookServiceTest extends TestCase
{
    use RefreshDatabase;

    private function makeService(): BanksWebhookService
    {
        return new class extends BanksWebhookService
        {
            protected function parseTransaction(string $webhook): array
            {
                return [
                    'date' => '2026-10-17',
                    'amount' => 100.00,
                    'reference' => $webhook,
                ];
            }

            /**
             * Expose parsing for isolated tests.
             *
             * @return array<mixed>
             */
            public function parseForTesting(string $webhook): array
            {
                return $this->parseTransaction($webhook);
            }
        };
    }

    /**
     * @param  array<int, string>  $webhooks
     */
    private function makeRequest(array $webhooks): Request
    {
        return Request::create('/test', 'POST', [
            'webhooks' => $webhooks,
        ]);
    }

    public function test_it_persists_webhooks_and_dispatches_jobs(): void
    {
        User::factory()->create(['id' => 1]);

        Bus::fake();

        $response = $this->makeService()->handle(
            $this->makeRequest(['TXN-001', 'TXN-002']),
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            ['message' => 'Webhook processed successfully.'],
            $response->getData(true),
        );

        $this->assertDatabaseCount('webhooks', 2);

        Bus::assertDispatched(ProcessWebhook::class, 2);
    }

    public function test_it_returns_failure_when_receiver_does_not_exist(): void
    {
        Bus::fake();

        $response = $this->makeService()->handle(
            $this->makeRequest(['TXN-001']),
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            ['message' => 'Webhook processed failed.'],
            $response->getData(true),
        );

        Bus::assertNothingDispatched();
    }

    public function test_it_returns_failure_when_parsing_throws_an_exception(): void
    {
        User::factory()->create(['id' => 1]);

        Bus::fake();

        $service = new class extends BanksWebhookService
        {
            protected function parseTransaction(string $webhook): array
            {
                throw new RuntimeException('Simulated parsing failure.');
            }
        };

        $response = $service->handle(
            $this->makeRequest(['TXN-001']),
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            ['message' => 'Webhook processed failed.'],
            $response->getData(true),
        );

        Bus::assertNothingDispatched();
    }

    public function test_it_formats_dates_correctly(): void
    {
        $service = new class extends BanksWebhookService
        {
            protected function parseTransaction(string $webhook): array
            {
                return [];
            }

            public function formatDateForTest(string $date): string
            {
                return $this->formatDate($date);
            }
        };

        $this->assertSame(
            '2026-10-17',
            $service->formatDateForTest('2026-10-17 14:30:00'),
        );
    }

    public function test_it_parses_1000_transactions_within_acceptable_time(): void
    {
        $service = $this->makeService();

        $webhooks = array_map(
            static fn (int $index): string => sprintf('TXN-%04d', $index),
            range(1, 1000),
        );

        $start = hrtime(true);

        foreach ($webhooks as $webhook) {
            $service->parseForTesting($webhook);
        }

        $elapsedMilliseconds = (hrtime(true) - $start) / 1_000_000;

        $this->assertLessThan(
            1000,
            $elapsedMilliseconds,
            sprintf(
                'Parsing 1,000 transactions took %.2f ms; expected less than 1,000 ms.',
                $elapsedMilliseconds,
            ),
        );
    }

    public function test_it_persists_and_dispatches_1000_transactions(): void
    {
        User::factory()->create(['id' => 1]);

        Bus::fake();

        $webhooks = array_map(
            static fn (int $index): string => sprintf('TXN-%04d', $index),
            range(1, 1000),
        );

        $start = hrtime(true);

        $response = $this->makeService()->handle(
            $this->makeRequest($webhooks),
        );

        $elapsedMilliseconds = (hrtime(true) - $start) / 1_000_000;

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            ['message' => 'Webhook processed successfully.'],
            $response->getData(true),
        );

        $this->assertDatabaseCount('webhooks', 1000);

        Bus::assertDispatched(ProcessWebhook::class, 1000);

        fwrite(
            STDERR,
            sprintf(
                "\nProcessed 1,000 webhooks in %.2f ms.\n",
                $elapsedMilliseconds,
            ),
        );
    }
}
