<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Libraries\Template;
use App\Libraries\Loop;

class Services extends BaseService
{
    public static function template(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('template');
        }

        return new Template(
            static::contentRepository(),
            static::contentTypeRepository(),
            static::categoryRepository(),
            static::menuRepository(),
            static::menuItemRepository(),
            static::settingRepository()
        );
    }

    public static function loop(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('loop');
        }

        return new Loop(
            static::contentRepository(),
            static::contentMetaRepository(),
            static::categoryRepository(),
            static::mediaRepository(),
            static::contentCategoryModel()
        );
    }

    public static function contentTypeRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentTypeRepository');
        }

        return new \App\Repositories\ContentTypeRepository(
            new \App\Models\ContentTypeModel()
        );
    }

    public static function contentRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentRepository');
        }

        return new \App\Repositories\ContentRepository(
            new \App\Models\ContentModel()
        );
    }

    public static function contentMetaRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentMetaRepository');
        }

        return new \App\Repositories\ContentMetaRepository(
            new \App\Models\ContentMetaModel()
        );
    }

    public static function contentTypeFieldRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentTypeFieldRepository');
        }

        return new \App\Repositories\ContentTypeFieldRepository(
            new \App\Models\ContentTypeFieldModel()
        );
    }

    public static function categoryRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('categoryRepository');
        }

        return new \App\Repositories\CategoryRepository(
            new \App\Models\CategoryModel()
        );
    }

    public static function mediaRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('mediaRepository');
        }

        return new \App\Repositories\MediaRepository(
            new \App\Models\MediaModel()
        );
    }

    public static function menuRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('menuRepository');
        }

        return new \App\Repositories\MenuRepository(
            new \App\Models\MenuModel()
        );
    }

    public static function menuItemRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('menuItemRepository');
        }

        return new \App\Repositories\MenuItemRepository(
            new \App\Models\MenuItemModel()
        );
    }

    public static function formRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formRepository');
        }

        return new \App\Repositories\FormRepository(
            new \App\Models\FormModel()
        );
    }

    public static function formFieldRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formFieldRepository');
        }

        return new \App\Repositories\FormFieldRepository(
            new \App\Models\FormFieldModel()
        );
    }

    public static function formSubmissionRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formSubmissionRepository');
        }

        return new \App\Repositories\FormSubmissionRepository(
            new \App\Models\FormSubmissionModel()
        );
    }

    public static function formSubmissionDataRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formSubmissionDataRepository');
        }

        return new \App\Repositories\FormSubmissionDataRepository(
            new \App\Models\FormSubmissionDataModel()
        );
    }

    public static function settingRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('settingRepository');
        }

        return new \App\Repositories\SettingRepository(
            new \App\Models\SettingModel()
        );
    }

    public static function userRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('userRepository');
        }

        return new \App\Repositories\UserRepository(
            new \App\Models\UserModel()
        );
    }

    public static function contentCategoryModel(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentCategoryModel');
        }

        return new \App\Models\ContentCategoryModel();
    }
}