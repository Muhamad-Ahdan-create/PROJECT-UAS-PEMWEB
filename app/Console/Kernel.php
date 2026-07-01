protected function schedule(Schedule $schedule): void
{
$schedule->command('angsuran:notifikasi')->dailyAt('08:00');
// Tandai yang terlambat setiap hari tengah malam
$schedule->call(function () {
app(\App\Services\PinjamanService::class)->tandaiTerlambat();
})->daily();
}