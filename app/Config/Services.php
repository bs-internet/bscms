<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Core\Shared\Libraries\Template;
use App\Core\Shared\Libraries\Loop;

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
            new \App\Core\Modules\Content\Models\ContentCategoryModel()
        );
    }

    public static function cacheManager(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('cacheManager');
        }

        return new \App\Core\Shared\Libraries\CacheManager();
    }

    public static function contentTypeRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentTypeRepository');
        }

        return new \App\Core\Modules\Content\Repositories\ContentTypeRepository(
            new \App\Core\Modules\Content\Models\ContentTypeModel()
        );
    }

    public static function contentRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentRepository');
        }

        return new \App\Core\Modules\Content\Repositories\ContentRepository(
            new \App\Core\Modules\Content\Models\ContentModel()
        );
    }

    public static function contentMetaRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentMetaRepository');
        }

        return new \App\Core\Modules\Content\Repositories\ContentMetaRepository(
            new \App\Core\Modules\Content\Models\ContentMetaModel()
        );
    }

    public static function contentTypeFieldRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentTypeFieldRepository');
        }

        return new \App\Core\Modules\Content\Repositories\ContentTypeFieldRepository(
            new \App\Core\Modules\Content\Models\ContentTypeFieldModel()
        );
    }

    public static function categoryRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('categoryRepository');
        }

        return new \App\Core\Modules\Category\Repositories\CategoryRepository(
            new \App\Core\Modules\Category\Models\CategoryModel()
        );
    }

    public static function mediaRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('mediaRepository');
        }

        return new \App\Core\Modules\Media\Repositories\MediaRepository(
            new \App\Core\Modules\Media\Models\MediaModel()
        );
    }

    public static function menuRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('menuRepository');
        }

        return new \App\Core\Modules\Menu\Repositories\MenuRepository(
            new \App\Core\Modules\Menu\Models\MenuModel()
        );
    }

    public static function menuItemRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('menuItemRepository');
        }

        return new \App\Core\Modules\Menu\Repositories\MenuItemRepository(
            new \App\Core\Modules\Menu\Models\MenuItemModel()
        );
    }

    public static function formRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formRepository');
        }

        return new \App\Core\Modules\Form\Repositories\FormRepository(
            new \App\Core\Modules\Form\Models\FormModel()
        );
    }

    public static function formFieldRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formFieldRepository');
        }

        return new \App\Core\Modules\Form\Repositories\FormFieldRepository(
            new \App\Core\Modules\Form\Models\FormFieldModel()
        );
    }

    public static function formSubmissionRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formSubmissionRepository');
        }

        return new \App\Core\Modules\Form\Repositories\FormSubmissionRepository(
            new \App\Core\Modules\Form\Models\FormSubmissionModel()
        );
    }

    public static function formSubmissionDataRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('formSubmissionDataRepository');
        }

        return new \App\Core\Modules\Form\Repositories\FormSubmissionDataRepository(
            new \App\Core\Modules\Form\Models\FormSubmissionDataModel()
        );
    }

    public static function settingRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('settingRepository');
        }

        return new \App\Core\Modules\System\Repositories\SettingRepository(
            new \App\Core\Modules\System\Models\SettingModel()
        );
    }

    public static function userRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('userRepository');
        }

        return new \App\Core\Modules\Auth\Repositories\UserRepository(
            new \App\Core\Modules\Auth\Models\UserModel()
        );
    }

    public static function componentRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('componentRepository');
        }

        return new \App\Core\Modules\Component\Repositories\ComponentRepository(
            new \App\Core\Modules\Component\Models\ComponentModel()
        );
    }

    public static function componentFieldRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('componentFieldRepository');
        }

        return new \App\Core\Modules\Component\Repositories\ComponentFieldRepository(
            new \App\Core\Modules\Component\Models\ComponentFieldModel()
        );
    }

    public static function componentLocationRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('componentLocationRepository');
        }

        return new \App\Core\Modules\Component\Repositories\ComponentLocationRepository(
            new \App\Core\Modules\Component\Models\ComponentLocationModel()
        );
    }

    public static function componentInstanceRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('componentInstanceRepository');
        }

        return new \App\Core\Modules\Component\Repositories\ComponentInstanceRepository(
            new \App\Core\Modules\Component\Models\ComponentInstanceModel()
        );
    }

    public static function componentInstanceDataRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('componentInstanceDataRepository');
        }

        return new \App\Core\Modules\Component\Repositories\ComponentInstanceDataRepository(
            new \App\Core\Modules\Component\Models\ComponentInstanceDataModel()
        );
    }

    public static function contentComponentRepository(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentComponentRepository');
        }

        return new \App\Core\Modules\Component\Repositories\ContentComponentRepository(
            new \App\Core\Modules\Content\Models\ContentComponentModel()
        );
    }

    public static function contentCategoryModel(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentCategoryModel');
        }

        return new \App\Core\Modules\Content\Models\ContentCategoryModel();
    }
}