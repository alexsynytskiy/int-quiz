<?php

namespace yii\easyii\modules\news\controllers;

use app\models\Answer;
use app\models\Question;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use yii\easyii\helpers\Image;
use yii\easyii\modules\news\models\QuestionSaver;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class AController extends Controller
{
    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Question::find()->orderBy('group_id')
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
            }

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

                if ($answer1->save() && $answer2->save() && $answer3->save() && $answer4->save()) {
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
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\HttpException
     */
    public function actionEdit($id)
    {
        /** @var Question $model */
        $question = Question::find()->where(['id' => $id])->one();

        if ($question === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        $model = new QuestionSaver();
        $model->text = $question->text;
        $model->correct_answer = $question->correct_answer;
        $model->group_id = $question->group_id;
        $model->image = $question->image;
        $model->reward = $question->reward ?: 1;

        $answers = Answer::find()->where(['question_id' => $id])->orderBy('id')->all();

        if(count($answers) > 0) {
            if(count($answers) >= 1) {
                $model->answerOneText = $answers[0]->text;
                $model->answerOneCorrect = $answers[0]->is_correct;
            }

            if(count($answers) >= 2) {
                $model->answerTwoText = $answers[1]->text;
                $model->answerTwoCorrect = $answers[1]->is_correct;
            }

            if(count($answers) >= 3) {
                $model->answerThreeText = $answers[2]->text;
                $model->answerThreeCorrect = $answers[2]->is_correct;
            }

            if(count($answers) >= 4) {
                $model->answerFourText = $answers[3]->text;
                $model->answerFourCorrect = $answers[3]->is_correct;
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if (isset($_FILES) && $this->module->settings['enableThumb']) {
                $model->image = UploadedFile::getInstance($model, 'image');
                if ($model->image && $model->validate(['image'])) {
                    $model->image = Image::upload($model->image, 'news');
                }
            }

            $question->text = $model->text;
            $question->correct_answer = $model->correct_answer;
            $question->group_id = $model->group_id;
            $question->image = $model->image;
            $question->reward = $model->reward ?: 1;

            $answers = Answer::find()->where(['question_id' => $id])->orderBy('id')->all();

            if ($question->update() && count($answers) > 0) {
                if(count($answers) >= 1) {
                    $answers[0]->text = $model->answerOneText;
                    $answers[0]->is_correct = $model->answerOneCorrect;
                    $answers[0]->question_id = $question->id;
                }

                if(count($answers) >= 2) {
                    $answers[1]->text = $model->answerTwoText;
                    $answers[1]->is_correct = $model->answerTwoCorrect;
                    $answers[1]->question_id = $question->id;
                }

                if(count($answers) >= 3) {
                    $answers[2]->text = $model->answerThreeText;
                    $answers[2]->is_correct = $model->answerThreeCorrect;
                    $answers[2]->question_id = $question->id;
                }

                if(count($answers) >= 4) {
                    $answers[3]->text = $model->answerFourText;
                    $answers[3]->is_correct = $model->answerFourCorrect;
                    $answers[3]->question_id = $question->id;
                }

                $count = 0;

                foreach ($answers as $answer) {
                    if($answer->update()) {
                        $count++;
                    }
                }

                if ($count === count($answers)) {
                    $this->flash('success', Yii::t('easyii/questions', 'Question updated'));
                    return $this->redirect(['/admin/' . $this->module->id]);
                }
            } else {
                $this->flash('error', VarDumper::export($question->getErrors()));
                foreach ($answers as $answer) {
                    $this->flash('error', VarDumper::export($answer->getErrors()));
                }

                return $this->refresh();
            }
        }

        return $this->render('edit', [
            'model' => $model
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
        if ($question = Question::find()->where(['id' => $id])->one()) {
            $question->delete();

            $answers = Answer::find()->where(['question_id' => $id])->orderBy('id')->all();

            foreach ($answers as $answer) {
                $answer->delete();
            }
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/questions', 'Question and answers deleted'));
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
        $model = Question::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
        } else {
            $model->image = '';
            if ($model->update()) {
                @unlink(Yii::getAlias('@webroot') . $model->image);
                $this->flash('success', Yii::t('easyii', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->getErrors()));
            }
        }
        return $this->back();
    }
}