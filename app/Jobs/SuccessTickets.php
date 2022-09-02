<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SuccessTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $ticketData;

    public function __construct($ticketData)
    {
        $this->ticketData = $ticketData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $column = Arr::get($this->ticketData, 'column_name');
        $value = Arr::get($this->ticketData, 'column_value');

        if ($column === 'visibility') {
            return true;
        }

        if ($this->ticketData['type'] === 'update_column_value' && !in_array($column, ['due_date', 'name', 'description'])) {
            $meta = Http::withoutVerifying()->withToken(config('services.success.public_api_token'))->get(config('services.success.public_api_url') . '/metas?filter[type]=ticket_' . $column)->json('data');
            $meta = collect($meta)->firstWhere('value', $this->ticketData['column_value']);
            $value = Arr::get($meta, 'id', Arr::get(Arr::first($meta), 'id'));
        }

        if ($this->ticketData['type'] === 'update_name') {
            $payload = [
                'title' => Arr::get($this->ticketData, 'name')
            ];
        }

        if ($this->ticketData['type'] === 'update_column_value') {
            $payload = [
                'title' => Arr::get($this->ticketData, 'name'),
                in_array($column, ['due_date', 'name', 'description']) ? $column : $column . '_id' => $value,
            ];
        }

        Http::withoutVerifying()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->withToken(config('services.success.public_api_token'))
            ->patch(config('services.success.public_api_url') . '/tickets/' . $this->ticketData['id'], $payload);

        return true;
    }
}
