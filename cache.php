<?php
    class Cache {  
        private $cache_path;
        private $cache_expire;


        /**
         *  检查缓存文件是否存在
         * @param int $exp_time 缓存时间
         * @param string $path  缓存目录
         */
        public function __construct($path="./cache/",$exp_time=3600){

            is_dir($path) || mkdir($path,'0755',true);

            $this->cache_expire=$exp_time;  
            $this->cache_path=$path;  
        }

        /**
         *  返回文件的路劲
         * @param $key
         * @return string
         */
        private function fileName($key){
            return $this->cache_path.md5($key);  
        }

        /**
         *  写入缓存
         * @param $key
         * @param $data
         * @return bool
         */
        public function put($key, $data){  
            $values = serialize($data);  
            $filename = $this->fileName($key);  
            $file = fopen($filename, 'w');  
            if ($file){
                fwrite($file, $values);  
                fclose($file);  
            }  
            else return false;  
        }

        /**
         *  读取缓存
         * @param $key
         * @return bool|mixed
         */
        public function get($key){  
            $filename = $this->fileName($key);  
            if (!file_exists($filename) || !is_readable($filename)){
                return false;  
            }  
            if ( time() < (filemtime($filename) + $this->cache_expire) ) {
                $file = fopen($filename, "r");
                if ($file){
                    $data = fread($file, filesize($filename));  
                    fclose($file);  
                    return unserialize($data);
                }  
                else return false;  
            }  
            else return false;
        }  
    } 

$cache = new Cache('./data/');
$cache->put("age",12);
echo $cache->get('age');
?>