<?php

namespace Tests\Unit;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_time_end_is_auto_set_when_status_becomes_resolved(): void
    {
        $ticket = Ticket::query()->create([
            'code' => 'TCK-0001',
            'subject' => 'Test',
            'requester_name' => 'Tester',
            'category' => 'Other',
            'priority' => 'low',
            'status' => 'active',
            'time_start' => '08:00',
        ]);

        $this->assertNull($ticket->time_end);

        $ticket->update(['status' => 'resolved']);
        $ticket->refresh();

        $this->assertNotNull($ticket->time_end);
    }

    public function test_time_end_is_not_overwritten_if_already_set(): void
    {
        $ticket = Ticket::query()->create([
            'code' => 'TCK-0002',
            'subject' => 'Test',
            'requester_name' => 'Tester',
            'category' => 'Other',
            'priority' => 'low',
            'status' => 'active',
            'time_start' => '08:00',
            'time_end' => '08:30',
        ]);

        $ticket->update(['status' => 'closed']);
        $ticket->refresh();

        $this->assertSame('08:30', \Illuminate\Support\Carbon::parse($ticket->time_end)->format('H:i'));
    }
}
