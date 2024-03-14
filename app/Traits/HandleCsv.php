<?php

namespace App\Traits;

trait HandleCsv
{
    /**
     * CSV文字列に変換する。
     * @param array $rowDataList    CSVデータリスト
     * @return string CSV文字列
     */
    public function toCsv($rowDataList) {
        $csv = '';

        foreach ($rowDataList as $row) {
            // ダブルクォーテーションで囲う
            $row = array_map(function($value) { return $this->quote($value); }, $row);
            // カンマ区切り
            $row = implode(',', $row);
            $csv .= $row . "\r\n";
        }

        return $csv;
    }
    
    /**
     * CSV用に文字列をダブルクォーテーションで囲む
     * @param string $str		文字列
     * @return string   ダブルクォーテーションで囲んだ文字列
     */
    public function quote($str) {
        if (is_null($str) || $str === '') {
            return '""';
        }

        // 明示的に文字列に変換する。
        $str = (string)$str;

        // 文字列中にダブルクォーテーションが含まれる場合は、
        // ダブルクォーテーション２つに置き換える。
        $escapeStr = preg_replace('/"/', '""', $str);

        // 前後にダブルクォーテーションを付ける。
        return '"' . $escapeStr . '"';
    }
}
