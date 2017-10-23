<?php
/**
 * Created by PhpStorm.
 * User: wikid
 * Date: 23.05.2017
 * Time: 20:52
 */

namespace Model;


class Generator
{
    private $filename = 'codes';
    private $extension = '.csv';
    private $path = __DIR__ . '/../';

    public function __construct()
    {
        $this->path = sys_get_temp_dir();
    }

    /**
     * Funkcja przeznaczona do zapisania danych w formie pliku csv
     * @param array $array tablica z danymi
     * @param null $custom_file_path ścieżka ustawiona przez użytkownika
     * @return null|string ścieżka do zapisanego pliku
     */
    public function save_array_to_csv_file(array $array, $custom_file_path = null)
    {
        if ($custom_file_path !== null) {
            $absolute_path = $custom_file_path;
        } else {
            $absolute_path = $this->path . $this->filename . $this->extension;
        }
        $out = fopen($absolute_path, 'w');
        foreach ($array as $element) {
            fputcsv($out, array($element));
        }
        fclose($out);

        return $absolute_path;
    }

    /**
     * Funkcja przeznaczona do zapisu pliku z danymi w formie txt
     * @param array $array tablica z danymi
     * @param null $custom_file_path ścieżka ustawiona przez użytkownika
     * @return null|string ścieżka do zapisanego pliku
     */
    public function save_array_to_txt_file(array $array, $custom_file_path = null)
    {
        $this->extension = '.txt';
        if ($custom_file_path !== null) {
            $absolute_path = $custom_file_path;
        } else {
            $absolute_path = $this->path . $this->filename . $this->extension;
        }
        $out = fopen($absolute_path, 'w');
        foreach ($array as $element) {
            fputs($out, $element . "\r\n");
//			fputcsv( $out, array( $element ), ',' );
        }
        fclose($out);

        return $absolute_path;
    }

    /**
     * Funkcja do zapisywania
     * @param string $string
     * @return string
     */
    public function save_string_to_txt_file(string $string)
    {
        $absolute_path = $this->path . $this->filename;
        $out = fopen($absolute_path, 'w');
        fputcsv($out, array($string));
        fclose($out);

        return $absolute_path;
    }

    public function download_file($file_path)
    {
        $file_url = $file_path;
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url);
    }
}