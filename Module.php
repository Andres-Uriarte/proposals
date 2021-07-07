<?php

namespace u4impact\humhub\modules\proposal;

use humhub\modules\user\models\ProfileField;
use humhub\modules\user\models\ProfileFieldCategory;
use yii\base\Exception;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    /**
    * @inheritdoc
    */
    public function getConfigUrl(): string
    {
        return Url::to(['/proposal/admin/proposals']);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function enable(): bool
    {
        $cGeneral = ProfileFieldCategory::findOne(['title' => 'General']);
        if($cGeneral != null) {

            $profile = ProfileField::findOne(['internal_name' => 'profile']);
            if ($profile == null) {
                $field = new ProfileField();
                $field->internal_name = "profile";
                $field->title = "Perfil";
                $field->description = "Debe indicar el rol que ejercerá dentro de la red social enfocada a los TFX";
                $field->sort_order = 250;
                $field->required = 1;
                $field->profile_field_category_id = $cGeneral->id;
                $field->module_id = $this->id;
                $field->field_type_class = \humhub\modules\user\models\fieldtype\Select::class;
                $field->show_at_registration = 1;
                $field->editable = 0;
                $field->visible = 1;
                $field->searchable = 1;
                $field->ldap_attribute = "profile";
                $field->translation_category = "UserModule.profile";
                if ($field->save()) {
                    $field->fieldType->options = "student=>Estudiante\norganization=>Organización";
                    $field->fieldType->save();
                } else {
                    throw new Exception(print_r($field->getErrors(), true));
                }
            }

            $organization_name = ProfileField::findOne(['internal_name' => 'organization_name']);
            if ($organization_name == null) {
                $field = new ProfileField();
                $field->internal_name = "organization_name";
                $field->title = "Institución educativa / Organización";
                $field->description = "Si eres estudiante indica tu institución educativa y si eres una organización rellénalo con su nombre";
                $field->sort_order = 251;
                $field->required = 1;
                $field->profile_field_category_id = $cGeneral->id;
                $field->module_id = $this->id;
                $field->field_type_class = \humhub\modules\user\models\fieldtype\Text::class;
                $field->is_system = NULL;
                $field->show_at_registration = 1;
                $field->editable = 0;
                $field->visible = 1;
                $field->searchable = 1;
                $field->ldap_attribute = "organization_name";
                $field->translation_category = "UserModule.profile";
                if ($field->save()) {
                    $field->fieldType->maxLength = 255;
                    $field->fieldType->minLength = "";
                    $field->fieldType->validator = "";
                    $field->fieldType->default = "";
                    $field->fieldType->regexp = "";
                    $field->fieldType->regexpErrorMessage = "";
                    $field->fieldType->save();
                } else {
                    throw new Exception(print_r($field->getErrors(), true));
                }
            }

            $profile_validation = ProfileField::findOne(['internal_name' => 'profile_validation']);
            if ($profile_validation == null) {
                $field = new ProfileField();
                $field->internal_name = "profile_validation";
                $field->title = "Perfil validado";
                $field->description = "El administrador deberá validar cada perfil de organización comprobando que se corresponde con la realidad.";
                $field->sort_order = 281;
                $field->required = 0;
                $field->profile_field_category_id = $cGeneral->id;
                $field->module_id = $this->id;
                $field->field_type_class = \humhub\modules\user\models\fieldtype\Select::class;
                $field->show_at_registration = 0;
                $field->editable = 0;
                $field->visible = 1;
                $field->searchable = 1;
                $field->ldap_attribute = "profile_validation";
                $field->translation_category = "UserModule.profile";
                if ($field->save()) {
                    $field->fieldType->options = "true=>Sí";
                    $field->fieldType->save();
                } else {
                    throw new Exception(print_r($field->getErrors(), true));
                }
            }

            $spaces_loaded = ProfileField::findOne(['internal_name' => 'spaces_loaded']);
            if ($spaces_loaded == null) {
                $field = new ProfileField();
                $field->internal_name = "spaces_loaded";
                $field->title = "Espacios Cargados";
                $field->description = "Este campo indica si el usuario organización ha sido dado de alta en los espacios de su organización ya creados previos a su primer login.";
                $field->sort_order = 285;
                $field->required = 0;
                $field->profile_field_category_id = $cGeneral->id;
                $field->module_id = $this->id;
                $field->field_type_class = \humhub\modules\user\models\fieldtype\Select::class;
                $field->show_at_registration = 0;
                $field->editable = 0;
                $field->visible = 0;
                $field->searchable = 0;
                $field->ldap_attribute = "spaces_loaded";
                $field->translation_category = "UserModule.profile";
                if ($field->save()) {
                    $field->fieldType->options = "true=>Sí";
                    $field->fieldType->save();
                } else {
                    throw new Exception(print_r($field->getErrors(), true));
                }
            }
        }

        return parent::enable();
    }

    /**
    * @inheritdoc
    */
    public function disable()
    {
        $DELETE_PROFILE_FIELDS = true;

        if ($DELETE_PROFILE_FIELDS) {
            $profile = ProfileField::findOne(['internal_name' => 'profile']);
            if ($profile != null) {
                $profile->delete();
            }

            $organization_name = ProfileField::findOne(['internal_name' => 'organization_name']);
            if ($organization_name != null) {
                $organization_name->delete();
            }

            $profile_validation = ProfileField::findOne(['internal_name' => 'profile_validation']);
            if ($profile_validation != null) {
                $profile_validation->delete();
            }

            $spaces_loaded = ProfileField::findOne(['internal_name' => 'spaces_loaded']);
            if ($spaces_loaded != null) {
                $spaces_loaded->delete();
            }
        }

        // Cleanup all module data, don't remove the parent::disable()!!!
        parent::disable();
    }

}
