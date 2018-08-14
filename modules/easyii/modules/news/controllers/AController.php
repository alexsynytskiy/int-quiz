<?php

namespace yii\easyii\modules\news\controllers;

use app\models\Answer;
use app\models\Question;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\SortableDateController;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use yii\easyii\components\helpers\CategoryHelper;
use yii\easyii\helpers\Image;
use yii\easyii\modules\news\models\News;
use yii\easyii\modules\news\models\QuestionSaver;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableDateController::className(),
                'model' => News::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Question::find()->orderBy('created_at')
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    /**
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionCreate()
    {
        $model = new QuestionSaver();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if (isset($_FILES)) {
                    $model->image = UploadedFile::getInstance($model, 'image');

                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'news');
                    } else {
                        $model->image = '';
                    }
                }

                $question = new Question();
                $question->text = $model->text;
                $question->correct_answer = $model->correct_answer;
                $question->group_id = $model->group_id;
                $question->image = $model->image;
                $question->reward = $model->reward ?: 1;

                $answer1 = new Answer();
                $answer2 = new Answer();
                $answer3 = new Answer();
                $answer4 = new Answer();

                if ($question->save()) {
                    $answer1->text = $model->answerOneText;
                    $answer1->is_correct = $model->answerOneCorrect;
                    $answer1->question_id = $question->id;

                    $answer2->text = $model->answerTwoText;
                    $answer2->is_correct = $model->answerTwoCorrect;
                    $answer2->question_id = $question->id;

                    $answer3->text = $model->answerThreeText;
                    $answer3->is_correct = $model->answerThreeCorrect;
                    $answer3->question_id = $question->id;

                    $answer4->text = $model->answerFourText;
                    $answer4->is_correct = $model->answerFourCorrect;
                    $answer4->question_id = $question->id;

                    if($answer1->save() && $answer2->save() && $answer3->save() && $answer4->save()) {
                        $this->flash('success', Yii::t('easyii/questions', 'Question created'));
                        return $this->redirect(['/admin/' . $this->module->id]);
                    }
                } else {
                    $this->flash('error', VarDumper::export($question->getErrors()));
                    $this->flash('error', VarDumper::export($answer1->getErrors()));
                    $this->flash('error', VarDumper::export($answer2->getErrors()));
                    $this->flash('error', VarDumper::export($answer3->getErrors()));
                    $this->flash('error', VarDumper::export($answer4->getErrors()));

                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param $id
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionEdit($id)
    {
        $model = News::find()->where(['news_id' => $id])->multilingual()->one();

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if (isset($_FILES) && $this->module->settings['enableThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'news');
                    } else {
                        $model->image = $model->oldAttributes['image'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/questions', 'News updated'));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionPhotos($id)
    {
        if (!($model = News::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if (($model = News::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/questions', 'News deleted'));
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionClearImage($id)
    {
        $model = News::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
        } else {
            $model->image = '';
            if ($model->update()) {
                @unlink(Yii::getAlias('@webroot') . $model->image);
                $this->flash('success', Yii::t('easyii', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, News::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, News::STATUS_OFF);
    }
}