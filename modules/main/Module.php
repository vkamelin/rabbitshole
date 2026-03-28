<?php

namespace modules\main;

use Cocur\Slugify\Slugify;
use craft\base\Element;
use craft\elements\Category;
use craft\elements\Entry;
use yii\base\Event;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    private Slugify $slugify;

    public function init(): void
    {
        parent::init();

        $this->slugify = new Slugify([
            'separator' => '-',
            'lowercase' => true,
            'rulesets' => ['default'],
        ]);

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

                $entry->slug = $this->makeAsciiSlug($entry->title, 'entry');
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
                    'actors',
                    'directors'
                ];

                $groupHandle = $category->group->handle ?? null;

                if (!in_array($groupHandle, $allowedGroups, true)) {
                    return;
                }

                $category->slug = $this->makeAsciiSlug($category->title, 'category');
            }
        );
    }

    private function makeAsciiSlug(string $text, string $fallback): string
    {
        $customMap = [
            'А' => 'A',  'а' => 'a',
            'Б' => 'B',  'б' => 'b',
            'В' => 'V',  'в' => 'v',
            'Г' => 'G',  'г' => 'g',
            'Д' => 'D',  'д' => 'd',
            'Е' => 'E',  'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z',  'з' => 'z',
            'И' => 'I',  'и' => 'i',
            'Й' => 'Y',  'й' => 'y',
            'К' => 'K',  'к' => 'k',
            'Л' => 'L',  'л' => 'l',
            'М' => 'M',  'м' => 'm',
            'Н' => 'N',  'н' => 'n',
            'О' => 'O',  'о' => 'o',
            'П' => 'P',  'п' => 'p',
            'Р' => 'R',  'р' => 'r',
            'С' => 'S',  'с' => 's',
            'Т' => 'T',  'т' => 't',
            'У' => 'U',  'у' => 'u',
            'Ф' => 'F',  'ф' => 'f',
            'Х' => 'Kh', 'х' => 'kh',
            'Ц' => 'Ts', 'ц' => 'ts',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shch','щ' => 'shch',
            'Ъ' => '',   'ъ' => '',
            'Ы' => 'Y',  'ы' => 'y',
            'Ь' => '',   'ь' => '',
            'Э' => 'E',  'э' => 'e',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
        ];

        $text = strtr($text, $customMap);
        $slug = $this->slugify->slugify($text);

        return $slug !== '' ? $slug : $fallback;
    }
}