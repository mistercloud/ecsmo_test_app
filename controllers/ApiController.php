<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 22.05.14
 * Time: 15:17
 */

namespace app\controllers;


use app\models\Cinema;
use app\models\Film;
use app\models\Session;
use app\models\Ticket;
use app\models\TicketPlace;
use yii\web\Controller;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii;
class ApiController extends Controller {


    public function init(){
        $this->enableCsrfValidation = false;
    }
    /**
     * Список сеансов кинотеатра
     * @param $name - транслитерация названия кинотеатра
     * @return string
     */
    public function actionCinema($name){
        //пытаемся получить кинотеатр по названию
        $cinema = Cinema::findOne(['name_eng' => $name]);
        if (!$cinema){
            return json_encode(['status' => 1 , 'error' => 'not found']);
        }


        $sessions = Session::find([
            'cinema_id' => $cinema->id,
            'start_at > NOW()',
        ])->with('film','hall');

        $hall_id = (int)Yii::$app->request->get('hall');
        if ($hall_id){
            $sessions->where(['hall_id'=>$hall_id]);
        }
        $sessions = $sessions->all();

        $return = ['status'=>0,'data'=>[]];
        foreach($sessions as $session){
            $return['data'][] = [
                'session' => $session->id,
                'hall'=> $session->hall->id,
                'film' => $session->film->name,
                'start_at' => $session->start_at,
            ];
        }

        return json_encode($return);
    }

    /**
     * Список кинотеатров в которых идет фильм
     * @param $name
     * @return string
     */
    public function actionFilm($name){
        $film = Film::findOne(['name_eng' => $name]);
        if (!$film){
            return json_encode(['status' => 1 , 'error' => 'not found']);
        }

        //получаем все сессии где идет этот фильм
        $sessions = Session::find([
            'film_id' => $film->id,
            'start_at > NOW()',
        ])->with('hall')->all();

        $return = ['status'=>0,'data'=>[]];
        foreach($sessions as $session){
            $return['data'][] = [
                'session' => $session->id,
                'cinema' => $session->hall->cinema->name,
                'hall'=> $session->hall->id,
                'start_at' => $session->start_at,
            ];
        }

        return json_encode($return);

    }

    /**
     * Список свободных мест на сеансе
     * @param $id
     * @return string
     */
    public function actionSession($id){
        $session = Session::findOne($id);
        if (!$session){
            return json_encode(['status' => 1 , 'error' => 'not found']);
        }


        $return = ['status'=>0,'data'=>['places' => $session->getFreePlaces()]];

        return json_encode($return);


    }

    public function actionBuy(){
        $session = (int)Yii::$app->request->post('session');
        $places = Yii::$app->request->post('places');

        if (!$session || !$places){
            return json_encode(['status' => 2 , 'error' => 'bad input']);
        }

        $places = explode(',',$places);
        foreach($places as $place_id){
            if (!is_numeric($place_id)){
                return json_encode(['status' => 2 , 'error' => 'bad input']);
            }
        }

        $session = Session::findOne($session);
        if (!$session){
            return json_encode(['status' => 1 , 'error' => 'not found']);
        }

        //проверяем не заняты ли места
        if (array_diff($places,$session->getFreePlaces())){
            return json_encode(['status' => 3 , 'error' => 'places are no free']);
        }

        //выполняем в транзакци на случай если будет пересечение по местам
        $db = ActiveRecord::getDb();
        $transaction = $db->beginTransaction();
        try {
            //создаем билет
            $ticket = new Ticket();
            $ticket->session_id = $session->id;
            $ticket->code = substr(md5(time()),0,10);
            $ticket->created_at = new Expression('NOW()');
            $ticket->save();
            foreach($places as $place_id){
                $place = new TicketPlace();
                $place->ticket_id = $ticket->id;
                $place->hall_id = $session->hall_id;
                $place->hall_place_id = $place_id;
                $place->cinema_id = $session->cinema_id;
                $place->save();
            }
            $transaction->commit();
            return json_encode([
                'status' => 0 ,
                'data' => $ticket->id.'_'.$ticket->code,
            ]);

        } catch (\Exception $e) {
            $transaction->rollBack();
            return json_encode(['status' => 3 , 'error' => 'places are no free']);
        }


    }

    public function actionReject($code){

        list($ticket_id,$ticket_code) = explode('_',$code);
        if (!$ticket_id || !$ticket_code){
            return json_encode(['status' => 2 , 'error' => 'bad input']);
        }

        //пытаемся найти билет
        $ticket = Ticket::findOne([
            'id' => $ticket_id,
            'code' => $ticket_code,
        ]);
        if (!$ticket){
            return json_encode(['status' => 1 , 'error' => 'not found']);
        }

        //проверяем не подошло ли время к сеансу
        if (strtotime($ticket->session->start_at)  < time() + 60*60){
            return json_encode(['status' => 4 , 'error' => 'session to close']);
        }

        //удаляем билет
        $ticket->delete();

        return json_encode(['status' => 0 , 'data' => '']);
    }
} 