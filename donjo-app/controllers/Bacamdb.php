<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Bacamdb extends CI_Controller {
    protected $mdb;
	public function __construct()
	{
		parent::__construct();
        $this->mdb = $this->load->database('siskeudes',TRUE);
    }

    public function tes(){
        $pathMdbFile = FCPATH.'siskeudes'.DIRECTORY_SEPARATOR.'HITFPTA.mdb';
        
        $sql = <<<SQL
        SELECT MSysObjects.Name AS table_name
        FROM MSysObjects
        WHERE (((Left([Name],1))<>"~") 
                AND ((Left([Name],4))<>"MSys") 
                AND ((MSysObjects.Type) In (1,4,6))
                AND ((MSysObjects.Flags)=0))
        order by MSysObjects.Name 
SQL;
        $mdb_file = $pathMdbFile;
        $uname = explode(" ",php_uname());
        $os = $uname[0];
        switch ($os){
        case 'Windows':
            $driver = '{Microsoft Access Driver (*.mdb)}';
            break;
        case 'Linux':
            $driver = '{Microsoft Access Driver (*.mdb)}';
            break;
        default:
            exit("Don't know about this OS");
        }
        
        $dataSourceName = "odbc:Driver=$driver;DBQ=/data/docker/OpenSID/siskeudes/HITFPTA.mdb;";
        $connection = new \PDO($dataSourceName);
        
        //$result = $connection->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        foreach($connection->query($sql) as $row){
            print_r($row);
        }
        
    }


    public function index(){
        $connected = $this->mdb->initialize();
        if (!$connected) {
            $msg = 'Database access tidak bisa di akses';
            print_r($msg);
            return $msg;
        }

        $query = '  select distinct datelog from personallog ';
        // Ambil data di access dg filter tanggal
        $query .= ' where personallog.datelog between #01-May-2019# and #05-May-2019# order by datelog';
        $runToHAbsensi = false;
        try {
            $runToHAbsensi = $this->mdb->query($query);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        echo "<pre>";
        print_r($runToHAbsensi);
        echo "</pre>";
        exit();
        $sql = <<<SQL
        select distinct datelog from personallog
SQL;
    }
}