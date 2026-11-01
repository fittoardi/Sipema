<?php

namespace App\Concerns;

use Symfony\Component\HttpFoundation\StreamedResponse;

trait StreamsCsv
{
    /**
     * Stream a CSV file download with UTF-8 BOM.
     *
     * @param  array<int, string>  $headers
     * @param  iterable<mixed>  $rows
     * @param  callable(mixed): array<int, mixed>  $rowMapper
     */
    protected function streamCsv(
        string $filename,
        array $headers,
        iterable $rows,
        callable $rowMapper,
        string $contentType = 'text/csv; charset=UTF-8'
    ): StreamedResponse {
        $responseHeaders = [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->stream(function () use ($headers, $rows, $rowMapper): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $rowMapper($row));
            }

            fclose($handle);
        }, 200, $responseHeaders);
    }
}
