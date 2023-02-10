<?PHP
/*
*	проект Morf	- сохранение и восстановление файлов и базы данных
*/

//
	echo ( '<!DOCTYPE html><html lang="ru"><head><meta http-equiv="Content-type" content="text/html; charset=UTF-8" /></head><body>');


$backup_folder = '/sup/morf/backup';    // куда будут сохранятся файлы
$backup_name = 'my_site_backup_' . date("Y-m-d");    // имя архива
$dir = '/sup/morf';    // что бэкапим
$delay_delete = 30 * 24 * 3600;    // время жизни архива (в секундах)

$db_host = 'localhost';
$db_user = 'walks_admin';
$db_password = 'walks_admin_123';
$db_name = 'walks';

$mail_to = 'at@walks.ru';
$mail_subject = 'Site backup';
$mail_message = '';
$mail_headers = 'MIME-Version: 1.0' . "\r\n"; $mail_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; $mail_headers .= 'To: me <my_email@example.com>' . "\r\n"; $mail_headers .= 'From: my_site <info@example.com>' . "\r\n";


		$handl  = $_SERVER['DOCUMENT_ROOT'];	// путь до сайта
		$backup_folder = $handl.$backup_folder ;		// полный путь до содержимого раздела

echo '<br>'.$backup_folder. '<hr>' ;


/*

// функция для бэкапа файлов:  ( для Линукс сервера )
function backupFiles ( $backup_folder, $backup_name, $dir ) {
    $fullFileName = $backup_folder . '/' . $backup_name . '.tar.gz';
    shell_exec("tar -cvf " . $fullFileName . " " . $dir . "/* ");  // ( для Линукс сервера )
    return $fullFileName;
};

 */
 
 
// функция для бэкапа Базы данных:
function backupDB ($backup_folder, $backup_name) {
  //  $fullFileName = $backup_folder . '/' . $backup_name . '.sql';
  
$db_host = 'localhost';
$db_user = 'walks_admin';
$db_password = 'walks_admin_123';
$db_name = 'walks';  
  
    $fullFileName = $backup_name . '.sql';
    $command = 'mysqldump -h' . $db_host . ' -u' . $db_user . ' -p' . $db_password . ' ' . $db_name . ' > ' . $fullFileName;
    echo ( '<br>'. $command. '<hr>' ) ;
    shell_exec($command);
    return $fullFileName;
};

/*
// функция для удаления старых архивов:
function deleteOldArchives($backup_folder, $delay_delete) {
    $this_time = time();
    $files = glob($backup_folder . "/*.tar.gz*");
    $deleted = array();
    foreach ($files as $file) {
        if ($this_time - filemtime($file) > $delay_delete) {
            array_push($deleted, $file);
            unlink($file);
        }
    }
    return $deleted;
};
*/

//  $start = microtime(true);    // запускаем таймер

// $deleteOld = deleteOldArchives($backup_folder, $delay_delete);    	// удаляем старые архивы
// $doBackupFiles = backupFiles($backup_folder, $backup_name, $dir);    // делаем бэкап файлов

 $doBackupDB = backupDB($backup_folder, $backup_name);    				// делаем бэкап базы данных

/*
// добавляем в письмо отчеты
if ($doBackupFiles) {
    $mail_message .= 'site backuped successfully<br/>';
    $mail_message .= 'Files: ' . $doBackupFiles . '<br/>'; }

*/

if ($doBackupDB) {
//    $mail_message .= 'DB: ' . $doBackupDB . '<br/>'; 
	echo 'DB: ' . $doBackupDB . '<br/>'; 
};

/*
if ($deleteOld) {
    foreach ($deleteOld as $val) {
        $mail_message .= 'File deleted: ' . $val . '<br/>';
    }
}


$time = microtime(true) - $start;     // считаем время, потраченое на выполнение скрипта
$mail_message .= 'script time: ' . $time . '<br/>';

mail($mail_to, $mail_subject, $mail_message, $mail_headers);    // и отправляем письмо

*/

?>
