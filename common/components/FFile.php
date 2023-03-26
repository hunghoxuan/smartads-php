<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace common\components;

use backend\assets\CustomAsset;
use common\components\filesystem\FtpFilesystem;
use common\components\filesystem\LocalFilesystem;
use common\components\filesystem\SftpFilesystem;
use Faker\Provider\DateTime;
use League\Flysystem\AdapterInterface;
use Psy\Exception\ErrorException;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\BaseInflector;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use ZipArchive;

/**
 * Filesystem
 *
 * @method \League\Flysystem\FilesystemInterface addPlugin(\League\Flysystem\PluginInterface $plugin)
 * @method void assertAbsent(string $path)
 * @method void assertPresent(string $path)
 * @method \League\Flysystem\Handler get(string $path, \League\Flysystem\Handler $handler = null)
 * @method \League\Flysystem\AdapterInterface getAdapter()
 * @method \League\Flysystem\Config getConfig()
 * @method array|false getMetadata(string $path)
 * @method string|false getMimetype(string $path)
 * @method integer|false getSize(string $path)
 * @method integer|false getTimestamp(string $path)
 * @method string|false getVisibility(string $path)
 * @method array getWithMetadata(string $path, array $metadata)
 * @method boolean has(string $path)
 * @method array listPaths(string $path = '', boolean $recursive = false)
 * @method array listWith(array $keys = [], $directory = '', $recursive = false)
 * @method boolean put(string $path, string $contents, array $config = [])
 * @method boolean putStream(string $path, resource $resource, array $config = [])
 * @method string|false read(string $path)
 * @method string|false readAndDelete(string $path)
 * @method resource|false readStream(string $path)
 * @method boolean rename(string $path, string $newpath)
 * @method boolean setVisibility(string $path, string $visibility)
 * @method boolean update(string $path, string $contents, array $config = [])
 * @method boolean updateStream(string $path, resource $resource, array $config = [])
 * @method boolean writeStream(string $path, resource $resource, array $config = [])
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class FFile extends FSystem
{

    const TYPE_LOCAL = '';
    const TYPE_FTP = 'ftp';
    const TYPE_sFTP = 'sFtp';
    const TYPE_GOOGLE_DRIVE = 'google';
    const TYPE_AWS = 'aws';

    /**
     * @var \League\Flysystem\Config|array|string|null
     */
    public $config;
    /**
     * @var string|null
     */
    public $cache;
    /**
     * @var string
     */
    public $cacheKey = 'flysystem';
    /**
     * @var integer
     */
    public $cacheDuration = 3600;
    /**
     * @var string|null
     */
    public $replica;
    public $path;
    public $type;
    public $host;
    public $port;
    public $username;
    public $password;
    public $ssl;
    public $timeout;
    public $root;
    public $permPrivate;
    public $permPublic;
    public $passive;
    public $transferMode;
    public $systemType;
    public $ignorePassiveAddress;
    public $resourceManually;
    public $utf8;
    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    protected $filesystem;

    /* List of File Types */
    public static function getFileTypeArray($extension = '')
    {
        $fileTypes['swf'] = 'application/x-shockwave-flash';
        $fileTypes['pdf'] = 'application/pdf';
        $fileTypes['exe'] = 'application/octet-stream';
        $fileTypes['zip'] = 'application/zip';
        $fileTypes['doc'] = 'application/msword';
        $fileTypes['xls'] = 'application/vnd.ms-excel';
        $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
        $fileTypes['gif'] = 'image/gif';
        $fileTypes['png'] = 'image/png';
        $fileTypes['jpeg'] = 'image/jpg';
        $fileTypes['jpg'] = 'image/jpg';
        $fileTypes['rar'] = 'application/rar';

        $fileTypes['ra'] = 'audio/x-pn-realaudio';
        $fileTypes['ram'] = 'audio/x-pn-realaudio';
        $fileTypes['ogg'] = 'audio/x-pn-realaudio';

        $fileTypes['wav'] = 'video/x-msvideo';
        $fileTypes['wmv'] = 'video/x-msvideo';
        $fileTypes['avi'] = 'video/x-msvideo';
        $fileTypes['asf'] = 'video/x-msvideo';
        $fileTypes['divx'] = 'video/x-msvideo';

        $fileTypes['mp3'] = 'audio/mpeg';
        $fileTypes['mp4'] = 'audio/mpeg';
        $fileTypes['mpeg'] = 'video/mpeg';
        $fileTypes['mpg'] = 'video/mpeg';
        $fileTypes['mpe'] = 'video/mpeg';
        $fileTypes['mov'] = 'video/quicktime';
        $fileTypes['swf'] = 'video/quicktime';
        $fileTypes['3gp'] = 'video/quicktime';
        $fileTypes['m4a'] = 'video/quicktime';
        $fileTypes['aac'] = 'video/quicktime';
        $fileTypes['m3u'] = 'video/quicktime';
        if (empty($extension))
            return $fileTypes;
        else if (key_exists($extension, $fileTypes))
            return $fileTypes[$extension];
        else
            return $extension;
    }

    //Files: https://github.com/creocoder/yii2-flysystem
    public static function createFile($filename, $content = null, $override = true, $fs = null)
    {
        // neu khong ton tai file va filename khong bat dau voi root folder va filename khong bat dau voi ".." va filename khong ton tai ":" thi tien hanh lay lai full path
        if (!is_file($filename) && !StringHelper::startsWith($filename, FHtml::getRootFolder()) && !StringHelper::startsWith($filename, '..') && strpos($filename, ':') < 0)
            $filename = FFile::getFullFileName($filename);

        $fs = self::FileSystem($fs);
        $exists = $fs->has($filename);
        if ($exists) {
            if ($override) {
                $fs->update($filename, $content);
                return true;
            } else
                return false;
        } else {

            if (!StringHelper::startsWith($filename, '..')) {
                $parts = explode(DS, $filename);
                $file = array_pop($parts);
                $dir = '';
                foreach ($parts as $part) {
                    $part = strpos($part, ':') == 1 ? $part : "/$part";
                    if (!is_dir($dir .= "$part")) {
                        if (!self::createDir($dir . DS)) // does not have permission to create Dir ==> quit
                            return false;
                    }
                }

                try {
                    if (is_file($filename) && !is_writable($filename)) {
                        FError::addError("Permission denied: $filename");
                        return false;
                    }

                    $myfile = fopen($filename, "w");
                    fwrite($myfile, $content);
                    fclose($myfile);
                    return true;
                } catch (Exception $ex) {
                    FError::addError($ex);
                    return false;
                }

            } else {
                if (is_file($filename) && !is_writable($filename)) {
                    FError::addError("Permission denied: $filename");
                    return false;
                }

                $fs->write($filename, $content);
                return true;
            }
        }
    }

    public static function write($filename, $content = null, $override = true, $fs = null)
    {
        return self::createFile($filename, $content, $override, $fs);
    }

    //Files: https://github.com/creocoder/yii2-flysystem

    /**
     * @param null $type
     * @param string $host
     * @param string $username
     * @param string $password
     * @param null $port
     * @param string $rootFolder
     * @param bool $ssl
     * @param int $timeout
     * @return FtpFilesystem|LocalFilesystem|SftpFilesystem|mixed
     */
    public static function FileSystem($type = null, $host = '', $username = '', $password = '', $port = null, $rootFolder = '/', $ssl = true, $timeout = 60)
    {
        if (isset($type) && is_object($type)) {
            return $type;
        }

        if (empty($rootFolder))
            $rootFolder = DS;

        if (is_string($type) && !empty($type)) {
            if ($type == self::TYPE_FTP || $port == 21) {
                $fs = self::FTP($host, $username, $password, $port, $rootFolder, $ssl, $timeout);
                return $fs;
            } else if ($type == self::TYPE_sFTP || $port == 22) {
                $fs = self::sFTP($host, $username, $password, $port, $rootFolder, $ssl, $timeout);
                return $fs;
            }
        }

        if (isset(Yii::$app->fs))
            return Yii::$app->fs;

        return self::Local();
    }

    //Files: https://github.com/creocoder/yii2-flysystem

    public static function FTP($host = '', $username = '', $password = '', $port = null, $rootFolder = '/', $ssl = true, $timeout = 60)
    {
        $fs = isset(Yii::$app->ftpFs) ? Yii::$app->ftpFs : new FtpFilesystem();

        if (!empty($fs->host))
            return $fs;

        // 'port' => 21,
        // 'username' => 'your-username',
        // 'password' => 'your-password',
        // 'ssl' => true,
        // 'timeout' => 60,
        // 'root' => '/path/to/root',
        // 'permPrivate' => 0700,
        // 'permPublic' => 0744,
        // 'passive' => false,
        // 'transferMode' => FTP_TEXT,
        if (!isset($host))
            $host = FHtml::getApplicationConfig('FTP HOST');
        if (!isset($username))
            $username = FHtml::getApplicationConfig('FTP USERNAME');
        if (!isset($password))
            $password = FHtml::getApplicationConfig('FTP PASSWORD');
        if (!isset($port))
            $port = FHtml::getApplicationConfig('FTP PORT', 21);

        $fs->host = $host;
        $fs->port = $port;
        $fs->username = $username;
        $fs->password = $password;
        $fs->ssl = $ssl;
        $fs->timeout = $timeout;
        $fs->root = $rootFolder;
        $fs->init();
        return $fs;
    }

    public static function sFTP($host = '', $username = '', $password = '', $port = null, $rootFolder = '/', $ssl = true, $timeout = 60)
    {
        $fs = isset(Yii::$app->sftpFs) ? Yii::$app->sftpFs : new SftpFilesystem();

        if (!empty($fs->host))
            return $fs;

        if (!isset($host))
            $host = FHtml::getApplicationConfig('FTP HOST');
        if (!isset($username))
            $username = FHtml::getApplicationConfig('FTP USERNAME');
        if (!isset($password))
            $password = FHtml::getApplicationConfig('FTP PASSWORD');
        if (!isset($port))
            $port = FHtml::getApplicationConfig('FTP PORT', 22);

        $fs->host = $host;
        $fs->port = $port;
        $fs->username = $username;
        $fs->password = $password;
        $fs->timeout = $timeout;
        $fs->root = $rootFolder;
        $fs->init();
        return $fs;
    }

    public static function Local($path = '@backend/..')
    {
        $fs = new LocalFilesystem();
        $fs->path = $path;
        $fs->init();
        return $fs;
    }

    public static function readFile($filename, $fs = null)
    {
        if (is_file($filename)) {
            return file_get_contents($filename);
        }

        $fs = self::FileSystem($fs);
        $exists = $fs->has($filename);
        if ($exists) {
            $contents = Yii::$app->fs->read($filename);
            return $contents;
        } else {

            return '';
        }
    }

    public static function delete($filename, $fs = null)
    {
        if (is_dir($filename))
            self::deleteDir($filename, $fs);
        else
            return self::deleteFile($filename, $fs);
    }

    public static function deleteFile($filename, $fs = null)
    {
        if (!isset($fs) || is_string($fs)) {
            if (is_string($fs))
                $folder = $fs;
            else
                $folder = '';
            if (is_file($folder . $filename)) {
                unlink($folder . $filename);
            }
            return false;
        }
        $fs = self::FileSystem($fs);
        $exists = $fs->has($filename);
        if ($exists) {
            return $fs->delete($filename);
        } else {
            return false;
        }
    }

    public static function getImageFolder($model)
    {
        if (is_object($model))
            $model = FModel::getTableName($model);

        return str_replace('_', '-', $model);
    }

    public static function getUploadFolder($model = '', $fs = null, $autoCreate = false)
    {
        if (is_object($model))
            return self::getImageFolder($model);

        $folder = FHtml::getApplicationUploadFolder($model);

        if ($autoCreate)
            self::createDir($folder, $fs);

        return $folder;
    }

    public static function getUploadFileName($folder = '', $file_name = '', $autoCreate = false)
    {
        $folder = self::getUploadFolder($folder, null, $autoCreate);

        //get only file name if folder is given
        $file_arr = explode(DS, $file_name);
        $file_name = $file_arr[count($file_arr) - 1];

        $file_name = FHtml::strReplace(strtolower($folder . DS . $file_name), ['\\' => DS, '//' => DS, ' ' => '_']);

        return $file_name;
    }

    public static function saveFile($file = null, $old_filename = '', $folder = '', $new_filename = '')
    {
        return self::saveUploadedFile($file, $folder, $new_filename);
    }

    public static function saveUploadedFile($file = null, $folder = '', $file_name = '')
    {
        if (empty($folder)) {
            $folder = 'backend' . DS . 'web' . DS . 'upload' . DS . 'www';
        }

        if (is_string($file)) {
            if (empty($file_name)) {
                $file_name = StringHelper::basename($file);
            }

            $file_name_full = self::getUploadFileName($folder, $file_name, true);
            if (!empty($file) && !empty($file_name_full) && move_uploaded_file($file, $file_name_full)) {
                return $file_name_full;
            }

            return false;

        } elseif (is_object($file)) {
            if ($file instanceof UploadedFile) {
                if (empty($file_name))
                    $file_name = $file->name;
                $file_name_full = self::getUploadFileName($folder, $file_name, true);

                if (!is_file($file->tempName)) {
                    FHtml::addError("Temp File does not existed. " . $file->tempName);
                    return false;
                }
                if ($file->saveAs($file_name_full)) {
                    return $file_name;
                }
            } else if ($file instanceof ActiveRecord && !empty($_FILES)) { //saveUploadFile($model, $upload_field)
                $fields = array_keys($_FILES);
                $field = $fields[0];
                if (is_array($folder)) {
                    foreach ($folder as $field1) {
                        if (in_array($field1, $fields)) {
                            $field = $field1; break;
                        }
                    }
                } else
                    $field = $folder;

                $file_path = FHtml::getApplicationUploadFolder($file);

                $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                if (empty($file_name))
                    $file_name = FHtml::getFriendlyFileName($_FILES[$field]['name']) . "_$field.$ext";

                $image_path = $file_path . DS . $file_name;

                if (!is_dir($file_path))
                {
                    FError::addError("Error Upload. Folder does not exists: $file_path");
                }

                $upload = move_uploaded_file($_FILES[$field]['tmp_name'], $image_path);
                if ($upload)
                    return $file_name;
            }

            return false;

        } else {
            if (!empty($_FILES)) {
                foreach ($_FILES as $key => $array) {
                    if (empty($folder)) {
                        $folder = strtolower(BaseInflector::camel2id($key));
                    }
                    $file = $array;
                    break;
                }
            } else {
                $file = [];
            }

            if (is_array($file) && !empty($file) && key_exists('tmp_name', $file)) {
                $names = $file['name'];
                $tmps = $file['tmp_name'];
                $result = [];
                $result1 = [];
                foreach ($tmps as $key => $value) {
                    $file_name_full = (is_array($file_name) && key_exists($key, $file_name)) ? $file_name[$key] : (key_exists($key, $names) ? $names[$key] : '');
                    $file_name_tmp = $value;
                    $file_name_full = self::getUploadFileName($folder, $file_name_full, true);

                    if (move_uploaded_file($file_name_tmp, $file_name_full)) {
                        $result[] = $file_name_full;
                    }
                }

                return $result;
            }

            if (empty($file_name)) {
                FError::addError("Could not save uploaded file: $folder/$file_name.", FHtml::encode($file));
                return false;
            }

            return false;
        }
    }


    public static function zipFileDirect($filename, $deleteAfterZip = false)
    {
        // if (is_file($filename))
        //     return false;

        $zip = new ZipArchive ();
        $file_name = $filename . '.zip';
        if ($zip->open($file_name, ZipArchive::CREATE) === TRUE) {
            $zip->addFile(self::getFullPath() . $filename, basename($filename));
            $zip->close();

            // if ($deleteAfterZip)
            //     @unlink ( $filename );
        }
        return true;
    }

    public static function zipFile($directory, $filename = '', $included = [], $deleteAfterZip = false)
    {
        FSystem::execRemoteUrl('api/zipFile', ['folder' => $directory, 'save_to' => $filename, 'delete_after_zip' => $deleteAfterZip], false);
    }

    public static function zipFolder($directory, $filename = '', $included = [], $deleteAfterZip = false)
    {
        FSystem::execRemoteUrl('api/zipFile', ['folder' => $directory, 'save_to' => $filename, 'delete_after_zip' => $deleteAfterZip], false);

    }

    public static function zipFolderDirect($directory, $filename = '', $included = [], $deleteAfterZip = false)
    {

        if (is_array($directory)) {
            $src_child = $directory[1];
            $directory = $directory[0];
        } else {
            $src_child = ['*'];
        }

        if (!is_dir($directory))
            return false;

        if (empty($filename))
            $filename = date('Y-m-d');

        $close_zip = true;
        if (is_string($filename)) {
            $zip = new ZipArchive();
            $filename = FFile::getFullFileName($filename);
            $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        } else if (is_object($filename)) {
            $zip = $filename;
            $filename = $zip->filename;
            $close_zip = false;

        } else {
            return;
        }

        foreach ($src_child as $child) {
            $rootPath = realpath($directory);

            if ($child == '' || $child == '.') {
                $rootPath = realpath($directory);
                $recursive = false;
            } else if ($child == '*') {
                $rootPath = realpath($directory);
                $recursive = true;
            } else {
                $rootPath = realpath($directory) . DS . "$child";
                if (!is_dir($rootPath))
                    continue;
                $recursive = true;
            }

            $files = self::listFiles($rootPath, $recursive, $included);
            $result = [];
            foreach ($files as $file => $name) {
                // Get real and relative path for current file
                $filePath = self::getFullFileName($file);
                $relativePath = substr($filePath, strlen($directory)); // before strlen($directory) + 1
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        if ($close_zip) {
            $zip->close();

            if ($deleteAfterZip)
                self::deleteDir($directory);
        }

        return true;
    }

    public static function getObjectDir($object)
    {
        if (empty($object)) {
            return '';
        }
        $child = new \ReflectionClass($object);
        return dirname($child->getFileName());
    }

    //Files: https://github.com/creocoder/yii2-flysystem

    /**
     * @param      $path
     * @param null $fs
     */
    public static function deleteDir($path, $fs = null)
    {
        if (!is_dir($path))
            return;

        if (isset($fs)) {
            $fs = self::FileSystem($fs);
            $fs->deleteDir($path);
        } else {
            if (substr($path, strlen($path) - 1, 1) != DS) {
                $path .= DS;
            }
            $files = glob($path . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            @rmdir($path);
        }
    }

    public static function unzip($file_name, $extractTo = '', $deleteAfterZip = false)
    {
        if (file_exists($file_name)) {
            if (empty($extractTo)) {
                $extractTo = dirname($file_name);
            }

            $zip = new ZipArchive ();
            if ($zip->open($file_name)) {
                $zip->extractTo($extractTo);
                $zip->close();
                if ($deleteAfterZip)
                    @unlink($file_name);
            }
            return true;
        }
        return false;
    }

    public static function mkdir($path)
    {
        return self::createDir($path);
    }

    public static function createDir($path, $fs = null)
    {
        $path = FHtml::strReplace($path, ["\\" => DS, "/" => DS]);

        if (!isset($fs)) {
            if (!is_dir($path)) {
                $folder = $path;
                try {
                    if ($folder != '.' && $folder != DS) {
                        static::createDir(dirname($folder));
                    }
                    if (file_exists($folder) || is_dir($folder)) {
                        return $folder;
                    }

                    if (is_writable(dirname($folder)) && mkdir($folder, DEFAULT_FOLDER_PERMISSION, true))
                        return $folder;
                    else {
                        //echo FError::addError("Permission denied or missing: $path. Please create this folder and/or set permission 0775");
                        return false;
                    }
                } catch (\yii\base\ErrorException $ex) {
                    //echo FError::addError($ex, $path);
                    return false;
                }
            }
            return false;
        }
        $fs = self::FileSystem($fs);
        $fs->createDir($path);
        return $path;
    }

    public static function getFullPath($folder = '', $autoCreateFolder = false)
    {
        $folder = str_replace('{application_id}', FHtml::currentApplicationId(), $folder);
        $folder = str_replace('{date}', date('Y.m.d'), $folder);
        $folder = str_replace('{module}', date('Y.m.d'), $folder);

        $folder = FFile::getFullFileName($folder . DS);

        if (!is_dir($folder) && $autoCreateFolder) {
            return FHtml::createDir($folder);
        }

        return $folder;
    }


    public static function fileReadIterator($path)
    {
        $handle = fopen($path, "r");

        while (!feof($handle)) {
            yield trim(fgets($handle));
        }

        fclose($handle);
    }

    public static function fileReplace($FilePath, $pairs = [])
    {
        return static::replaceFileContent($FilePath, $pairs);
    }

    public static function replaceFileContent($FilePath, $pairs = [])
    {
//        $iterator = static::fileReadIterator($FilePath);
//
//        $buffer = "";
//
//        foreach ($iterator as $iteration) {
//            preg_match("/\n{3}/", $buffer, $matches);
//
//            if (count($matches)) {
//                print ".";
//                $buffer = "";
//            } else {
//                $buffer .= FHtml::strReplace($iteration, $pairs) . PHP_EOL;
//            }
//        }
//
//        static::write($FilePath, $buffer);
//        return;

        $Result = null;
        if (file_exists($FilePath) === TRUE) {
            if (is_writeable($FilePath)) {
                try {
                    $FileContent = file_get_contents($FilePath);
                    $FileContent = FHtml::strReplace($FileContent, $pairs);
                    if (file_put_contents($FilePath, $FileContent) > 0) {
                        return true;
                    } else {
                        return 'Error while writing file';
                    }
                } catch (Exception $e) {
                    return 'Error : ' . $e;
                }
            } else {
                return 'File ' . $FilePath . ' is not writable !';
            }
        } else {
            return 'File ' . $FilePath . ' does not exist !';
        }
    }

    /**
     * Recursively copy files from one directory to another
     *
     * @param String $src - Source of files being moved
     * @param String $dest - Destination of files being moved
     * @param Boolean $deleteFileZipAfterCopy - Delete file zip after copy file to new dest
     */
    public static function copy($src, $dest, $included = [], $deleteFileZipAfterCopy = true)
    {
        if (is_file($src)) {
            copy($src, $dest);
            return;
        }

        $src_path = is_array($src) ? $src[0] : $src;
        $src_path = self::getFullFileName($src_path);
        // If source is not a directory stop processing
        if (!is_dir($src_path)) return false;

        $zip_file = '';
        if (is_string($dest))
            $dest_arr = [$dest];
        else if (is_array($dest))
            $dest_arr = $dest;
        else
            return false;

        foreach ($dest_arr as $dest) {
            $dest = self::getFullPath($dest);
            // If the destination directory does not exist create it
            if (!is_dir($dest)) {
                if (!self::createDir($dest)) {
                    // If the destination directory could not be created stop processing
                    continue;
                }
            }

            if (empty($zip_file)) {
                $zip_file = "$dest" . DS . str_replace([":", "/", "\\"], "_", $src_path) . date('Y-m-d') . '.zip';
                $basename = basename($src_path);
                if (is_file($basename)) {
                    $zip = self::zipFile($src_path);
                } else {
                    //echo "$src_path => $zip_file"; die;
                    $zip = self::zipFolderDirect($src_path, $zip_file, $included);
                }

                self::unzip($zip_file, '', $deleteFileZipAfterCopy);
            } else {
                $zip_file1 = "$dest" . DS . str_replace([":", "/", "\\"], "_", $src_path) . date('Y-m-d') . '.zip';
                //echo "$zip_file => $zip_file1;"; die;

                copy($zip_file, $zip_file1);
                self::unzip($zip_file1, '', $deleteFileZipAfterCopy);
            }
        }

        return true;
    }

    public static function parseFile($file)
    {
        return static::getFileInfo($file);
    }

    public static function listFiles($path, $recursive = true, $included = [], $excluded = [], $showFullPath = true)
    {
        if (!is_array($path))
            $pathArray = [$path];
        else
            $pathArray = $path;

        $result = [];
        foreach ($pathArray as $path) {
            $path = realpath($path);
            if (empty($path)) {
                continue;
            }
            $files = [];
            $i = new \DirectoryIterator($path);
            foreach ($i as $i => $f) {
                if (FHtml::strpos_array($f->getRealPath(), $included) === -1 && !empty($included)) {
                    continue;
                }

                if (FHtml::strpos_array($f->getRealPath(), $excluded) > -1 && !empty($excluded)) {
                    continue;
                }

                if ($f->isFile()) {
                    $path_root = str_replace("/", "\\", self::getFullPath());
                    $realPath = str_replace($path_root, "", $f->getRealPath());
                    $key = $realPath;

                    if ($showFullPath === true)
                        $value = $realPath;
                    else if ($showFullPath === false)
                        $value = $f->getFilename();
                    else if (is_string($showFullPath))
                        $value = FHtml::strReplace($showFullPath, ['{size}' => $f->getSize(), '{name}' => $f->getFilename(), '{extension}' => $f->getExtension()]);
                    else if (is_array($showFullPath))
                        $value = [
                            'file_name' => $f->getFilename(),
                            'extension' => $f->getExtension(),
                            'name' => str_replace('.' . $f->getExtension(), '', $f->getFilename()),
                            'type' => $f->getType(),
                            'permission' => $f->getPerms(),
                            'basename' => $f->getBasename(),
                            'filename' => $f->getFilename(),
                            'dirname' => $f->getPath(),
                            'path' => $f->getPath(),
                            'real_path' => $f->getRealPath(),
                            'mtime' => $f->getMTime(),
                            'ctime' => $f->getCTime(),
                            'owner' => $f->getOwner(),
                            'size' => $f->getSize()
                        ];
                    else
                        $value = $f;

                    $files = array_merge($files, [$key => $value]);
                    // $files = array_merge($files, [$f->getBasename() => $f->getRealPath()]);
                } else if (!$f->isDot() && $f->isDir() && $recursive) {
                    $files = array_merge($files, self::listFiles($f->getRealPath(), $recursive, $included, $excluded));
                }
            }
            $result = array_merge($result, $files);
        }
        return $result;
    }

    public static function listFilesNames($path, $recursive = true, $included = [], $excluded = [])
    {
        return static::listFiles($path, $recursive, $included, $excluded, false);
    }

    public static function listFilesObjects($path, $recursive = true, $included = [], $excluded = [])
    {
        return static::listFiles($path, $recursive, $included, $excluded, null);
    }

    public static function listFilesArrays($path, $recursive = true, $included = [], $excluded = [])
    {
        return static::listFiles($path, $recursive, $included, $excluded, []);
    }

    public static function listFilesInfos($path, $recursive = true, $included = [], $excluded = [])
    {
        return static::listFiles($path, $recursive, $included, $excluded, []);
    }

    public static function listFolders($path = '', $recursive = true, $included = [], $excluded = [],
                                       &$folders = [])
    {
        if (empty($path))
            $path = FHtml::getRootFolder();

        $path =  str_replace("//", DS, "$path");
        if (!is_dir($path)) {
            FHtml::addError("Path does not exist: $path");
            return [];
        }

        $i = new \DirectoryIterator($path);
        foreach ($i as $i => $f) {
            if (FHtml::strpos_array($f->getRealPath(), $included) === -1 && !empty($included)) {
                continue;
            }

            if (FHtml::strpos_array($f->getRealPath(), $excluded) > -1 && !empty($excluded)) {
                continue;
            }

            if ($f->isDir() && !$f->isDot() && !$recursive) {
                $folders = array_merge($folders, [$f->getBasename() => $f->getRealPath()]);
            } elseif ($f->isDir() && !$f->isDot() && !$f->isFile() && $recursive) {

                $path_root = str_replace("/", "\\", self::getFullPath());
                $realPath = str_replace($path_root, "", $f->getRealPath());
                $folders = array_merge($folders, [$realPath => $realPath]);
                self::listFolders($f->getRealPath(), $recursive, $included, $excluded, $folders);
            }
        }
        return $folders;
    }

    public static function rootFolders()
    {
        $path_root = self::getPathRoot();
        $folders = self::listFolders($path_root, false);
        $arr_id = array_values($folders);
        $arr_name = array_keys($folders);
        $folders = array_combine($arr_id, $arr_name);
        return $folders;
    }

    public static function getPathRoot()
    {
        $path_root = self::getFullPath();
        $path_root = trim($path_root, '/');
        $path_root = trim($path_root, '\\');
        $path_root = trim($path_root, basename($path_root));
        return $path_root;
    }

    public static function listContents($path = '', $recursive = false, $extensions = [], $fs = null)
    {
        $fs = self::FileSystem($fs);
        $contents = $fs->listContents($path, $recursive);
        $result = [];
        $i = 0;

        foreach ($contents as $content) {
            $extension = FHtml::getFieldValue($content, 'extension');

            if (!empty($extensions) && !in_array($extension, $extensions))
                continue;

            $i += 1;
            if ($extension == 'mkv')
                $extension = 'webm';

            $result[] = [
                'url' => FHtml::getFileURLForAPI($content['path']),
                'file' => FHtml::getRootFolder() . DS . $content['path'],
                'name' => FHtml::getFieldValue($content, 'basename'),
                'extension' => $extension,
                'size' => FHtml::getFieldValue($content, 'size'),
                'timestamp' => FHtml::getFieldValue($content, 'timestamp'),

            ];
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {

    }

    /**
     * @return AdapterInterface
     */
    public function prepareAdapter()
    {
        return null;
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->filesystem, $method], $parameters);
    }

    /*
           Parameters: downloadFile(File Location, File Name,
           max speed, is streaming
           If streaming - videos will show as videos, images as images
           instead of download prompt
          */
    public static function downloadFileAs($fileLocation, $fileName, $maxSpeed = 100, $doStream = false)
    {
        if (connection_status() != 0)
            return (false);
        //    in some old versions this can be pereferable to get extention
        //    $extension = strtolower(end(explode('.', $fileName)));
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $contentType = self::getFileTypeArray($extension);
        header("Cache-Control: public");
        header("Content-Transfer-Encoding: binary\n");
        header('Content-Type: $contentType');

        $contentDisposition = 'attachment';

        if ($doStream == true) {
            /* extensions to stream */
            $array_listen = array('mp3', 'm3u', 'm4a', 'mid', 'ogg', 'ra', 'ram', 'wm',
                'wav', 'wma', 'aac', '3gp', 'avi', 'mov', 'mp4', 'mpeg', 'mpg', 'swf', 'wmv', 'divx', 'asf');
            if (in_array($extension, $array_listen)) {
                $contentDisposition = 'inline';
            }
        }


        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
            header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
        } else {
            header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
        }

        header("Accept-Ranges: bytes");
        $range = 0;
        $size = filesize($fileLocation);

        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
            str_replace($range, "-", $range);
            $size2 = $size - 1;
            $new_length = $size - $range;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range$size2/$size");
        } else {
            $size2 = $size - 1;
            header("Content-Range: bytes 0-$size2/$size");
            header("Content-Length: " . $size);
        }

        if ($size == 0) {
            die('Zero byte file! Aborting download');
        }
        set_magic_quotes_runtime(0);
        $fp = fopen("$fileLocation", "rb");

        fseek($fp, $range);

        while (!feof($fp) and (connection_status() == 0)) {
            set_time_limit(0);
            print(fread($fp, 1024 * $maxSpeed));
            flush();
            ob_flush();
            sleep(1);
        }
        fclose($fp);

        return ((connection_status() == 0) and !connection_aborted());
    }

    public static function downloadFile($file_path = '', $is_attachment = true, $file_name = '')
    {
        if (!is_string($file_path))
            return false;

        $path_parts = pathinfo($file_path);

        $file_ext = $path_parts['extension'];
        if (empty($file_name)) {
            $file_name = $path_parts['basename'];
            $file_name = str_replace(".$file_ext", '', $file_name);
        }
        $application_id = FHtml::currentApplicationFolder();
        //case 1: simple download
        if (is_file($file_path) && $is_attachment !== true) {
            $content = file_get_contents($file_path);
            if (is_dir($is_attachment))
                $file_save = $is_attachment . DS . "$file_name.$file_ext";
            else if (is_file($is_attachment))
                $file_save = $is_attachment;
            else
                $file_save = FHtml::getRootFolder() . DS . "applications" . DS . "$application_id" . DS . "download" . DS . "$file_name.$file_ext";

//            if (file_exists($file_path)) {
//                header('Content-Description: File Transfer');
//                header('Content-Type: application/octet-stream');
//                header('Content-Disposition: attachment; filename='.basename($file_path));
//                header('Content-Transfer-Encoding: binary');
//                header('Expires: 0');
//                header('Cache-Control: must-revalidate');
//                header('Pragma: public');
//                header('Content-Length: ' . filesize($file_path));
//                ob_clean();
//                flush();
//                readfile($file_path);
//                exit;
//            }

            FFile::write($file_save, $content);
            exit;
            return;
        }

        //case 2: download remote file to server
        if (StringHelper::startsWith($file_path, 'http') && $is_attachment !== true) {
            if (is_dir($is_attachment))
                $file_save = $is_attachment . DS . "$file_name.$file_ext";
            else if (is_file($is_attachment))
                $file_save = $is_attachment;
            else
                $file_save = FHtml::getRootFolder() .  DS . "applications" . DS . "$application_id" . DS . "download" . DS . "$file_name.$file_ext";


            $file = fopen($file_path, 'rb');
            if ($file) {
                if (is_file($file_save) && !is_writable($file_save)) {
                    FError::addError("Permission denied: $file_save");
                    return false;
                }

                $newf = fopen($file_save, 'wb');
                if ($newf) {
                    while (!feof($file)) {
                        fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                    }
                }
            }
            if ($file) {
                fclose($file);
            }
            if ($newf) {
                fclose($newf);
            }
            return false;
        }

        //case 3: download file with resumable file
        if (empty($file_path))
            $file_path = FHtml::getRequestParam(['file', 'file_path', 'download_file']);

        // hide notices
        @ini_set('error_reporting', E_ALL & ~E_NOTICE);

//- turn off compression on the server
        @apache_setenv('no-gzip', 1);
        @ini_set('zlib.output_compression', 'Off');

        if (empty($file_path)) {
            header("HTTP/1.0 400 Bad Request");
            exit;
        }

// allow a file to be streamed instead of sent as an attachment
        $is_attachment = isset($is_attachment) ? $is_attachment : FHtml::getRequestParam(['is_attachment'], true);

// make sure the file exists
        if (is_file($file_path)) {
            $file_size = filesize($file_path);
            $file = @fopen($file_path, "rb");
            if ($file) {
                // set the headers, prevent caching
                header("Pragma: public");
                header("Expires: -1");
                header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
                header("Content-Disposition: attachment; filename=\"$file_name\"");

                // set appropriate headers for attachment or streamed file
                if ($is_attachment)
                    header("Content-Disposition: attachment; filename=\"$file_name\"");
                else
                    header('Content-Disposition: inline;');

                // set the mime type based on extension, add yours if needed.
                $ctype_default = "application/octet-stream";
                $content_types = self::getFileTypeArray();
                $ctype = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
                header("Content-Type: " . $ctype);

                //check if http_range is sent by browser (or download manager)
                if (isset($_SERVER['HTTP_RANGE'])) {
                    list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                    if ($size_unit == 'bytes') {
                        //multiple ranges could be specified at the same time, but for simplicity only serve the first range
                        //http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
                        list($range, $extra_ranges) = explode(',', $range_orig, 2);
                    } else {
                        $range = '';
                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        exit;
                    }
                } else {
                    $range = '';
                }

                //figure out download piece from range (if set)
                list($seek_start, $seek_end) = explode('-', $range, 2);

                //set start and end based on range (if set), else set defaults
                //also check for invalid ranges.
                $seek_end = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)), ($file_size - 1));
                $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)), 0);

                //Only send partial content header if downloading a piece of the file (IE workaround)
                if ($seek_start > 0 || $seek_end < ($file_size - 1)) {
                    header('HTTP/1.1 206 Partial Content');
                    header('Content-Range: bytes ' . $seek_start . '-' . $seek_end . '/' . $file_size);
                    header('Content-Length: ' . ($seek_end - $seek_start + 1));
                } else
                    header("Content-Length: $file_size");

                header('Accept-Ranges: bytes');

                set_time_limit(0);
                fseek($file, $seek_start);

                while (!feof($file)) {
                    print(@fread($file, 1024 * 8));
                    ob_flush();
                    flush();
                    if (connection_status() != 0) {
                        @fclose($file);
                        exit;
                    }
                }

                // file save was a success
                @fclose($file);
                exit;
            } else {
                // file couldn't be opened
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        } else {
            // file does not exist
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }

    public static function getFullFileName($file)
    {
        if (is_file($file) || StringHelper::startsWith($file, 'http:'))
            return $file;

        $base_url = FHtml::currentBaseURL();
        $root = FHtml::getRootFolder();

        $file1 = $file;
        if (strpos($file1, "/backend/web/upload/") > 0)
            $file1 = substr($file1, strpos($file1, "/backend/web/upload/"));
        if (StringHelper::startsWith($file1, $base_url))
            $file1 = str_replace($base_url, $root . DS, $file1);

//        if (ends_with($file, 'no_image.jpg')) {
//            echo "Haha: $file $file1 $base_url $root <br/>";
//        }
        if (!StringHelper::startsWith($file1, $root) && !StringHelper::startsWith($file1, '@'))
            $file1 = "$root" . DS . "$file1";

        $file1 = FHtml::strReplace($file1, ['\\' => DS, '//' => DS, '/' => DS]);


        return $file1;
    }

    public static function getRootFolder()
    {
        return dirname(dirname(__DIR__));
    }

    public static function includeFile($file, $isCached = true)
    {
        $isCached = false; //Moza: no need to get/set from Cached

        $file = FFile::getFullFileName($file);

        if (function_exists('opcache_get_status'))
            $isCached = false; // include file is already cached in opcache --> no need to use Cache

        $result = self::getCache($file);

        if ((!isset($result) || !$isCached) && is_file($file)) {
            $result = include($file);
        }

        if (empty($result) || !is_array($result))
            $result = [];

        if ($isCached)
            self::setCache($file, $result);

        return $result;
    }

    public static function getCache($file)
    {
        return FConfig::Session($file);
    }

    public static function clearCache($file)
    {
        if (function_exists('opcache_reset')) {
            opcache_invalidate($file);
        }

        return FConfig::DestroySession($file);
    }

    public static function setCache($file, $result)
    {
        FConfig::Session($file, $result);
    }

    public static function getFileInfo($model, $field = '')
    {
        $file_size = null;
        $folder = '';
        $full_file = '';
        $extension = '';
        $file = '';
        if (empty($field) && is_string($model))
            $full_file = $model;
        else if (is_object($model) && FHtml::field_exists($model, $field)) {
            $folder = FHtml::getUploadFolder($model);
            $file = FHtml::getFieldValue($model, $field);
            $full_file = FHtml::getFullUploadFolder($folder) . DS . $file;
        } else if (is_string($model) && is_string($field)) {
            $folder = $model;
            $file = $field;
            $full_file = FHtml::getFullUploadFolder($folder) . DS . $file;
        }

        if (is_file($file)) {
            $is_file = true;
            $file_size = filesize($full_file);
        } else {
            $is_file = false;
        }

        return ['error' => !$is_file, 'name' => $file, 'size' => $file_size, 'full_file' => $full_file, 'path' => $folder, 'extension' => $extension];
    }

    public static function getUploadMaxFileSize()
    {
        //select maximum upload size
        $max_upload = self::convertToBytes(ini_get('upload_max_filesize'));
        //select post limit
        $max_post = self::convertToBytes(ini_get('post_max_size'));
        //select memory limit
        $memory_limit = self::convertToBytes(ini_get('memory_limit'));
        // return the smallest of them, this defines the real limit
        return min($max_upload, $max_post, $memory_limit);
    }

    public static function convertToBytes($val)
    {
        $val = strtolower(trim($val));
        $val = trim($val, "\b");
        $last = strtolower($val[strlen($val) - 1]);
        $val = trim($val, "\m\g\k");

        if (!is_numeric($val))
            return $val;

        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    public static function getBytes($val)
    {
        return self::convertToBytes($val);
    }

    public static function showFileSize($val)
    {
        return self::convertToKBytes($val);
    }

    public static function convertToKBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 0) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 0) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 0) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public static function createUploadFileName($model, $image_file, $lower_name = '', $field = '')
    {
        $name = strtolower(str_replace(' ', '-', FHtml::getFieldValue($model, ['name', 'title'])));
        $name = FHtml::toSEOFriendlyString($name);

        return $name . '_' . $lower_name . '_' . $field . '_' . FHtml::getFieldValue($model, ['id']) . '.' . $image_file->extension;
    }


    public static function getImagePath($file, $model_dir = '')
    {
        return FHtml::getFilePath($file, $model_dir, \Globals::NO_IMAGE);

//        $baseUpload = Yii::getAlias('@' . UPLOAD_DIR);
//        if (is_file($baseUpload . DS . $model_dir . DS . $file)) {
//            $image_path = $baseUpload . DS . $model_dir . DS . $file;
//        } else {
//            $image_path = $baseUpload . DS . WWW_DIR . DS . \Globals::NO_IMAGE;
//        }
//        return $image_path;
    }

    public static function saveFiles($files, $folder, $model = null, $autoSave = true)
    {
        if (!isset($files))
            return $model;

        if (is_array($files)) {
            foreach ($files as $file) {
                FHtml::saveFile($file, $file->oldName, $folder);
                if (isset($model) && !empty($file->fieldName) && FHtml::field_exists($model, $file->fieldName))
                    $model[$file->fieldName] = $file->name;
                else
                    $autoSave = false;
            }
        } else {
            $file = $files;
            FHtml::saveFile($file, $file->oldName, $folder);
            if (isset($model) && !empty($file->fieldName) && FHtml::field_exists($model, $file->fieldName))
                $model[$file->fieldName] = $file->name;
            else
                $autoSave = false;
        }

        if (isset($model) && $autoSave) {
            $model->save();
        }

        return $model;
    }

    public static function getImageSize($img_loc)
    {
        $handle = fopen($img_loc, "rb") or die("Invalid file stream.");
        $new_block = NULL;
        if (!feof($handle)) {
            $new_block = fread($handle, 32);
            $i = 0;
            if ($new_block[$i] == "\xFF" && $new_block[$i + 1] == "\xD8" && $new_block[$i + 2] == "\xFF" && $new_block[$i + 3] == "\xE0") {
                $i += 4;
                if ($new_block[$i + 2] == "\x4A" && $new_block[$i + 3] == "\x46" && $new_block[$i + 4] == "\x49" && $new_block[$i + 5] == "\x46" && $new_block[$i + 6] == "\x00") {
                    // Read block size and skip ahead to begin cycling through blocks in search of SOF marker
                    $block_size = unpack("H*", $new_block[$i] . $new_block[$i + 1]);
                    $block_size = hexdec($block_size[1]);
                    while (!feof($handle)) {
                        $i += $block_size;
                        $new_block .= fread($handle, $block_size);
                        if ($new_block[$i] == "\xFF") {
                            // New block detected, check for SOF marker
                            $sof_marker = array("\xC0", "\xC1", "\xC2", "\xC3", "\xC5", "\xC6", "\xC7", "\xC8", "\xC9", "\xCA", "\xCB", "\xCD", "\xCE", "\xCF");
                            if (in_array($new_block[$i + 1], $sof_marker)) {
                                // SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.
                                $size_data = $new_block[$i + 2] . $new_block[$i + 3] . $new_block[$i + 4] . $new_block[$i + 5] . $new_block[$i + 6] . $new_block[$i + 7] . $new_block[$i + 8];
                                $unpacked = unpack("H*", $size_data);
                                $unpacked = $unpacked[1];
                                $height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
                                $width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
                                return array($width, $height);
                            } else {
                                // Skip block marker and read block size
                                $i += 2;
                                $block_size = unpack("H*", $new_block[$i] . $new_block[$i + 1]);
                                $block_size = hexdec($block_size[1]);
                            }
                        } else {
                            return FALSE;
                        }
                    }
                }
            }
        }
        return FALSE;
    }

    public static function baseUploadFolderLink()
    {
        $zone = FHtml::currentZone();
        $baseUrl = self::getBaseUrl();
        if ($zone == FRONTEND) {
            $url = $baseUrl . DS . BACKEND . DS . WEB_DIR . DS . UPLOAD_DIR;
        } else {
            $url = $baseUrl . DS . UPLOAD_DIR;
        }
        return $url;
    }

    /**
     * @param string $model
     * @param bool $is_application
     * @return bool|mixed|string
     */
    public static function getFullUploadFolder($model = '', $is_application = true)
    {
        //$folder can be path or folder name only
        $folder = Yii::getAlias('@' . UPLOAD_DIR);
        if (is_object($model)) {
            $model = FHtml::getImageFolder($model);
        }

        if (strlen($model) != 0) {
            if (is_dir($model)) {
                $folder = $model;
            } else {
                $folder = $folder . DS . $model;
            }
            $folder = self::getApplicationUploadFolder($folder, $is_application);
        }
        return $folder;
    }

    public static function getApplicationUploadFolder($folder = "", $is_application = true)
    {
        if (is_object($folder))
            $folder = FHtml::getImageFolder($folder);

        //$folder can be path or folder name only
        $folder = FHtml::strReplace($folder, ['/' => DS, '\\' => DS]);

        if (is_dir($folder)) { // folder is full path and exist
            $folder = basename($folder);
        } else { //Not found folder or folder is not full
            $pos = strpos($folder, UPLOAD_DIR);
            if ($pos > 0) { // in case: {root}/backend/web/upload/www/flag
                $folder = substr($folder, $pos + strlen(UPLOAD_DIR) + 1); //get www/flag from the path
            }
        }
        $backend_path = BACKEND . DS . WEB_DIR . DS . UPLOAD_DIR;
        if (strpos($folder, $backend_path) === false) {
            $folder = FHtml::getRootFolder() . DS . $backend_path . DS . $folder;
        }
        if ($is_application) {
            $application_folder = FHtml::currentApplicationFolder();
            if (!empty($application_folder)) {
                $application_path = "applications" . DS . $application_folder . DS . UPLOAD_DIR;
                if (strpos($folder, $application_path) === false && strpos($folder, $backend_path) !== false) {
                    $folder = str_replace($backend_path, $application_path, $folder);
                }
            }
        }
        return $folder;
    }

    public static function getApplicationUploadFolderLink($link, $is_application = true)
    {
        if ($is_application) {
            $application_folder = FHtml::currentApplicationFolder();

            if (!empty($application_folder)) {
                $link = str_replace("backend/web/upload", "applications/$application_folder/upload", $link);
            }
        }

        return $link;
    }

    public static function getFilePath($file, $model_dir = '', $default_file = '')
    {
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            return strtolower($file);
        } else {
            $file = strtolower($file);

            $model_dir = str_replace('_', '-', $model_dir);
            if (empty($default_file) && FHtml::is_image($file, false)) {
                $default_file = \Globals::NO_IMAGE;
            }
            $baseUpload = FHtml::getFullUploadFolder($model_dir); // get base upload folder

            $file_path = $baseUpload . DS . $file;

            if (!is_file($file_path)) { //does not existed
                if ($default_file == '') {
                    $file_path = $default_file;
                } else {

                    if (StringHelper::startsWith($model_dir, 'www')) {
                        $files[] = FHtml::getRootFolder() . DS . 'backend' . DS . 'web' . DS . 'upload' . DS . $model_dir . DS . $file;
                        if (!empty($default_file)) {
                            $files[] = FHtml::getRootFolder() . DS . 'backend' . DS . 'web' . DS . 'upload' . DS . $model_dir . DS . $default_file;
                        }
                        foreach ($files as $file_path) {
                            if (is_file($file_path))
                                return $file_path;
                        }
                        return $file_path;
                    } else {
                        $file_path = FHtml::getExistUploadFilePath($default_file, 'www');
                    }
                }
            }
        }
        return $file_path;
    }

    public static function getExistUploadFilePath($file, $folder)
    {
        $file_path = FHtml::getFullUploadFolder($folder) . DS . $file;

        if (!file_exists($file_path)) {
            $file_path = FHtml::getFullUploadFolder($folder, false) . DS . $file;
        }
        return $file_path;
    }

    public static function getFileUrl($file, $model_dir = '', $default_file = '')
    {
        $file_path = self::getFilePath($file, $model_dir, $default_file);
        if (strlen($file_path) != 0) {
            $file_url = str_replace(FHtml::getRootFolder(), FHtml::getRootUrl(), $file_path);
        } else {
            $file_url = $file_path;
        }
        $file_url = str_replace('\\', '/', $file_url);
        return $file_url;
    }

    public static function getBaseUrl($view = null, $get_from_root = false)
    {
        if (!isset($view)) {
            $result = $baseUrl = Yii::$app->request->baseUrl;
            if ($get_from_root)
                $result = FHtml::strReplace($result, ['backend/web' => '']);

            return $result;
        }

        $asset = CustomAsset::register($view);
        return $baseUrl = $asset->baseUrl;
    }

    public static function findArrayFromCSV($file, $first_columns = false)
    {
        $file = FFile::getFullFileName($file);

        if (!is_file($file))
            return [];

        $csv = array_map('str_getcsv', file($file));
        if (is_array($first_columns) && !empty($first_columns)) {
            $csv = array_merge($first_columns, $csv);
        }
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });

        array_shift($csv); # remove column header
        return $csv;
    }

    public static function saveToCSV($data, $file = '')
    {
        # Generate CSV data from array
        $fh = fopen('php://temp', 'rw'); # don't create a file, attempt
        # to use memory instead

        # write out the headers
        fputcsv($fh, array_keys(current($data)));

        # write out the data
        foreach ($data as $row) {
            fputcsv($fh, $row);
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        if (!empty($file)) {
            $file = FFile::getFullFileName($file);
            FFile::write($file, $csv);
        }

        return $csv;
    }

    public static function findArrayFromJSONFile($file, $first_columns = false)
    {
        $file = FFile::getFullFileName($file);
        if (!is_file($file))
            return [];

        $content = FFile::readFile($file);
        $json = FContent::decode($content);
        return $json;
    }

    public static function saveToJSon($data, $file = '')
    {
        $file = FFile::getFullFileName($file);

        $content = FContent::encode($data);
        if (!empty($file)) {
            $file = FFile::getFullFileName($file);
            FFile::write($file, $content);
        }
        return $content;
    }
}

