<?php

use Illuminate\Support\Facades\Schedule;

use App\Jobs\AtualizarFeedsJob;

Schedule::call(function () {
    AtualizarFeedsJob::dispatch();
})->twiceDaily(8, 14);
