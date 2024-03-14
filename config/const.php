<?php
return [
    // 申請状態
    'm_user_requests' => [
        'status' => [
            // 仮保存
            'temporary' => '1',
            // 申請
            'application' => '2',
            // 差戻し
            'decline' => '3',
            // 設定済み
            'configured' => '4',
        ],
    ],
    // ユーザーラインプラン
    't_user_line_plans' => [
        'line_status' => [
            // 利用中
            'in_use' => '1',
            // 休止中
            'in_pause' => '2',
            // 廃局
            'in_discontinued' => '3',
        ],
    ],
    // 請求計算タイプ
    'billing_calc_type' => [
        // 日割り新規
        'daily_new' => '1',
        // 日割り再開
        'daily_restart' => '2',
        // 通常
        'monthly' => '3',
    ],
    // プランタイプ
    'plan_type' => [
        'main' => '1',
        'option' => '2',
        'promotion' => '3',
        'commission' => '4',
    ],
    // 請求ステータス
    'billing_status' => [
        // 新規
        'new' => '1',
        // 確認済み
        'comfirm' => '2',
        // 引落済み
        'direct_debit' => '3',
        // 再引落し
        'direct_debit_returned' => '4',
    ],
    //指定配送先
    'shipping_destination' => [
        // 契約者
        'user' => '1',
        // 代理店
        'shop' => '2',
        // 指定
        'specify' => '3',
    ],
];