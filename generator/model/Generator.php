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
    private $filename = 'codes.csv';
    private $path = __DIR__ . '/../';

    public function save_array_to_csv_file(array $array)
    {
        $out = fopen($this->path . $this->filename, 'w');
        foreach ($array as $element) {
            fputcsv($out, array($element));
        }
        fclose($out);
    }

    public function save_string_to_csv_file(string $string)
    {
        $out = fopen($this->path . $this->filename, 'w');
        fputcsv($out, array($string));
        fclose($out);
    }
}