<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%video}}".
 *
 * @property string $video_id
 * @property string $title
 * @property string|null $description
 * @property string|null $tags
 * @property int|null $status
 * @property int|null $has_thumbnail
 * @property string|null $video_name
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 */
class Video extends \yii\db\ActiveRecord
{

    /**
     * @var \yii\web\UpLoadedFile
     */
    public $video;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%video}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'tags', 'status', 'has_thumbnail', 'video_name', 'created_at', 'updated_at', 'created_by'], 'default', 'value' => null],
            [['video_id', 'title'], 'required'],
            [['description'], 'string'],
            [['status', 'has_thumbnail', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['video_id'], 'string', 'max' => 16],
            [['title', 'tags', 'video_name'], 'string', 'max' => 512],
            [['video_id'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['videoFile'], 'file', 'extensions' => 'mp4, mov, avi', 'maxSize' => 1024 * 1024 * 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'video_id' => 'Video ID',
            'title' => 'Title',
            'description' => 'Description',
            'tags' => 'Tags',
            'status' => 'Status',
            'has_thumbnail' => 'Has Thumbnail',
            'video_name' => 'Video Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\VideoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\VideoQuery(get_called_class());
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $isInsert = $this->isNewRecord;
        
        if ($isInsert) {
            // Gera um ID único para o vídeo
            $this->video_id = Yii::$app->security->generateRandomString(8);
            
            // Define o título baseado no nome do arquivo (se existir)
            if ($this->videoFile instanceof UploadedFile) {
                $this->title = $this->videoFile->name;
                $this->video_name = $this->videoFile->name;
            }
        }
        
        // Salva o modelo no banco de dados
        if (!parent::save($runValidation, $attributeNames)) {
            return false;
        }
        
        // Se for uma inserção e houver um arquivo, salva o arquivo
        if ($isInsert && $this->videoFile instanceof UploadedFile) {
            $videoPath = $this->getVideoPath();
            
            // Cria o diretório se não existir
            if (!is_dir(dirname($videoPath))) {
                FileHelper::createDirectory(dirname($videoPath));
            }
            
            // Salva o arquivo físico
            if (!$this->videoFile->saveAs($videoPath)) {
                $this->addError('videoFile', 'Falha ao salvar o arquivo no servidor.');
                return false;
            }
        }

        return true;
    }

    protected function getVideoPath()
    {
        return Yii::getAlias('@frontend/web/storage/videos/' . $this->video_id . '.' . $this->videoFile->extension);
    }
}
