<?php

namespace modules\main;

use craft\base\Element;
use craft\elements\Category;
use craft\elements\Entry;
use craft\helpers\StringHelper;
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
            function($event) {
                $entry = $event->sender;

                if (!$entry instanceof Entry) {
                    return;
                }

                if ($entry->getIsDraft() || $entry->getIsRevision()) {
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

                $entry->slug = StringHelper::slugify($entry->title);
            }
        );

        Event::on(
            Category::class,
            Element::EVENT_BEFORE_SAVE,
            function($event) {
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

                $category->slug = StringHelper::slugify($category->title);
            }
        );
    }
}