<?php
namespace App\CPU;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Models\BusinessSetting;
use App\Models\Currency;

class Helpers
{
    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }
    public static function currency_code()
    {
        $currency_code = BusinessSetting::where(['key' => 'currency'])->first()->value;
        return $currency_code;
    }

    public static function currency_symbol()
    {
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol;
        return $currency_symbol;
    }
    public static function upload(string $dir, string $format, $image = null)
    {
        if ($image != null) {
            $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }
    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = Helpers::upload($dir, $format, $image);
        return $imageName;
    }
    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }
        return [
            'success' => 1,
            'message' => translate('Removed successfully')
        ];
    }
    public static function discount_calculate($product, $price)
    {
        if ($product['discount_type'] == 'percent') {
            $price_discount = ($price / 100) * $product['discount'];
        } else {
            $price_discount = $product['discount'];
        }
        return $price_discount;
    }
    public static function tax_calculate($product, $price)
    {
        $price_tax = ($price / 100) * $product['tax'];

        return $price_tax;
    }
    public static function get_business_settings($name)
    {
        $config = null;
        $data = BusinessSetting::where(['key' => $name])->first();
        if (isset($data)) {
            $config = json_decode($data['value'], true);
            if (is_null($config)) {
                $config = $data['value'];
            }
        }
        return $config;
    }
    public static function get_language_name($key)
    {
        $languages = array(
            "af" => "Afrikaans",
            "sq" => "Albanian - shqip",
            "am" => "Amharic - ????????????",
            "ar" => "Arabic - ??????????????",
            "an" => "Aragonese - aragon??s",
            "hy" => "Armenian - ??????????????",
            "ast" => "Asturian - asturianu",
            "az" => "Azerbaijani - az??rbaycan dili",
            "eu" => "Basque - euskara",
            "be" => "Belarusian - ????????????????????",
            "bn" => "Bengali - ???????????????",
            "bs" => "Bosnian - bosanski",
            "br" => "Breton - brezhoneg",
            "bg" => "Bulgarian - ??????????????????",
            "ca" => "Catalan - catal??",
            "ckb" => "Central Kurdish - ?????????? (???????????????? ????????????)",
            "zh" => "Chinese - ??????",
            "zh-HK" => "Chinese (Hong Kong) - ??????????????????",
            "zh-CN" => "Chinese (Simplified) - ??????????????????",
            "zh-TW" => "Chinese (Traditional) - ??????????????????",
            "co" => "Corsican",
            "hr" => "Croatian - hrvatski",
            "cs" => "Czech - ??e??tina",
            "da" => "Danish - dansk",
            "nl" => "Dutch - Nederlands",
            "en" => "English",
            "en-AU" => "English (Australia)",
            "en-CA" => "English (Canada)",
            "en-IN" => "English (India)",
            "en-NZ" => "English (New Zealand)",
            "en-ZA" => "English (South Africa)",
            "en-GB" => "English (United Kingdom)",
            "en-US" => "English (United States)",
            "eo" => "Esperanto - esperanto",
            "et" => "Estonian - eesti",
            "fo" => "Faroese - f??royskt",
            "fil" => "Filipino",
            "fi" => "Finnish - suomi",
            "fr" => "French - fran??ais",
            "fr-CA" => "French (Canada) - fran??ais (Canada)",
            "fr-FR" => "French (France) - fran??ais (France)",
            "fr-CH" => "French (Switzerland) - fran??ais (Suisse)",
            "gl" => "Galician - galego",
            "ka" => "Georgian - ?????????????????????",
            "de" => "German - Deutsch",
            "de-AT" => "German (Austria) - Deutsch (??sterreich)",
            "de-DE" => "German (Germany) - Deutsch (Deutschland)",
            "de-LI" => "German (Liechtenstein) - Deutsch (Liechtenstein)",
            "de-CH" => "German (Switzerland) - Deutsch (Schweiz)",
            "el" => "Greek - ????????????????",
            "gn" => "Guarani",
            "gu" => "Gujarati - ?????????????????????",
            "ha" => "Hausa",
            "haw" => "Hawaiian - ????lelo Hawai??i",
            "he" => "Hebrew - ??????????",
            "hi" => "Hindi - ??????????????????",
            "hu" => "Hungarian - magyar",
            "is" => "Icelandic - ??slenska",
            "id" => "Indonesian - Indonesia",
            "ia" => "Interlingua",
            "ga" => "Irish - Gaeilge",
            "it" => "Italian - italiano",
            "it-IT" => "Italian (Italy) - italiano (Italia)",
            "it-CH" => "Italian (Switzerland) - italiano (Svizzera)",
            "ja" => "Japanese - ?????????",
            "kn" => "Kannada - ???????????????",
            "kk" => "Kazakh - ?????????? ????????",
            "km" => "Khmer - ???????????????",
            "ko" => "Korean - ?????????",
            "ku" => "Kurdish - Kurd??",
            "ky" => "Kyrgyz - ????????????????",
            "lo" => "Lao - ?????????",
            "la" => "Latin",
            "lv" => "Latvian - latvie??u",
            "ln" => "Lingala - ling??la",
            "lt" => "Lithuanian - lietuvi??",
            "mk" => "Macedonian - ????????????????????",
            "ms" => "Malay - Bahasa Melayu",
            "ml" => "Malayalam - ??????????????????",
            "mt" => "Maltese - Malti",
            "mr" => "Marathi - ???????????????",
            "mn" => "Mongolian - ????????????",
            "ne" => "Nepali - ??????????????????",
            "no" => "Norwegian - norsk",
            "nb" => "Norwegian Bokm??l - norsk bokm??l",
            "nn" => "Norwegian Nynorsk - nynorsk",
            "oc" => "Occitan",
            "or" => "Oriya - ???????????????",
            "om" => "Oromo - Oromoo",
            "ps" => "Pashto - ????????",
            "fa" => "Persian - ??????????",
            "pl" => "Polish - polski",
            "pt" => "Portuguese - portugu??s",
            "pt-BR" => "Portuguese (Brazil) - portugu??s (Brasil)",
            "pt-PT" => "Portuguese (Portugal) - portugu??s (Portugal)",
            "pa" => "Punjabi - ??????????????????",
            "qu" => "Quechua",
            "ro" => "Romanian - rom??n??",
            "mo" => "Romanian (Moldova) - rom??n?? (Moldova)",
            "rm" => "Romansh - rumantsch",
            "ru" => "Russian - ??????????????",
            "gd" => "Scottish Gaelic",
            "sr" => "Serbian - ????????????",
            "sh" => "Serbo-Croatian - Srpskohrvatski",
            "sn" => "Shona - chiShona",
            "sd" => "Sindhi",
            "si" => "Sinhala - ???????????????",
            "sk" => "Slovak - sloven??ina",
            "sl" => "Slovenian - sloven????ina",
            "so" => "Somali - Soomaali",
            "st" => "Southern Sotho",
            "es" => "Spanish - espa??ol",
            "es-AR" => "Spanish (Argentina) - espa??ol (Argentina)",
            "es-419" => "Spanish (Latin America) - espa??ol (Latinoam??rica)",
            "es-MX" => "Spanish (Mexico) - espa??ol (M??xico)",
            "es-ES" => "Spanish (Spain) - espa??ol (Espa??a)",
            "es-US" => "Spanish (United States) - espa??ol (Estados Unidos)",
            "su" => "Sundanese",
            "sw" => "Swahili - Kiswahili",
            "sv" => "Swedish - svenska",
            "tg" => "Tajik - ????????????",
            "ta" => "Tamil - ???????????????",
            "tt" => "Tatar",
            "te" => "Telugu - ??????????????????",
            "th" => "Thai - ?????????",
            "ti" => "Tigrinya - ????????????",
            "to" => "Tongan - lea fakatonga",
            "tr" => "Turkish - T??rk??e",
            "tk" => "Turkmen",
            "tw" => "Twi",
            "uk" => "Ukrainian - ????????????????????",
            "ur" => "Urdu - ????????",
            "ug" => "Uyghur",
            "uz" => "Uzbek - o???zbek",
            "vi" => "Vietnamese - Ti???ng Vi???t",
            "wa" => "Walloon - wa",
            "cy" => "Welsh - Cymraeg",
            "fy" => "Western Frisian",
            "xh" => "Xhosa",
            "yi" => "Yiddish",
            "yo" => "Yoruba - ??d?? Yor??b??",
            "zu" => "Zulu - isiZulu",
        );
        return array_key_exists($key, $languages) ? $languages[$key] : $key;
    }
    public static function pagination_limit()
    {
        $pagination_limit = BusinessSetting::where('key', 'pagination_limit')->first();
        return (int)$pagination_limit->value;
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $oldValue = env($envKey);
        if (strpos($str, $envKey) !== false) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return $envValue;
    }

    public static function requestSender()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => route(base64_decode('YWN0aXZhdGlvbi1jaGVjaw==')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $data = json_decode($response, true);
        return $data;
    }

    public static function remove_dir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") Helpers::remove_dir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
//for translation
function translate($key)
{
    $local = session()->has('local') ? session('local') : 'en';
    App::setLocale($local);
    $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
    $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
    if (!array_key_exists($key, $lang_array)) {
        $lang_array[$key] = $processed_key;
        $str = "<?php return " . var_export($lang_array, true) . ";";
        file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
        $result = $processed_key;
    } else {
        $result = __('messages.' . $key);
    }
    return $result;
}
