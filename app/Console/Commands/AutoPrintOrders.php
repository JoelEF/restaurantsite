<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class AutoPrintOrders extends Command
{
    protected $signature = 'orders:auto-print
                            {--printer= : Naam van de printer (standaard: standaardprinter)}
                            {--interval=15 : Polling interval in seconden}
                            {--once : Eenmalig draaien (geen loop)}';

    protected $description = 'Print nieuwe bestellingen automatisch naar de printer (draait als achtergrondservice)';

    public function handle(): int
    {
        $printer  = $this->option('printer');
        $interval = (int) $this->option('interval');
        $once     = $this->option('once');

        $this->info('Auto-print service gestart. Printer: ' . ($printer ?: 'standaard'));
        $this->info("Polling elke {$interval} seconden. Ctrl+C om te stoppen.");
        $this->line('');

        do {
            $this->checkAndPrint($printer);

            if (!$once) {
                sleep($interval);
            }
        } while (!$once);

        return self::SUCCESS;
    }

    private function checkAndPrint(?string $printer): void
    {
        $orders = Order::with('items')
            ->whereNull('printed_at')
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at')
            ->get();

        if ($orders->isEmpty()) {
            return;
        }

        foreach ($orders as $order) {
            $bonText = $this->generateBonText($order);

            $success = $this->sendToPrinter($bonText, $printer);

            if ($success) {
                $order->update(['printed_at' => now()]);
                $this->info("[{$order->order_number}] Geprint om " . now()->format('H:i:s'));
            } else {
                $this->error("[{$order->order_number}] Print mislukt — probeer opnieuw bij volgende check");
            }
        }
    }

    private function generateBonText(Order $order): string
    {
        $line  = str_repeat('-', 42);
        $dline = str_repeat('=', 42);
        $type  = $order->type === 'delivery' ? 'BEZORGING' : 'AFHALEN';

        $bon = [];
        $bon[] = '';
        $bon[] = $this->center(config('app.name', 'Restaurant'), 42);
        $bon[] = $dline;
        $bon[] = $this->center("BON #{$order->order_number}", 42);
        $bon[] = $this->center($order->created_at->format('d-m-Y  H:i'), 42);
        $bon[] = $this->center("*** {$type} ***", 42);
        $bon[] = $dline;
        $bon[] = "Naam:  {$order->customer_name}";
        $bon[] = "Tel:   {$order->customer_phone}";

        if ($order->type === 'delivery' && $order->delivery_address) {
            $bon[] = "Adres: {$order->delivery_address}";
        }

        $bon[] = $line;
        $bon[] = 'BESTELLING:';

        foreach ($order->items as $item) {
            $qty  = str_pad($item->quantity . 'x', 4);
            $bon[] = "  {$qty} {$item->name}";
        }

        if ($order->notes) {
            $bon[] = $line;
            $bon[] = 'OPMERKING:';
            // Woordombreking voor lange opmerkingen
            foreach (wordwrap($order->notes, 40, "\n", true) as $noteLine) {
                $bon[] = "  {$noteLine}";
            }
        }

        $bon[] = $dline;
        $bon[] = $this->center('Bedankt voor uw bestelling!', 42);
        $bon[] = '';
        $bon[] = '';  // Extra witregel voor afscheur ruimte
        $bon[] = '';

        return implode("\n", $bon) . "\n";
    }

    private function sendToPrinter(string $text, ?string $printer): bool
    {
        // Schrijf tijdelijk naar temp bestand
        $tmpFile = tempnam(sys_get_temp_dir(), 'bon_');
        file_put_contents($tmpFile, $text);

        // Bouw lp commando
        $cmd = ['lp'];
        if ($printer) {
            $cmd[] = '-d';
            $cmd[] = $printer;
        }
        $cmd[] = '-o';
        $cmd[] = 'raw';       // Stuur tekst direct als raw (voor thermische printers)
        $cmd[] = $tmpFile;

        $result = Process::run($cmd);

        unlink($tmpFile);

        return $result->successful();
    }

    private function center(string $text, int $width): string
    {
        $len = strlen($text);
        if ($len >= $width) return $text;
        $pad = (int)(($width - $len) / 2);
        return str_repeat(' ', $pad) . $text;
    }
}
