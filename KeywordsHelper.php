<?php
/**
 * Created by internetsite.com.ua
 * User: Tymofeiev Maksym
 * Date: 28.12.2016
 * Time: 13:59
 */

namespace wokster\seomodule;


class KeywordsHelper
{
  protected $text;
  protected $origin_arr;
  protected $modif_arr;
  protected $min_word_length = 5;

  function explode_str_on_words()
  {
    $search = array ("'ё'",
        "'<script[^>]*?>.*?</script>'si",  // Вырезается javascript
        "'<[/!]*?[^<>]*?>'si",           // Вырезаются html-тэги
        "'([rn])[s]+'",                 // Вырезается пустое пространство
        "'&(quot|#34);'i",                 // Замещаются html-элементы
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&#(d+);'e");
    $replace = array ("е",
        " ",
        " ",
        "1 ",
        " ",
                      " ",
                      " ",
                      " ",
                      " ",
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      "chr(1)");
    $text = preg_replace ($search, $replace, $this->text);
    //Список стоп символом и слов
    $del_symbols = array(",", ".", ";", ":", "", "#", "$", "%", "^",
                         "!", "@", "`", "~", "*", "-", "=", "+", "",
                         "|", "/", ">", "<", "(", ")", "&", "?", "?", "t",
                         "r", "n", "{","}","[","]", "'", "“", "”", "•",
                         "как", "для", "для", "что", "или", "это", "этих",
                         "всех", "вас", "они", "оно", "еще", "когда",
                         "где", "эта", "лишь", "уже", "вам", "нет",
                         "если", "надо", "все", "так", "его", "чем",
                         "при", "даже", "мне", "есть", "раз", "два",
                         "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
                         );
 
    $text = str_ireplace($del_symbols, array(" "), $text);
    $text = preg_replace("( +)", " ", $text);
    $this->origin_arr = explode(" ", trim($text));
    return $this->origin_arr;
}

  function count_words()
  {
    $tmp_arr = array();
    foreach ($this->origin_arr as $val)
    {
      if (strlen($val)>=$this->min_word_length)
      {
        $val = strtolower($val);
        if (array_key_exists($val, $tmp_arr))
        {
          $tmp_arr[$val]++;
        }
        else
        {
          $tmp_arr[$val] = 1;
        }
      }
    }
    arsort ($tmp_arr);
    $this->modif_arr = $tmp_arr;
  }

  function get_keywords($text)
  {
    $text = strip_tags($text);
    $this->text = mb_convert_case($text,MB_CASE_LOWER,"UTF-8");
    $this->explode_str_on_words();
    $this->count_words();
    $arr = array_slice($this->modif_arr, 0, 10);
    $str = "";
    foreach ($arr as $key=>$val)
    {
      $str .= $key . ", ";
    }
    return trim(substr($str, 0, strlen($str)-2));
  }
}