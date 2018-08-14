<?php
namespace yii\easyii\modules\news\models;

use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\Taggable;
use yii\easyii\components\helpers\LanguageHelper;
use yii\easyii\models\Photo;
use yii\easyii\models\SeoText;
use yii\easyii\models\Tag;
use yii\helpers\StringHelper;
use \yii\easyii\components\ActiveRecord;

/**
 * Class News
 * @package yii\easyii\modules\news\models
 *
 * @property integer $news_id
 * @property string  $image
 * @property string  $slug
 * @property string  $category
 * @property integer $time
 * @property integer $views
 * @property integer $status
 * @property string  $title
 * @property string  $text
 * @property integer $on_main
 *
 * @property Tag[]   $tags
 * @property SeoText $seo
 */
class News extends ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    /**
     * @return MultilingualQuery
     */
    public static function find() {
        return new MultilingualQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'easyii_news';
    }

    public function rules()
    {
        return [
            [['title', 'short', 'text'], 'trim'],
            [['category'], 'string'],
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            [['views', 'time', 'status', 'on_main'], 'integer'],
            ['time', 'default', 'value' => time()],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON],
            ['tagNames', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'text'  => Yii::t('easyii', 'Text'),
            'short' => Yii::t('easyii/questions', 'Short'),
            'image' => Yii::t('easyii', 'Image'),
            'time'  => Yii::t('easyii', 'Date'),
            'slug'  => Yii::t('easyii', 'Slug'),
            'tagNames' => Yii::t('easyii', 'Tags'),
            'category' => Yii::t('easyii', 'Category'),
            'on_main' => Yii::t('easyii', 'To main page'),
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'multilingual' => [
                'class'           => MultilingualBehavior::className(),
                'languages'       => LanguageHelper::getLanguages(),
                'defaultLanguage' => Yii::$app->language,
                'langForeignKey'  => 'news_id',
                'tableName'       => 'news_i18n',
                'attributes'      => [
                    'title', 'text', 'short'
                ],
            ],
            'seoBehavior' => SeoBehavior::className(),
            'taggabble' => Taggable::className(),
            'sluggable' => [
                'class'        => SluggableBehavior::className(),
                'attribute'    => 'title',
                'ensureUnique' => true
            ],
        ]);
    }

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), [
            'item_id' => 'news_id'
        ])
            ->where([
                'class' => self::className()
            ])
            ->sort();
    }

    /**
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->activeModules['news']->settings;
            $this->short = StringHelper::truncate($settings['enableShort'] ? $this->short : strip_tags($this->text), $settings['shortMaxLength']);

            if(!$insert && $this->image !== $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }
            return true;
        }

        return false;
    }

    
    public function afterDelete()
    {
        parent::afterDelete();

        if($this->image){
            @unlink(Yii::getAlias('@webroot').$this->image);
        }
        
        /** $photo yii\easyii\models\Photo */
        foreach($this->getPhotos()->all() as $photo) {
            $photo->delete();
        }
    }
}