<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once FCPATH.'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel
{
    private $data;
    private $header;
    private $filename;
    private $spreadsheet;
    public function __construct()
    {
        
    }

    public function download()
    {
        $this->generate();
        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$this->getFilename().'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($this->spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    private function generate()
    {
        if(empty($this->getData()))
        {
           return 'Data kosong';
           exit; 
        }
        
        if (empty($this->getHeader())) 
        {
            return 'header kosong';
            exit;
        }
        // Create new Spreadsheet object
        $this->spreadsheet = new Spreadsheet();        
        $this->spreadsheet->setActiveSheetIndex(0);                        
        $this->generateHeader();   
        $this->generateContent();        
        // Get sheet dimension
        $sheet_dimension = $this->spreadsheet->getActiveSheet()->calculateWorksheetDimension();
        // Apply text format to numbers
        $this->spreadsheet->getActiveSheet()->getStyle($sheet_dimension)->getNumberFormat()->setFormatCode('#');
    }

    private function generateHeader(){
        $sheet = $this->spreadsheet->getActiveSheet();
        $asciiA = ord('A');
        $row = 1;
        $this->genereateRow($sheet, $this->getHeader(), $row, $asciiA);
    }

    private function generateContent(){
        $sheet = $this->spreadsheet->getActiveSheet();
        $asciiA = ord('A');
        $row = 2;
        foreach($this->getData() as $data){
            $this->genereateRow($sheet, $data, $row, $asciiA);
            $row++;
        }           
    }

    private function genereateRow($sheet,$data, $row, $asciiA){        
        foreach ($data as $c)
        {
            $column = $this->getColumnExcel($asciiA);            
            $sheet->setCellValue($column.$row, $c);
            $asciiA++;
        }
    }

    private function getColumnExcel($ascii)
    {
        $column = 'A';
        $asciiA = ord('A');
        $asciiZ = ord('Z');
        if ($ascii <= $asciiZ) {
            $column = chr($ascii);
        } else {
            $range = $asciiZ - $asciiA + 1;
            $selisih = $ascii - $asciiZ;
            $section = floor($selisih / $range);
            $sisaBagi = $selisih % $range;
            $column = chr($asciiA + $section).chr($asciiA + $sisaBagi - 1);
        }
        return $column;
    }

    /**
     * Get the value of data
     */ 
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */ 
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of filename
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of header
     */ 
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set the value of header
     *
     * @return  self
     */ 
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }
}
