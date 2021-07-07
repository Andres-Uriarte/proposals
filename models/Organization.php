<?php

namespace u4impact\humhub\modules\proposal\models;

use \yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%proposal_organization}}".
 *
 * @property int $id
 * @property string $name
 * @property string $legal_form
 * @property string $web_page
 * @property int $big
 */
class Organization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%proposal_organization}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'legal_form', 'web_page', 'big'], 'required'],
            [['big', 'legal_form'], 'integer'],
            [['name'], 'string', 'max' => 40],
            [['web_page'], 'string', 'max' => 90],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre de la organización',
            'legal_form' => 'Forma legal',
            'web_page' => 'Página web',
            'big' => 'Si eres una empresa o fundación corporativa, ¿tiene tu empresa o empresa asociada a la fundación menos de 250 empleados?',
        ];
    }

    /**
     * {@inheritdoc}
     * @return OrganizationQuery the active query used by this AR class.
     */
    public static function find(): OrganizationQuery
    {
        return new OrganizationQuery(get_called_class());
    }
}
