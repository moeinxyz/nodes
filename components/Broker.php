<?php
namespace app\components;

use yii\base\Component;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;
use yii\helpers\Json;

class Broker extends Component{
    
    private static $queues      =   [];
    private static $connection  =   null;
    private static $channel     =   null;

    /**
     * 
     * @return AMQPConnection
     */
    private static function getConnection()
    {
        if (self::$connection == NULL)
        {
            $server     =   \Yii::$app->params['rabbitmq']['server'];
            $port       =   \Yii::$app->params['rabbitmq']['port'];
            $username   =   \Yii::$app->params['rabbitmq']['username'];
            $password   =   \Yii::$app->params['rabbitmq']['password'];
            $vhost      =   \Yii::$app->params['rabbitmq']['vhost'];
            self::$connection   =   new AMQPConnection($server,$port,$username,$password,$vhost);
        }
        return self::$connection;
    }

    /**
     * 
     * @return AMQPChannel
     */
    public static function getChannel()
    {
        if (self::$channel == NULL)
        {
            $conn   =   self::getConnection();
            self::$channel  =   $conn->channel();
        }
        return self::$channel;
    }
    
    public static function declareQueue($queue)
    {
        if (!in_array($queue, self::$queues))
        {
            $ch =   self::getChannel();
            $ch->queue_declare($queue, false, true, false, false);
            self::$queues[] =   $queue;
        }
    }
    
    private static function generateAMPQMessage($message)
    {
        return new AMQPMessage(Json::encode($message),[
            'content_type'  =>  'text/plain',
            'delivery_mode' =>  2
        ]);
    }
    
    public static function publishMessage($message,$queue)
    {
        $ch =   self::getChannel();
        self::declareQueue($queue);
        $msg    =   self::generateAMPQMessage($message);
        $ch->basic_publish($msg, '',$queue);
    }
    
    public static function sendAck($message)
    {
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }

    public static function consumeMessage($queue,$callback)
    {
        $ch =   self::getChannel();
        self::declareQueue($queue);
        $ch->basic_qos(null, 1, null);
        $ch->basic_consume($queue, '', false, false, false, false, $callback);
        while (count($ch->callbacks)) {
            $ch->wait();
        }
    }

    public static function close()
    {
        if (self::$channel != NULL)
        {
            self::$channel->close();
        }
        
        if (self::$connection != NULL)
        {
            self::$connection->close();
        }
    }
}