<?php

namespace App\Services;

use RuntimeException;
use ZipArchive;

class SimpleXlsxExporter
{
    /**
     * @param  array<int, array<int, mixed>>  $rows
     */
    public function make(array $rows): string
    {
        $path = tempnam(sys_get_temp_dir(), 'income-report-');

        if ($path === false) {
            throw new RuntimeException('Unable to create temporary export file.');
        }

        $zip = new ZipArchive;

        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Unable to create xlsx export.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addFromString('_rels/.rels', $this->rootRelationships());
        $zip->addFromString('xl/workbook.xml', $this->workbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationships());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheet($rows));
        $zip->close();

        $contents = file_get_contents($path);
        @unlink($path);

        if ($contents === false) {
            throw new RuntimeException('Unable to read xlsx export.');
        }

        return $contents;
    }

    private function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'</Types>';
    }

    private function rootRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    private function workbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Income Report" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    private function workbookRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'</Relationships>';
    }

    /**
     * @param  array<int, array<int, mixed>>  $rows
     */
    private function worksheet(array $rows): string
    {
        $sheetRows = collect($rows)
            ->values()
            ->map(function (array $row, int $rowIndex): string {
                $cells = collect($row)
                    ->values()
                    ->map(fn (mixed $value, int $columnIndex): string => $this->cell($value, $columnIndex + 1, $rowIndex + 1))
                    ->implode('');

                return '<row r="'.($rowIndex + 1).'">'.$cells.'</row>';
            })
            ->implode('');

        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<sheetData>'.$sheetRows.'</sheetData>'
            .'</worksheet>';
    }

    private function cell(mixed $value, int $column, int $row): string
    {
        $reference = $this->columnName($column).$row;

        if (is_int($value) || is_float($value)) {
            return '<c r="'.$reference.'"><v>'.$value.'</v></c>';
        }

        return '<c r="'.$reference.'" t="inlineStr"><is><t>'.$this->escape((string) $value).'</t></is></c>';
    }

    private function columnName(int $column): string
    {
        $name = '';

        while ($column > 0) {
            $column--;
            $name = chr(65 + ($column % 26)).$name;
            $column = intdiv($column, 26);
        }

        return $name;
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
