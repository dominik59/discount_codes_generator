<?php
/**
 * Created by PhpStorm.
 * User: wikid
 * Date: 23.05.2017
 * Time: 20:52
 */

namespace Controller;


class Generator
{
    private $allowed_characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private $output_discount_codes_array = [];

    public function generate_discount_codes($how_many, $length, $percentage_of_fill = 70, $custom_characters = null)
    {
        $percentage_of_fill = $percentage_of_fill / 100.0;
        if ($custom_characters !== null) {
            if (gettype($custom_characters == 'string')) {
                $this->change_allowed_characters($custom_characters);
            }
        }
        if (($message = $this->count_number_of_possibilities($how_many, $length, $percentage_of_fill)) === true) {
            for ($i = 0; $i < $how_many; $i++) {
//                $code = $this->generate_one_discount_code($length);
                do {
                    $code = $this->generate_one_discount_code($length);
                } while ($this->check_if_code_exist($code));
                $this->set_output_discount_code($code);
            }
            return $this->output_discount_codes_array;
        } else {
            return $message;
        }
    }

    private function change_allowed_characters($new_allowed_characters)
    {
        $this->allowed_characters = implode('', array_unique(str_split($new_allowed_characters)));
    }

    private function count_number_of_possibilities($how_many, $length, $percentage_of_fill)
    {
        $number_of_possibilities = pow(strlen($this->allowed_characters), $length);
        if ($number_of_possibilities < $how_many) {
            return 'Ilosc kombinacji jest niewystaczajaca do osiagniecia zadanego efektu';
        } elseif ($how_many / $number_of_possibilities > $percentage_of_fill) {
            return 'Parametr wypelnienia zostal ustawiony na ' . $percentage_of_fill * 100 . '%, niestety ustawione parametry przekraczaja deklaracje, prosze o zmniejszenie liczby kodow, lub zwiekszenie dlugosci kodu';
        } else {
            return true;
        }
    }

    private function generate_one_discount_code($length)
    {
        $output_code = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_int = random_int(0, strlen($this->allowed_characters) - 1);
            $output_code .= $this->allowed_characters[$rand_int];
        }
        return $output_code;
    }

    private function check_if_code_exist($code)
    {
        if (in_array($code, $this->output_discount_codes_array)) {
            return true;
        } else {
            return false;
        }
    }

    private function set_output_discount_code($code)
    {
        array_push($this->output_discount_codes_array, $code);
    }


}