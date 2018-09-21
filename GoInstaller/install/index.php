<?php		
require_once('taskCoreClass.php');
$pathFolder = '../../desa/config';
$fileConfig = 'database.php';
$core = new Core($pathFolder,$fileConfig);

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {

	require_once('databaseLibrary.php');
    $sqlCommand = '../../contoh_data_awal.sql';    
	$database = new Database($sqlCommand);    
	if($core->checkEmpty($_POST) == true)
	{
		if($database->create_database($_POST) == false)
		{
			$message = $core->show_message('error',"The database could not be created, make sure your the host, username, password, database name is correct.");
        }else if ($core->checkFile() == false)
		{
			$message = $core->show_message('error',"File ".$core->getFileConfig()." is Empty");
		}
		else if ($core->write_config($_POST) == false)
		{
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod ".$core->getPathFolder()." file to 777");
        }
        if(isset($_POST['showProgress'])){
            if ($core->write_bigdump_config($sqlCommand,$_POST) == false)
            {
                $message = $core->show_message('error',"The bigdump configuration file could not be written, please chmod install folder to 777");
            }else{
                $params = json_encode(array_merge($_POST,array('filename'=> $sqlCommand)));
                $urlWb = 'bigdump.php?params='.$params;       
            }
        }else{
            if ($database->create_tables($_POST) == false)
            {
                $message = $core->show_message('error',"The database could not be created, make sure your the host, username, password, database name is correct.");
            }else{
                $urlWb = 'waiting.php';       
            } 
        }
        

		if(!isset($message)) {
            
            header( 'Location: ' . $urlWb ) ;
		}
	}
	else {
		$message = $core->show_message('error','The host, username, password, database name, and URL are required.');
    }
    
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Install | Welcome to Installer CodeIginter by Abed Putra</title>
		<link href="../../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
    <?php
    $error = 0;
    $error_message = '';
    /* periksa dulu apakah sudah ada folder desa, jika belum maka rename atau cloning dari folder desa-contoh */     
    if(!is_dir($core->getPathFolder()))
    {
        $error++;       
        $error_message = <<<QQQ
            Folder {$core->getPathFolder()} tidak ditemukan, rename folder desa-contoh menjadi folder desa.<br>
            atau buat folder desa dan salin seluruh isi dari folder desa-contoh ke dalam folder desa yang baru dibuat            
QQQ;
    }
    if(!$error){
        if(!is_writable($core->getPathConfig()))
        {
            $error++;
            $error_message = <<<QQQ
            Mohon file {$core->getPathConfig()} diset supaya bisa ditulis (writeable).<br>
            <strong>Contoh</strong>:<br />
            <code>chmod 777 {$core->getPathConfig()}</code> <br /> atau
            <code>chown www-data:www-data {$core->getPathConfig()}</code>         
QQQ;
        }
    }        
    ?>   
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <h1>Installer</h1>
            <hr>
            <?php
            
            if(!$error)
            {
            ?>
                <?php if(isset($message)) {
                echo '
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                ' . $message . '
                </div>';
                }?>
                
                <form id="install_form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="hostname">Hostname</label>
                    <input type="text" id="hostname" value="localhost" placeholder="isi dengan IP atau domain anda" class="form-control" name="hostname" />                
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" name="username" placeholder="username database anda" />
                    
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="password database anda" />
                    
                </div>
                
                <div class="form-group">
                    <label for="database">Database Name</label>
                    <input type="text" id="database" class="form-control" name="database" placeholder="nama database anda" />
                    
                </div>

                <div class="form-group">
                    <label><input type="checkbox" name="showProgress" value="1" /> Show progress</label>
                    <span class="help-block">Jika tidak dicentang maka akan menggunakan asynchronous multi query (hasil dump sql dapat dicek beberapa menit kemudian tergantung servernya). Meskipun script php sudah done tapi sebernarnya proses import dump sql masih berjalan. Jika dicentang maka setelah tampil halaman bigdump klik link start</span>                    
                </div>

                <div class="form-group hide">
                    <label for="database">CodeIgniter Version</label>
                    <select class="form-control" id="template" name="template" />                      
                        <option value="3">3</option>
                    </select>
                    
                </div>
                
                <input type="submit" value="Install" class="btn btn-primary btn-block" id="submit" />
                </form>
        
                <?php 
                } 
                else {
                ?>
                <p class="alert alert-danger">
                    <?php echo $error_message ?>
                </p>
                <?php 
                } 
                ?>
            </div>
            
            <footer>
                <div class="col-md-12" style="text-align:center;margin-bottom:20px">
                    <hr>
                    Copyright - 2017 | <a href="http://abedputra.com">abedputra.com</a>
                </div>
            </footer>
      </div>
      <script src="../../assets/bootstrap/js/jquery.js" type="text/javascript"></script>
      <script src="../../assets/bootstrap/js/bootstrap.js"></script>
	</body>
</html>
