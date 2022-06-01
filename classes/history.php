<?php

class History
{
    private $storage;
    private $history = NULL;

    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;

        if (isset($_SESSION["history"])) {
            $this->history = $_SESSION["history"];
        }
    }

    public function getUserStartedSeries($user){
        if (is_null($user)) {
            return false;
        }

        $series = [];

        $found = $this->storage->findOne(["user_id" => $user['id']]);
        //TODO: nem adja vissza rendesen
        foreach ($found['series'] as $serie) {
            if(!isset($serie['episodes'])) continue;
            else if(count($serie['episodes']) > 0) $series [] = $serie['serie_id'];
        }

        return $series;
    }

    public function getUserSawThisEpisode($user,$serie_id, $episode_id)
    {
        if(is_null($user)){
            return false;
        }
        $found = $this->storage->findOne(["user_id"=> $user['id']]);
        foreach($found['series'] as $serie){
           foreach($serie['episodes'] as $episode){
               if($episode === $episode_id){
                   return true;
               }
           }
        }
        return false;
    }

    public function setUserSawThisEpisode($user, $serie_id,$episode_id)
    {
        if($user === null){
            return false;
        }
        $id = $user['id'];
        $old = $this->storage->findOne(['user_id' => $user['id']]);
        if(!isset($old['series'][$serie_id])){
            $old['series'][$serie_id] = [
                 'serie_id' => $serie_id,
                 'episodes' => [$episode_id]
                ];
        }
        else{
            $old['series'][$serie_id]['episodes'][]=$episode_id;
        }
        $this->storage->update($id,$old);
    }
}