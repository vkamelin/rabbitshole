<?php

namespace modules\main;

use craft\base\Element;
use craft\elements\Entry;
use yii\base\Event;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public function init(): void
    {
        parent::init();

        Event::on(
            Entry::class,
            Element::EVENT_BEFORE_SAVE,
            function ($event) {
                $entry = $event->sender;

                if (!$entry instanceof Entry) {
                    return;
                }

                // Если нет title — ничего не делаем
                if (empty($entry->title)) {
                    return;
                }

                // Если slug уже введён вручную — не трогаем
                if (!empty($entry->slug)) {
                    return;
                }

                $entry->slug = $this->transliterateSlug($entry->title);
            }
        );
    }

    private function transliterateSlug(string $text): string
    {
        $map = [
            'а' => 'a',  'б' => 'b',  'в' => 'v',  'г' => 'g',  'д' => 'd',
            'е' => 'e',  'ё' => 'e',  'ж' => 'zh', 'з' => 'z',  'и' => 'i',
            'й' => 'y',  'к' => 'k',  'л' => 'l',  'м' => 'm',  'н' => 'n',
            'о' => 'o',  'п' => 'p',  'р' => 'r',  'с' => 's',  'т' => 't',
            'у' => 'u',  'ф' => 'f',  'х' => 'h',  'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch','ъ' => '',   'ы' => 'y',  'ь' => '',
            'э' => 'e',  'ю' => 'yu', 'я' => 'ya',
        ];

        $text = mb_strtolower($text, 'UTF-8');
        $text = strtr($text, $map);
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
        $text = trim($text, '-');

        return $text !== '' ? $text : 'entry';
    }
}