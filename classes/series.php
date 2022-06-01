<?php

class Series
{
    private $series_storage;
    private $series = NULL;

    public function __construct(IStorage $series_storage)
    {
        $this->series_storage = $series_storage;

        if (isset($_SESSION["series"])) {
            $this->series = $_SESSION["series"];
        }
    }

    public function getAll()
    {
        return $this->series_storage->findAll();
    }

    public function getSerieById($id)
    {
        return $this->series_storage->findById($id);
    }

    public function getSeriesById($ids){
        $series = [];
        foreach($this->series_storage->findAll() as $serie){
            foreach($ids as $val){
                if($val === $serie['id']) $series[]=$serie;
            }  
        }
        return $series;
    }

    public function getNotStartedSeries($started_series){
        $series = [];

        foreach($this->series_storage->findAll() as $serie){
            $isIn = false;
            foreach($started_series as $s_serie){
                if($s_serie['id'] === $serie['id']) $isIn = true;
            }
            if(!$isIn){
                $series []= $serie;
            }
        }

        return $series;
    }

    public function getEpisodeById($s_id, $e_id)
    {
        $found_serie = $this->getSerieById($s_id);
            foreach ($found_serie['episodes'] as $episode) {
                if ($episode['id'] === $e_id) {
                    return $episode;
                }
            }
        return null;
    }

    public function getTheRecentEpisodeDate($serie){
        $current = null;
        $max = 0;
        foreach ($serie['episodes'] as $episode) {
            if ($max < strtotime($episode['year'])) {
                $max = strtotime($episode['year']);
                $current = $episode['year'];
            }
        }
        return $current;
    }

    public function getTheMostPopularRating($serie){
        $max = 0;
        foreach($serie['episodes'] as $episode){
            if($max < $episode['rating']){
                $max = $episode['rating'];
            }
        }
        return $max;
    }

    public function getLastFiveAddedSeries()
    {
        return ksort($this->series);
    }

    public function updateSerie($id, $data)
    {
        $this->series_storage->update($id, $data);
    }

    public function updateEpisode($s_id, $data)
    {
        $selected_series = $this->series_storage->findById($s_id);
        $selected_series['episodes'][$data['id']] = $data;
        $this->updateSerie($s_id,$selected_series);
    }

    public function deleteSeries($id)
    {
        $this->series_storage->delete($id);
    }

    public function serieExist($title){
        $series = $this->series_storage->findOne(['title' => $title]);
        return !is_null($series);
    }

    public function addSerie($data){
        $this->series_storage->add($data);
    }

    public function addEpisodeToSerie($s_id, $data)
    {
        $selected_series = $this->series_storage->findById($s_id);
        $id = uniqid();
        $data['id'] = $id;
        $selected_series['episodes'][$id] = $data;
        $this->updateSerie($s_id, $selected_series);
    }

    public function deleteEpisode($s_id, $e_id)
    {
        $selected_series = $this->series_storage->findById($s_id);
        //TODO
    }
}
