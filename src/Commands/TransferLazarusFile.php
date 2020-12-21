<?php

namespace Lazarus\Laravel\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class TransferLazarusFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazarus:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfers Lazarus file to the APM for processing.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // copy file to queue directory to be processed
        Storage::move(
            '/logs/lazarus/current.log',
            sprintf('/logs/lazarus/queue/%s.log', microtime(true))
        );

        // empty file for new logs
        Storage::put('/logs/lazarus/current.log', '');

        $queuedFiles = $files = Storage::files('/logs/lazarus/queue');

        foreach ($queuedFiles as $file) {
            // get absolute file path
            $fileName = sprintf('%s/app/%s', storage_path(), $file);

            // compress the file
            $compressedFile = new ZipArchive();
            $compressedFile->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $compressedFile->addFile($fileName, basename($file));

            // get name to send to api
            $compressedFileName = $compressedFile->filename;

            // close compressed object
            $compressedFile->close();

            // read compressed file as stream
            $stream = fopen($compressedFileName, 'r');

            try {
                //  attempt transfer file
                Http::attach(
                    'lazarusFile',
                    $stream,
                    basename($fileName.'.zip')
                )->post('http://lazarus.test/api/transfer');

                // remove file if transferred successfully
                Storage::delete($file);
            } catch (Exception $exception) {
                // handle failure at some point
            }
        }
    }
}
