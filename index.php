<?php
    $connect = mysqli_connect('localhost', 'root', '');
    $query = mysqli_query($connect, 'SHOW DATABASES');
    $path = '/mnt/backup/';
   
    $exception = array('information_schema' => array('exception' => '1'),
                       'mysql'              => array('exception' => '1'),
                       'performance_schema' => array('exception' => '1'),
                       'test'             	=> array('tables' => array(
                                                                        'test1'  => array('exception' => '1'),
                                                                        'test2'  => array('exception' => '1'),
                                                                        'test3'  => array('exception' => '1'),
                                                                        'test4'  => array('exception' => '1')
                                                                       ))
                       );
   
    while($DB = mysqli_fetch_assoc($query)) {
       
        if($exception[$DB['Database']]['exception']){
            continue;
        }
        if(!is_dir($path.$argv[1])) {
            system('mkdir '.$path.$argv[1]);
        }
        if(!is_dir($path.$argv[1].'/'.$DB['Database'])) {
            system('mkdir '.$path.$argv[1].'/'.$DB['Database']);
        }
       
        mysqli_query($connect, 'USE '.$DB['Database']);
        $q = mysqli_query($connect, 'SHOW TABLES');
        while($table = mysqli_fetch_array($q)) {
            if($exception[$DB['Database']]['tables'][$table[0]]['exception']){
                continue;
            }
            system('mysqldump -uroot '.$DB['Database'].' '.$table[0].' > '.$path.$argv[1].'/'.$DB['Database'].'/'.$table[0].'.sql');
        }
    }