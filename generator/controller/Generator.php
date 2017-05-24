<?php
/**
 * Created by PhpStorm.
 * User: wikid
 * Date: 23.05.2017
 * Time: 20:52
 */

namespace Controller;


use Modules\Encryptor;
use Modules\Parser;

class Generator
{
    private $allowed_characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private $output_discount_codes_array = [];
    private $generator_model;

    function __construct()
    {
        $this->generator_model = new \Model\Generator();
    }

    /**
     * Funkcja przeznaczona do generowania formularza do wyboru opcji algorytmu
     */
    public function parse_page_to_generate_codes()
    {
        $view = new Parser(__DIR__ . '/../view/generator.html');
        $view->TITLE = 'Greet the User';

        $view->parse('page');
        $view->render('page');

    }

    /**
     * Funkcja przeznaczona do pobierania pliku, do którego ścieżka została zaszyfrowana modułem Encryptor
     * @param $encrypted_file zaszyfrowana ścieżka do pliku
     */
    public function download_encrypted_file($encrypted_file)
    {
        $encryptor_object = new Encryptor();
        $decrypted_file_path = $encryptor_object->decrypt($encrypted_file);
        $this->generator_model->download_file($decrypted_file_path);
    }

    /**
     * Główna funkcja przeznaczona do koordynacji procesu tworzenia tablicy kodów
     * @param $how_many ile kodów ma być wygenerowane
     * @param $length jak długie mają być to kody
     * @param int $percentage_of_fill jaki ma być procent wypełnienia wszystkich możliwości
     * @param null $custom_characters zastąpienie wbudowanego słownika wybranym przez użytkownika
     * @param null $file_path opcjonalna ścieżka do pliku
     * @return array|bool|string zwracana jest albo tablica kodów, komunikat o błędzie lub zmienna typu logicznego
     */
    public function download_generated_discount_codes($how_many, $length, $percentage_of_fill = 70, $custom_characters = null, $file_path = null)
    {
        if (php_sapi_name() != 'cli') {
            $codes_array = $this->generate_discount_codes($how_many, $length, $percentage_of_fill, $custom_characters);
            if (!is_array($codes_array)) {
                $this->parse_error_page($codes_array);
            } else {
                $file_path = $this->generator_model->save_array_to_txt_file($codes_array);
                $encryptor_object = new Encryptor();
                $encrypted_file_path = $encryptor_object->encrypt($file_path);
                $this->parse_encrypted_download_page($encrypted_file_path);
            }
        } else {
            $codes_array = $this->generate_discount_codes($how_many, $length, $percentage_of_fill, $custom_characters);
            if (!is_array($codes_array)) {
                return $codes_array;
            }
            $this->generator_model->save_array_to_txt_file($codes_array, $file_path);

            return 'Zapisano';
        }
    }

    /**
     * Funkcja przeznaczona do tworzenia tablicy kodów
     * @param $how_many ile kodów ma być wygenerowane
     * @param $length jak długie mają być to kody
     * @param int $percentage_of_fill jaki ma być procent wypełnienia wszystkich możliwości
     * @param null $custom_characters zastąpienie wbudowanego słownika wybranym przez użytkownika
     * @return array|bool|string zwracana jest albo tablica kodów, komunikat o błędzie lub zmienna typu logicznego
     */
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

    /**
     * Funkcja przeznaczona do zmiany wbudowanego słownika, dba o to by nie było duplikatów znaków
     * @param $new_allowed_characters ciąg znaków przesłany przez użytkownika
     */
    private function change_allowed_characters($new_allowed_characters)
    {
        $this->allowed_characters = implode('', array_unique(str_split($new_allowed_characters)));
    }

    /**
     * Funkcja przeznaczona do określenia czy za pomocąwprowadzonych danych da rady wyznaczyć odpowiednią ilość unikalnych kodów
     * @param $how_many ile kodów ma być wygenerowane
     * @param $length jak długie mają być to kody
     * @param int $percentage_of_fill jaki ma być procent wypełnienia wszystkich możliwości
     * @return bool|string treść błędu lub true
     */
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

    /**
     * funkcja do generowania pojedynczego kodu
     * @param $length jak długie mają być to kody
     * @return string treść kodu
     */
    private function generate_one_discount_code($length)
    {
        $output_code = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_int = random_int(0, strlen($this->allowed_characters) - 1);
            $output_code .= $this->allowed_characters[$rand_int];
        }

        return $output_code;
    }

    /**
     * Funkcja do sprawdzania czy taki kod już istnieje
     * @param $code treść kodu
     * @return bool true jeżeli istnieje false jeżeli nie
     */
    private function check_if_code_exist($code)
    {
        if (in_array($code, $this->output_discount_codes_array)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Dołączenie przekazanego kodu do tablicy wynikowej
     * @param $code treść kodu
     */
    private function set_output_discount_code($code)
    {
        array_push($this->output_discount_codes_array, $code);
    }

    /**
     * Funcja przeznaczona do wygenerowania strony z opisem błędu
     * @param $error_message treść błędu do wyświetlenia
     */
    public function parse_error_page($error_message)
    {
        $view = new Parser(__DIR__ . '/../view/error.html');
        $view->BLAD = $error_message;

        $view->parse('page');
        $view->render('page');
    }

    /**
     * Funkcja przeznaczona do wygenerowania wyglądu strony do pobrania pliku
     * @param $encrypted_file_path
     */
    public function parse_encrypted_download_page($encrypted_file_path)
    {
        $view = new Parser(__DIR__ . '/../view/download.html');
        $view->LINK = '?file=' . $encrypted_file_path;

        $view->parse('page');
        $view->render('page');
    }


}