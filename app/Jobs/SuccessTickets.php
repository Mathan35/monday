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

        if ($column != 'due_date') {
            $meta = Http::withoutVerifying()->withToken(config('services.success.public_api_token'))->get(config('services.success.public_api_url') . '/metas?filter[type]=ticket_' . $column)->json('data');
            $meta = collect($meta)->firstWhere('value', $this->ticketData['column_value']);
            $value = Arr::get($meta, 'id', Arr::get(Arr::first($meta), 'id'));
        }

        if (!Arr::get($this->ticketData, 'name')) {
            $ticket = Http::withoutVerifying()->withToken(config('services.success.public_api_token'))->get(config('services.success.public_api_url') . '/tickets/' . $this->ticketData['id'])->json();
        }

        Http::withoutVerifying()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->withToken(config('services.success.public_api_token'))
            ->patch(config('services.success.public_api_url') . '/tickets/' . $this->ticketData['id'], [
                'title' => Arr::get($this->ticketData, 'name') ?? Arr::get($ticket, 'data.title'),
                $column === 'due_date' ? 'due_date' : $column . '_id' => $value,
            ]);

        return true;
    }
}
