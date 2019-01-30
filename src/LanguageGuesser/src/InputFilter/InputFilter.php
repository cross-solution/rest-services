<?php
/**
 * CrossServices
 *
 * @filesource
 * @copyright 2019 Cross Solution <http://cross-solution.de>
 */

/** */

namespace LanguageGuesser\InputFilter;

use Zend\Filter\Callback;
use Zend\InputFilter\ArrayInput;
use Zend\InputFilter\InputFilter as ZfInputFilter;
use Zend\Validator\InArray;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class InputFilter extends ZfInputFilter
{
    public function init()
    {
        $this->add([
            'name' => 'languages',
            'type' => ArrayInput::class,
            'required' => false,
            'validators' => [
                [
                    'name' => 'InArray',

                    'options' => [
                        'haystack' => $this->getValidLanguages(),
                        'messageTemplates' => [
                            InArray::NOT_IN_ARRAY => 'Unsupported language: %value%',
                        ],
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'text',
            'filters' => [
                [
                    'name' => 'StringTrim'
                ],
            ],
        ]);
    }
    
    private function getValidLanguages() {
        return 

        [
            'ab',
            'af',
            'am',
            'ar',
            'ay',
            'az-Cyrl',
            'az-Latn',
            'be',
            'bg',
            'bi',
            'bn',
            'bo',
            'br',
            'bs-Cyrl',
            'bs-Latn',
            'ca',
            'ch',
            'co',
            'cr',
            'cs',
            'cy',
            'da',
            'de',
            'dz',
            'el-monoton',
            'el-polyton',
            'en',
            'eo',
            'es',
            'et',
            'eu',
            'fa',
            'fi',
            'fj',
            'fo',
            'fr',
            'fy',
            'ga',
            'gd',
            'gl',
            'gn',
            'gu',
            'ha',
            'he',
            'hi',
            'hr',
            'hu',
            'hy',
            'ia',
            'id',
            'ig',
            'io',
            'is',
            'it',
            'iu',
            'ja',
            'jv',
            'ka',
            'km',
            'ko',
            'kr',
            'ku',
            'la',
            'lg',
            'ln',
            'lo',
            'lt',
            'lv',
            'mh',
            'mn-Cyrl',
            'ms-Arab',
            'ms-Latn',
            'mt',
            'nb',
            'ng',
            'nl',
            'nn',
            'nv',
            'pl',
            'pt-BR',
            'pt-PT',
            'ro',
            'ru',
            'sa',
            'sk',
            'sl',
            'so',
            'sq',
            'sr-Cyrl',
            'sr-Latn	',
            'ss',
            'sv',
            'ta',
            'th',
            'tl',
            'to	',
            'tr',
            'tt',
            'ty',
            'ug-Arab',
            'ug-Latn',
            'uk',
            'ur	',
            'uz',
            've',
            'vi',
            'wa',
            'wo',
            'xh',
            'yo',
            'zh-Hans',
            'zh-Hant',
        ];
    }
}
