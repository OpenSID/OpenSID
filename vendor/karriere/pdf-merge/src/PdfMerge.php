<?php

namespace Karriere\PdfMerge;

use Karriere\PdfMerge\Exceptions\FileNotFoundException;
use Karriere\PdfMerge\Exceptions\NoFilesDefinedException;
use TCPDI;

class PdfMerge
{
    private array $files = [];
    private TCPDI $pdf;

    /**
     * Passed parameters overrides settings for header and footer by calling tcpdf.php methods:
     * setHeaderData($ln='', $lw=0, $ht='', $hs='', $tc=array(0,0,0), $lc=array(0,0,0))
     * setFooterData($tc=array(0,0,0), $lc=array(0,0,0))
     * For more info about tcpdf, please read https://tcpdf.org/docs/
     *
     * @param array $headerConfig Only values for keys 'ln', 'lw', 'ht', 'hs', 'tc', 'lc' are taken into account
     * @param array $footerConfig Only values for keys 'tc', 'lc' are taken into account
     */
    public function __construct(array $headerConfig = [], array $footerConfig = [])
    {
        $this->pdf = new TCPDI();
        $this->configureHeaderAndFooter($headerConfig, $footerConfig);
    }

    public function getPdf(): TCPDI
    {
        return $this->pdf;
    }

    /**
     * Adds a file to merge
     *
     * @param string $file The file to merge
     * @throws FileNotFoundException when the given file does not exist
     */
    public function add(string $file): void
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        $this->files[] = $file;
    }

    /**
     * Checks if the given file is already registered for merging
     */
    public function contains(string $file): bool
    {
        return in_array($file, $this->files);
    }

    /**
     * Resets the stored files
     */
    public function reset(): void
    {
        $this->files = [];
    }

    /**
     * Generates a merged PDF file from the already stored PDF files
     *
     * @param string $outputFilename The file to write to
     * @param ?string $destination Destination where to send the document, see TCPDF docs for more info
     * @see \TCPDF::Output()
     * @throws NoFilesDefinedException
     */
    public function merge(string $outputFilename, ?string $destination = 'F'): string
    {
        if (count($this->files) === 0) {
            throw new NoFilesDefinedException();
        }

        foreach ($this->files as $file) {
            $pageCount = $this->pdf->setSourceFile($file);

            for ($i = 1; $i <= $pageCount; $i++) {
                $pageId = $this->pdf->ImportPage($i);
                $size = $this->pdf->getTemplateSize($pageId);

                $this->pdf->AddPage('', $size);
                $this->pdf->useTemplate($pageId, null, null, 0, 0, true);
            }
        }

        return $this->pdf->Output($outputFilename, $destination);
    }

    private function configureHeaderAndFooter(array $headerConfig, array $footerConfig): void
    {
        if (count($headerConfig)) {
            $ln = $headerConfig['ln'] ?? '';
            $lw = $headerConfig['lw'] ?? 0;
            $ht = $headerConfig['ht'] ?? '';
            $hs = $headerConfig['hs'] ?? '';
            $tc = $headerConfig['tc'] ?? [0, 0, 0];
            $lc = $headerConfig['lc'] ?? [0, 0, 0];
            $this->pdf->setHeaderData($ln, $lw, $ht, $hs, $tc, $lc);
        } else {
            $this->pdf->setPrintHeader(false);
        }

        if (count($footerConfig)) {
            $tc = $footerConfig['tc'] ?? [0, 0, 0];
            $lc = $footerConfig['lc'] ?? [0, 0, 0];
            $this->pdf->setFooterData($tc, $lc);
        } else {
            $this->pdf->setPrintFooter(false);
        }
    }
}
