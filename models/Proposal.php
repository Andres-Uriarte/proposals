<?php

namespace u4impact\humhub\modules\proposal\models;

use \yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%proposal_proposal}}".
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $status
 * @property string $timestamp
 * @property string $consent
 * @property string $rrss_consent
 * @property string $organization_area
 * @property string $title
 * @property string $objectives
 * @property string $short_description
 * @property string $expected_progress
 * @property string $expected_progress_indicators
 * @property string $impact
 * @property string $related_links
 * @property string $sdg
 * @property string $student_academic_profile
 * @property string $student_profile
 * @property string $requirements
 * @property string $organization
 * @property int|null $space_id
 */
class Proposal extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%proposal_proposal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['timestamp'], 'safe'],
            [['consent', 'rrss_consent', 'organization_area', 'title', 'objectives', 'short_description', 'expected_progress', 'expected_progress_indicators', 'impact', 'related_links', 'sdg', 'student_academic_profile', 'student_profile', 'requirements', 'organization'], 'required'],
            [['organization_id', 'space_id'], 'integer'],
            [['status', 'title'], 'string', 'max' => 40],
            [['consent', 'rrss_consent', 'organization_area', 'objectives', 'short_description', 'expected_progress', 'expected_progress_indicators', 'impact', 'related_links', 'sdg', 'student_academic_profile', 'student_profile', 'requirements', 'organization'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID Propuesta',
            'organization_id' => 'ID Organización',
            'status' => 'Estado',
            'timestamp' => 'Fecha',
            'consent' => 'Consiento que TFCoop utilice mis datos conforme a los términos y condiciones*',
            'rrss_consent' => '¿Consientes la utilización de imágenes de tu web y otros materiales gráficos para preparación de los materiales de difusión de tu propuesta a los estudiantes? Esto aumentará la visibilidad de tu propuesta.',
            'organization_area' => 'Breve descripción del proyecto u área de actividad al que va asociada la propuesta ',
            'title' => 'Título',
            'objectives' => '¿Qué objetivos tiene esta propuesta?',
            'short_description' => 'Breve descripción de la propuesta',
            'expected_progress' => 'Describe brevemente cuál es la teoría del cambio de esta propuesta. Es decir la transformación o el cambio que se quiere ver al finalizar el desarrollo de la misma. Para ello define: 1. de qué punto se parte 2. a qué punto se quiere llegar',
            'expected_progress_indicators' => '¿Cómo vas a medir si al final del trabajo del estudiante se ha dado esta transformación? A ser posible define indicadores.',
            'impact' => 'Describe el impacto social o medioambiental del proyecto o actividad de tu organización al que va vinculada la propuesta. ¿Cómo lo mides?',
            'related_links' => 'Enlaces de interés al proyecto',
            'sdg' => 'ODS a los que aplica tu propuesta/o tu proyecto',
            'student_academic_profile' => 'Perfil académico necesario (del alumno que realizará la propuesta)',
            'student_profile' => 'Específica más el perfil académico',
            'requirements' => '¿Existe algún otro requisito conforme al perfil del estudiante o la Universidad? ¿Motivaciones o intereses específicos?						',
            'organization' => 'Nombre de la organización',
            'space_id' => 'Space ID',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ProposalQuery the active query used by this AR class.
     */
    public static function find(): ProposalQuery
    {
        return new ProposalQuery(get_called_class());
    }
}
