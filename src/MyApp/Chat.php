<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $users = array("name"=>array(),"id"=>array());

    public function __construct() {
        array_push($this->users['name'], 'a');
        array_push($this->users['id'], 0);
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        $data = json_decode($msg,true);
        if($data['new'] !== 'n'){
            echo $data['user']." connected at ".$from->resourceId."\n";
            array_push($this->users['name'], $data['user']);
            array_push($this->users['id'], $from->resourceId);
        }else{
            $user = $data['user'];
            $msgs = $data['msg'];
            $m = json_encode(array("msg"=>$msgs,"from"=>$data['from']));
            $i=array_search($user, $this->users['name']);
            if($i!= '0' || $i!= 0){
                echo sprintf('Connection %d sending message "%s" to connection %d "%s"' . "\n"
            , $from->resourceId, $data['from'],$this->users['id'][$i], $user);
                foreach ($this->clients as $client) {
                    if ($client->resourceId === $this->users['id'][$i]) {
                        $client->send($m);
                    }
                }
            }
        }  
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        $i = array_search($conn->resourceId, $this->users['id']);
        $this->users['name'] = array_values(array_diff($this->users['name'], [$this->users['name'][$i]]));
        $this->users['id'] = array_values(array_diff($this->users['id'], [$conn->resourceId]));
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}