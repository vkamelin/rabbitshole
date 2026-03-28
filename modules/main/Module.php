<?php

namespace modules\main;

use craft\base\Element;
use craft\elements\Category;
use craft\elements\Entry;
use Craft;
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

                if (empty($entry->title)) {
                    return;
                }

                $allowedSections = [
                    'movies',
                    'series',
                    'anime',
                    'games',
                    'irl',
                    'reactions',
                ];

                $sectionHandle = $entry->section->handle ?? null;

                if (!in_array($sectionHandle, $allowedSections, true)) {
                    return;
                }

                $entry->slug = $this->transliterateSlug($entry->title, 'entry');
            }
        );

        Event::on(
            Category::class,
            Element::EVENT_BEFORE_SAVE,
            function ($event) {
                $category = $event->sender;

                if (!$category instanceof Category) {
                    return;
                }

                if (empty($category->title)) {
                    return;
                }

                $allowedGroups = [
                    'genres',
                    'collections',
                ];

                $groupHandle = $category->group->handle ?? null;

                if (!in_array($groupHandle, $allowedGroups, true)) {
                    return;
                }

                $category->slug = $this->transliterateSlug($category->title, 'category');
            }
        );
    }

    private function transliterateSlug(string $text, string $fallback = 'item'): string
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

        return $text !== '' ? $text : $fallback;
    }
}