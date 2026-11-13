<?php
use App\Models\Languages;
use App\Models\Translate;


/**
 * We use this for languages
 *
 * @return // Response
 */
if (!function_exists('languages')) {
    function languages()
    {
        $codes = [
            'aa' => 'Afar',
            'ab' => 'Abkhazian',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari languages',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan',
            'br' => 'Breton',
            'bs' => 'Bosnian',
            'ca' => 'Catalan, Valencian',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'dv' => 'Divehi, Dhivehi, Maldivian',
            'dz' => 'Dzongkha',
            'ee' => 'Ewe',
            'el' => 'Greek (Modern)',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish, Castilian',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'ff' => 'Fulah',
            'fi' => 'Finnish',
            'fj' => 'Fijian',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Western Frisian',
            'ga' => 'Irish',
            'gd' => 'Gaelic, Scottish Gaelic',
            'gl' => 'Galician',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian, Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',
            'ia' => 'Interlingua (International Auxiliary Language Association)',
            'id' => 'Indonesian',
            'ie' => 'Interlingue',
            'ig' => 'Igbo',
            'ii' => 'Nuosu, Sichuan Yi',
            'ik' => 'Inupiaq',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',
            'ja' => 'Japanese',
            'jv' => 'Javanese',
            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Gikuyu, Kikuyu',
            'kj' => 'Kwanyama, Kuanyama',
            'kk' => 'Kazakh',
            'kl' => 'Greenlandic, Kalaallisut',
            'km' => 'Central Khmer',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kyrgyz',
            'la' => 'Latin',
            'lb' => 'Letzeburgesch, Luxembourgish',
            'lg' => 'Ganda',
            'li' => 'Limburgish, Limburgan, Limburger',
            'ln' => 'Lingala',
            'lo' => 'Lao',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian',
            'mg' => 'Malagasy',
            'mh' => 'Marshallese',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'nb' => 'Norwegian Bokmål',
            'nd' => 'Northern Ndebele',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch, Flemish',
            'nn' => 'Norwegian Nynorsk',
            'no' => 'Norwegian',
            'nr' => 'South Ndebele',
            'nv' => 'Navajo, Navaho',
            'ny' => 'Chichewa, Chewa, Nyanja',
            'oc' => 'Occitan (post 1500)',
            'oj' => 'Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian, Ossetic',
            'pa' => 'Panjabi, Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pashto, Pushto',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Rundi',
            'ro' => 'Moldovan, Moldavian, Romanian',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'si' => 'Sinhala, Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'st' => 'Sotho, Southern',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Tswana',
            'to' => 'Tonga (Tonga Islands)',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',
            'ug' => 'Uighur, Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'Volap_k',
            'wa' => 'Walloon',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang, Chuang',
            'zh' => 'Chinese',
            'zu' => 'Zulu',
        ];
        return $codes;
    }
}

/**
 * We use this to remove spaces
 *
 * @return // Response
 */
if (!function_exists('removeSpaces')) {
    function removeSpaces($string)
    {
        
        return preg_replace('/\s+/', '', $string);
    }
}


/**
 * We use to get lang URL
 *
 * @return // Response
 */
if (!function_exists('langURL')) {
    function langURL($lang)
    {
        
        return route('admin.front.set_language', [$lang]);
    }
}

/**
 * We use to get current lang
 *
 * @return // Response
 */
if (!function_exists('getLang')) {
    function getLang()
    {
        return setting('default_lang');
    }
}

/**
 * We use to get current lang
 *
 * @return // Response
 */
if (!function_exists('getLangName')) {
    function getLangName()
    {       
        if(session()->has('locale')){
            $lang = Languages::where('languagecode', session()->get('locale'))->first();
            if($lang != null){
                return $lang->languagename;
            }else{
                $lang = Languages::where('languagecode', getLang())->first();
                if($lang != null){
                    return $lang->languagename;
                }
            }
            
        }else{
            $lang = Languages::where('languagecode', getLang())->first();
            if($lang != null){
                return $lang->languagename;
            }
        }
         
        
    }
}

if (!function_exists('getIsRtl')) {
    function getIsRtl()
    {       
        if(session()->has('locale')){
            $lang = Languages::where('languagecode', session()->get('locale'))->first();
            if($lang != null){
                return $lang->is_rtl ? 'rtl' : '';
            }else{
                 $lang = Languages::where('languagecode', getLang())->first();
                if($lang != null){
                    return $lang->is_rtl ? 'rtl' : '';
                }
            }
        }else{
            $lang = Languages::where('languagecode', getLang())->first();
            if($lang != null){
                return $lang->is_rtl ? 'rtl' : '';
            }
        }
        
        
    }
}

/**
 * We use to get support locales
 *
 * @return // Response
 */
if (!function_exists('getSupportedLocales')) {
    function getSupportedLocales()
    {
        $locales = [];
        foreach (Languages::all() as $language) {
            $locales[$language->code] = [
                'name' => $language->languagename,
            ];
        }
        return $locales;
    }
}
/**
 * We use this to translate website
 * We get the language from database
 * If the item is not in database we create it
 * @return // Response
 */
if (!function_exists('lang')) {
    function lang($key, $group = null, $lang = null)
    {
        static $mem = [];
        static $ver = null;
        static $installed = null;

        if ($lang == null) {
            $lang = App::getLocale();
        }
        if ($group == null) {
            $group = "General";
        }
        $mk = $lang . "\0" . $group . "\0" . $key;
        if (array_key_exists($mk, $mem)) {
            return $mem[$mk];
        }

        $default = setting('default_lang');
        if ($ver === null) {
            $ver = (string) \Illuminate\Support\Facades\Cache::get('gnlang_ver', '0');
        }
        $ck = 'gnlang:' . $ver . ':' . md5($mk . "\0" . $default);
        $hit = \Illuminate\Support\Facades\Cache::get($ck);
        if ($hit !== null) {
            return $mem[$mk] = $hit;
        }

        // Une seule requête pour la langue demandée et la langue par défaut.
        $rows = Translate::whereIn('lang_code', array_values(array_unique([$lang, $default])))
            ->where('key', $key)->where('group_langname', $group)->get(['lang_code', 'value']);
        $local = optional($rows->firstWhere('lang_code', $lang))->value;
        $def = optional($rows->firstWhere('lang_code', $default))->value;

        if ($rows->isEmpty()) {
            // Clé inconnue : on la crée UNE seule fois par langue installée (jamais de doublon).
            if ($installed === null) {
                $installed = Languages::pluck('languagecode')->all();
            }
            foreach ($installed as $code) {
                Translate::firstOrCreate(
                    ['lang_code' => $code, 'group_langname' => strtolower($group), 'key' => $key],
                    ['value' => ($code == 'en') ? $key : null]
                );
            }
            $ver = null; // la création invalide le cache : on relira la version
        }

        if ($local !== null && $local !== '') {
            $out = $local;
        } elseif ($def !== null && $def !== '') {
            $out = $def;
        } else {
            $out = $key;
        }
        \Illuminate\Support\Facades\Cache::put($ck, $out, 86400);
        return $mem[$mk] = $out;
    }
}


function getLanguageslist()
{

	$languages = Languages::get();
	
	return $languages;
}