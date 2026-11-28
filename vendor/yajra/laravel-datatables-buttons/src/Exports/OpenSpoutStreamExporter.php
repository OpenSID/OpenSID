<?php

namespace Yajra\DataTables\Exports;

use Illuminate\Support\Collection;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\CSV\Writer as CsvWriter;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Html\Column;

final class OpenSpoutStreamExporter
{
    /**
     * @param  callable(): Collection<int, Column>  $exportableColumnsResolver
     * @param  callable(): iterable<mixed>  $rowsResolver
     * @param  callable(mixed, Collection<int, Column>): array<int, mixed>  $mapRow
     *
     * @throws Exception
     */
    public function streamDownload(
        bool $asCsv,
        string $filenameWithoutExtension,
        string $csvWriter,
        string $excelWriter,
        callable $exportableColumnsResolver,
        callable $rowsResolver,
        callable $mapRow,
    ): StreamedResponse {
        if (! class_exists(XlsxWriter::class)) {
            throw new Exception('Please `composer require openspout/openspout` to be able to use this function.');
        }

        return response()->streamDownload(
            function () use (
                $asCsv,
                $exportableColumnsResolver,
                $rowsResolver,
                $mapRow
            ): void {
                $exportableColumns = $exportableColumnsResolver();
                $rows = $rowsResolver();
                $this->writeToPhpOutput($asCsv, $exportableColumns, $rows, $mapRow);
            },
            $this->downloadFilename($asCsv, $filenameWithoutExtension, $csvWriter, $excelWriter),
            $this->downloadHeaders($asCsv)
        );
    }

    private function downloadFilename(bool $asCsv, string $base, string $csvWriter, string $excelWriter): string
    {
        $suffix = strtolower($asCsv ? $csvWriter : $excelWriter);

        return $base.'.'.$suffix;
    }

    /**
     * @return array<string, string>
     */
    private function downloadHeaders(bool $asCsv): array
    {
        if ($asCsv) {
            return ['Content-Type' => 'text/csv; charset=UTF-8'];
        }

        return ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    }

    /**
     * @param  Collection<int, Column>  $exportableColumns
     * @param  iterable<mixed>  $rows
     * @param  callable(mixed, Collection<int, Column>): array<int, mixed>  $mapRow
     */
    private function writeToPhpOutput(bool $asCsv, Collection $exportableColumns, iterable $rows, callable $mapRow): void
    {
        $writer = $asCsv ? new CsvWriter : new XlsxWriter;
        $writer->openToFile('php://output');

        $titles = $exportableColumns->map(fn (Column $column) => $column->title)->all();
        $writer->addRow(Row::fromValues($titles));

        $columnStylesByIndex = $this->columnStylesIndexedForExport($asCsv, $exportableColumns);

        foreach ($rows as $row) {
            $values = $mapRow($row, $exportableColumns);
            $writer->addRow($this->exportRowForWriter($values, $asCsv, $columnStylesByIndex));
        }

        $writer->close();
    }

    /**
     * @param  Collection<int, Column>  $exportableColumns
     * @return array<int, Style>
     */
    private function columnStylesIndexedForExport(bool $asCsv, Collection $exportableColumns): array
    {
        if ($asCsv) {
            return [];
        }

        $styles = [];
        foreach ($exportableColumns as $index => $column) {
            if ($column->exportFormat) {
                $styles[$index] = $this->cellStyleWithFormat($column->exportFormat);
            }
        }

        return $styles;
    }

    /**
     * @param  array<int, mixed>  $values
     * @param  array<int, Style>  $columnStylesByIndex
     */
    private function exportRowForWriter(array $values, bool $asCsv, array $columnStylesByIndex): Row
    {
        if ($asCsv || $columnStylesByIndex === []) {
            return Row::fromValues($values);
        }

        return $this->rowWithColumnStyles($values, $columnStylesByIndex);
    }

    private function cellStyleWithFormat(string $format): Style
    {
        // OpenSpout 5.x: withFormat(); 4.x: setFormat() (mutating).
        // @phpstan-ignore function.alreadyNarrowedType (OpenSpout major differs)
        if (method_exists(Style::class, 'withFormat')) {
            return (new Style)->withFormat($format);
        }

        $style = new Style;

        // @phpstan-ignore method.notFound (OpenSpout 4.x)
        return $style->setFormat($format);
    }

    /**
     * OpenSpout 5.x: fromValuesWithStyles(values, columnStyles).
     * OpenSpout 4.x: fromValuesWithStyles(values, ?rowStyle, columnStyles).
     *
     * @param  array<int, mixed>  $values
     * @param  array<int, Style>  $columnStylesByIndex
     */
    private function rowWithColumnStyles(array $values, array $columnStylesByIndex): Row
    {
        if ($this->columnStylesIsSecondArgument()) {
            return Row::fromValuesWithStyles($values, $columnStylesByIndex);
        }

        return Row::fromValuesWithStyles($values, null, $columnStylesByIndex);
    }

    private function columnStylesIsSecondArgument(): bool
    {
        static $cached = null;

        if ($cached === null) {
            $method = new ReflectionMethod(Row::class, 'fromValuesWithStyles');
            $secondParameterType = $method->getParameters()[1]->getType();
            $cached = $secondParameterType instanceof ReflectionNamedType
                && $secondParameterType->getName() === 'array';
        }

        return $cached;
    }
}
