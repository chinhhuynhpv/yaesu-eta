<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 入力値検証用の言語ファイル
    |--------------------------------------------------------------------------
    */

    'accepted'        => ':attributeを承認してください。',
    'accepted_if'     => ':otherが:value の場合、:attributeを承認してください。',
    'active_url'      => ':attributeは、有効なURLではありません。',
    'after'           => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal'  => ':attributeには、:date以降の日付を指定してください。',
    'alpha'           => ':attributeには、アルファベッドのみ使用できます。',
    'alpha_dash'      => ":attributeには、英数字('A-Z','a-z','0-9')とハイフンと下線('-','_')が使用できます。",
    'alpha_num'       => ":attributeには、英数字('A-Z','a-z','0-9')が使用できます。",
    'array'           => ':attributeには、配列を指定してください。',
    'before'          => ':attributeには、:dateより前の日付を指定してください。',
    'before_or_equal' => ':attributeには、:date以前の日付を指定してください。',
    'between'         => [
        'numeric' => ':attributeには、:minから、:maxまでの数字を指定してください。',
        'file'    => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'string'  => ':attributeは、:min文字から:max文字にしてください。',
        'array'   => ':attributeの項目は、:min個から:max個にしてください。',
    ],
    'boolean'              => ":attributeには、'true'か'false'を指定してください。",
    'confirmed'            => ':attributeと:attribute確認が一致しません。',
    'current_password'     => 'パスワードが間違っています。.',
    'date'                 => ':attributeは、正しい日付ではありません。',
    'date_equals'          => ':attributeは:dateに等しい日付でなければなりません。',
    'date_format'          => ":attributeの形式は、':format'と合いません。",
    'different'            => ':attributeと:otherには、異なるものを指定してください。',
    'digits'               => ':attributeは、:digits桁にしてください。',
    'digits_between'       => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions'           => ':attributeの画像サイズが無効です',
    'distinct'             => ':attributeの値が重複しています。',
    'email'                => ':attributeは、有効なメールアドレス形式で指定してください。',
    'ends_with'            => 'The :attribute must end with one of the following: :values',
    'exists'               => '選択された:attributeは、有効ではありません。',
    'file'                 => ':attributeはファイルでなければいけません。',
    'filled'               => ':attributeは必須です。',
    'gt'                   => [
        'numeric' => ':attributeは、:valueより大きくなければなりません。',
        'file'    => ':attributeは、:value KBより大きくなければなりません。',
        'string'  => ':attributeは、:value文字より大きくなければなりません。',
        'array'   => ':attributeの項目数は、:value個より大きくなければなりません。',
    ],
    'gte'                  => [
        'numeric' => ':attributeは、:value以上でなければなりません。',
        'file'    => ':attributeは、:value KB以上でなければなりません。',
        'string'  => ':attributeは、:value文字以上でなければなりません。',
        'array'   => ':attributeの項目数は、:value個以上でなければなりません。',
    ],
    'image'                => ':attributeには、画像を指定してください。',
    'in'                   => '選択された:attributeは、有効ではありません。',
    'in_array'             => ':attributeが:otherに存在しません。',
    'integer'              => ':attributeには、整数を指定してください。',
    'ip'                   => ':attributeには、有効なIPアドレスを指定してください。',
    'ipv4'                 => ':attributeはIPv4アドレスを指定してください。',
    'ipv6'                 => ':attributeはIPv6アドレスを指定してください。',
    'json'                 => ':attributeには、有効なJSON文字列を指定してください。',
    'lt'                   => [
        'numeric' => ':attributeは、:valueより小さくなければなりません。',
        'file'    => ':attributeは、:value KBより小さくなければなりません。',
        'string'  => ':attributeは、:value文字より小さくなければなりません。',
        'array'   => ':attributeの項目数は、:value個より小さくなければなりません。',
    ],
    'lte'                  => [
        'numeric' => ':attributeは、:value以下でなければなりません。',
        'file'    => ':attributeは、:value KB以下でなければなりません。',
        'string'  => ':attributeは、:value文字以下でなければなりません。',
        'array'   => ':attributeの項目数は、:value個以下でなければなりません。',
    ],
    'max'                  => [
        'numeric' => ':attributeには、:max以下の数字を指定してください。',
        'file'    => ':attributeには、:max KB以下のファイルを指定してください。',
        'string'  => ':attributeは、:max文字以下にしてください。',
        'array'   => ':attributeの項目は、:max個以下にしてください。',
    ],
    'mimes'                => ':attributeには、:valuesタイプのファイルを指定してください。',
    'mimetypes'            => ':attributeには、:valuesタイプのファイルを指定してください。',
    'min'                  => [
        'numeric' => ':attributeには、:min以上の数字を指定してください。',
        'file'    => ':attributeには、:min KB以上のファイルを指定してください。',
        'string'  => ':attributeは、:min文字以上にしてください。',
        'array'   => ':attributeの項目は、:min個以上にしてください。',
    ],
    'multiple_of'          => ':attributeには、:valueの倍数を指定してください。',
    'not_in'               => '選択された:attributeは、有効ではありません。',
    'not_regex'            => ':attributeの形式が無効です。',
    'numeric'              => ':attributeには、数字を指定してください。',
    'password'             => ':attributeが間違っています',
    'present'              => ':attributeが存在している必要があります。',
    'regex'                => ':attributeには、有効な形式で入力してください。',
    'required'             => ':attributeは、必ず指定してください。',
    'required_if'          => ':otherが:valueの場合、:attributeを指定してください。',
    'required_unless'      => ':otherが:values以外の場合、:attributeを指定してください。',
    'required_with'        => ':valuesが指定されている場合、:attributeも指定してください。',
    'required_with_all'    => ':valuesが全て指定されている場合、:attributeも指定してください。',
    'required_without'     => ':valuesが指定されていない場合、:attributeを指定してください。',
    'required_without_all' => ':valuesが全て指定されていない場合、:attributeを指定してください。',
    'prohibited'           => ':attributeは入力禁止です。',
    'prohibited_if'        => ':otherが:valueの場合、:attributeは入力禁止です。',
    'prohibited_unless'    => ':otherが:values以外の場合、:attributeは入力禁止です。',
    'same'                 => ':attributeと:otherが一致しません。',
    'size'                 => [
        'numeric' => ':attributeには、:sizeを指定してください。',
        'file'    => ':attributeには、:size KBのファイルを指定してください。',
        'string'  => ':attributeは、:size文字にしてください。',
        'array'   => ':attributeの項目は、:size個にしてください。',
    ],
    'starts_with'          => ':attributeは、次のいずれかで始まる必要があります。:values',
    'string'               => ':attributeには、文字を指定してください。',
    'timezone'             => ':attributeには、有効なタイムゾーンを指定してください。',
    'unique'               => '指定の:attributeは既に使用されています。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeは、有効なURL形式で指定してください。',
    'uuid'                 => ':attributeは、有効なUUIDでなければなりません。',
    'zip_code'             => ':attributeは、ハイフン「-」有りで半角英数字7個の数字で入力してください。',
    'phone_number'         => ':attributeは、ハイフン「-」有りで半角英数字8~11個の数字で入力してください。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'Eメールアドレス',
        'contract_name' => '契約者名',
        'contract_name_kana' => '契約者名カナ',
        'representative_position' => '代表者役職',
        'representative_name' => '代表者名',
        'representative_name_kana' => '代表者名カナ',
        'billing_department' => '請求先_担当部署',
        'billing_manager_position' => '請求先_担当者役職',
        'billing_manager_name' => '請求先_担当者名',
        'billing_post_number' => '請求先_郵便番号',
        'billing_prefectures' => '請求先_都道府県',
        'billing_municipalities' => '請求先_市区町村',
        'billing_address' => '請求先_番地',
        'billing_building' => '請求先_ビル名',
        'billing_tel' => '請求先_TEL',
        'billing_fax' => '請求先_FAX',
        'billing_email' => '請求先_Eメールアドレス',
        'billing_shipping' => '請求書郵送',
        'shipping_destination' => '出荷先',
        'shipping_post_number' => '指定出荷先_郵便番号',
        'shipping_prefectures' => '指定出荷先_都道府県',
        'shipping_municipalities' => '指定出荷先_市区町村',
        'shipping_address' => '指定出荷先_番地',
        'shipping_building' => '指定出荷先_ビル名',
        'shipping_tel' => '指定出荷先_TEL',
        'shipping_fax' => '指定出荷先_FAX',
        'payment_type' => '支払い方法',
        'bank_num' => '銀行番号',
        'bank_name' => '銀行名',
        'branchi_num' => '支店番号',
        'branchi_name' => '支店名',
        'deposit_type' => '預金種目',
        'account_num' => '口座番号',
        'account_name' => '口座名義',
        'bank_entruster_num' => '銀行委託者番号',
        'bank_customer_num' => '銀行顧客者番号',
        'status' => 'ステータス',

        'start date of plan' => '利用開始日',
        'startDateOfPlan' => '利用開始日',
        'rowNum' => '行番号',
        'voipIdName' => 'VOIPID名称',
        'seri_number' => '回線番号',
        'priority' => '優先',
        'groupName' => 'グループ名',
        'groupMemberView' => 'メンバー表示',
        'groupResponsiblePerson' => 'グループ責任者',
        'voipLinePassword' => 'VOIPID名称',
        'group_main' => '主グループ',
        'career' => 'キャリア',
        'sim_num' => 'SIM番号',
        'line_num' => '回線番号',
        'line num' => '回線番号',
        'voip_line_id' => 'VOIP回線ID',
        'voip line id' => 'VOIP回線ID'
    ],
    'values' => [
        'shipping_destination' => [
            '3' => '「指定する」'
        ],
    ],
];
