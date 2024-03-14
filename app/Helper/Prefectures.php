<?php

namespace App\Helper;

class Prefectures
{
    const FILE_PATH = 'data/pref_city.json';
    private $jsondata;
    private $dataArr;

    public function __construct()
    {
        $this->jsondata = self::readFile();
        $this->dataArr = json_decode($this->jsondata, true);
    }

    public function getJsonData() {
        return $this->jsondata;
    }

    public function getArray() {
        return $this->dataArr;
    }

    public function checkPrefecture($prefectureID) {
        return isset($this->dataArr[$prefectureID]);
    }

    public function checkCityPrefectureMatching($prefectureID, $cityId) {
        if ($this->checkPrefecture($prefectureID)) {
            $cities = $this->dataArr[$prefectureID]['city'];
            $ids = array_column($cities,'id');

            return array_search($cityId, $ids);
        }

        return false;
    }

    public function getPrefecture($id) {
        return $this->dataArr[$id];
    }

    public function getCity($prefectureID, $cityId) {
        $cities = $this->dataArr[$prefectureID]['city'];
        $ids = array_column($cities,'id');

        return $cities[array_search($cityId, $ids)];
    }

    static function readFile() {
        return file_get_contents(storage_path(self::FILE_PATH), true);
    }
}
