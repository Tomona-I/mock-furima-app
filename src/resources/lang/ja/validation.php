<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attributeを承認してください。',
    'accepted_if'          => ':otherが:valueの場合、:attributeを承認してください。',
    'active_url'           => ':attributeは有効なURLではありません。',
    'after'                => ':attributeは:dateより後の日付にしてください。',
    'after_or_equal'       => ':attributeは:date以降の日付にしてください。',
    'alpha'                => ':attributeはアルファベットのみ使用できます。',
    'alpha_dash'           => ':attributeはアルファベット、数字、ハイフン、アンダースコアのみ使用できます。',
    'alpha_num'            => ':attributeはアルファベットと数字のみ使用できます。',
    'array'                => ':attributeは配列である必要があります。',
    'before'               => ':attributeは:dateより前の日付にしてください。',
    'before_or_equal'      => ':attributeは:date以前の日付にしてください。',
    'between'              => [
        'numeric' => ':attributeは:minから:maxの間にしてください。',
        'file'    => ':attributeは:min KBから:max KBの間のファイルサイズにしてください。',
        'string'  => ':attributeは:minから:max文字の間にしてください。',
        'array'   => ':attributeは:minから:max項目の間にしてください。',
    ],
    'boolean'              => ':attributeはtrueまたはfalseである必要があります。',
    'confirmed'            => ':attributeの確認が一致しません。',
    'current_password'     => 'パスワードが正しくありません。',
    'date'                 => ':attributeは有効な日付ではありません。',
    'date_equals'          => ':attributeは:dateと等しい日付である必要があります。',
    'date_format'          => ':attributeは:format形式と一致していません。',
    'declined'             => ':attributeは却下される必要があります。',
    'declined_if'          => ':otherが:valueの場合、:attributeは却下される必要があります。',
    'different'            => ':attributeと:otherは異なっている必要があります。',
    'digits'               => ':attributeは:digits桁である必要があります。',
    'digits_between'       => ':attributeは:minから:max桁の間である必要があります。',
    'dimensions'           => ':attributeの画像サイズが無効です。',
    'distinct'             => ':attributeに重複した値があります。',
    'email'                => ':attributeは有効なメールアドレスである必要があります。',
    'ends_with'            => ':attributeは:valuesのいずれかで終わる必要があります。',
    'exists'               => '選択された:attributeは無効です。',
    'file'                 => ':attributeはファイルである必要があります。',
    'filled'               => ':attributeは値を持つ必要があります。',
    'gt'                   => [
        'numeric' => ':attributeは:valueより大きい必要があります。',
        'file'    => ':attributeは:value KBより大きい必要があります。',
        'string'  => ':attributeは:value文字より大きい必要があります。',
        'array'   => ':attributeは:value項目より多くの項目を持つ必要があります。',
    ],
    'gte'                  => [
        'numeric' => ':attributeは:value以上である必要があります。',
        'file'    => ':attributeは:value KB以上である必要があります。',
        'string'  => ':attributeは:value文字以上である必要があります。',
        'array'   => ':attributeは:value項目以上の項目を持つ必要があります。',
    ],
    'image'                => ':attributeは画像である必要があります。',
    'in'                   => '選択された:attributeは無効です。',
    'in_array'             => ':attributeは:otherに含まれている必要があります。',
    'integer'              => ':attributeは整数である必要があります。',
    'ip'                   => ':attributeは有効なIPアドレスである必要があります。',
    'ipv4'                 => ':attributeは有効なIPv4アドレスである必要があります。',
    'ipv6'                 => ':attributeは有効なIPv6アドレスである必要があります。',
    'json'                 => ':attributeは有効なJSON文字列である必要があります。',
    'lt'                   => [
        'numeric' => ':attributeは:valueより小さい必要があります。',
        'file'    => ':attributeは:value KBより小さい必要があります。',
        'string'  => ':attributeは:value文字より小さい必要があります。',
        'array'   => ':attributeは:value項目より少ない項目を持つ必要があります。',
    ],
    'lte'                  => [
        'numeric' => ':attributeは:value以下である必要があります。',
        'file'    => ':attributeは:value KB以下である必要があります。',
        'string'  => ':attributeは:value文字以下である必要があります。',
        'array'   => ':attributeは:value項目以下の項目を持つ必要があります。',
    ],
    'max'                  => [
        'numeric' => ':attributeは:maxより大きくしないでください。',
        'file'    => ':attributeは:max KBより大きくしないでください。',
        'string'  => ':attributeは:max文字より大きくしないでください。',
        'array'   => ':attributeは:max項目より多くの項目を持たないでください。',
    ],
    'mimes'                => ':attributeは:valuesタイプのファイルである必要があります。',
    'mimetypes'            => ':attributeは:valuesタイプのファイルである必要があります。',
    'min'                  => [
        'numeric' => ':attributeは:minより小さくしないでください。',
        'file'    => ':attributeは:min KBより小さくしないでください。',
        'string'  => ':attributeは:min文字より小さくしないでください。',
        'array'   => ':attributeは:min項目より少ない項目を持たないでください。',
    ],
    'multiple_of'          => ':attributeは:valueの倍数である必要があります。',
    'not_in'               => '選択された:attributeは無効です。',
    'not_regex'            => ':attributeの形式が無効です。',
    'numeric'              => ':attributeは数字である必要があります。',
    'password'             => 'パスワードが正しくありません。',
    'present'              => ':attributeが存在する必要があります。',
    'regex'                => ':attributeの形式が無効です。',
    'required'             => ':attributeは必須項目です。',
    'required_if'          => ':otherが:valueの場合、:attributeは必須項目です。',
    'required_unless'      => ':otherが:valuesでない限り、:attributeは必須項目です。',
    'required_with'        => ':valuesが存在する場合、:attributeは必須項目です。',
    'required_with_all'    => ':valuesが存在する場合、:attributeは必須項目です。',
    'required_without'     => ':valuesが存在しない場合、:attributeは必須項目です。',
    'required_without_all' => ':valuesのいずれも存在しない場合、:attributeは必須項目です。',
    'same'                 => ':attributeと:otherは一致する必要があります。',
    'size'                 => [
        'numeric' => ':attributeは:sizeである必要があります。',
        'file'    => ':attributeは:size KBである必要があります。',
        'string'  => ':attributeは:size文字である必要があります。',
        'array'   => ':attributeは:size項目を持つ必要があります。',
    ],
    'starts_with'          => ':attributeは:valuesのいずれかで始まる必要があります。',
    'string'               => ':attributeは文字列である必要があります。',
    'timezone'             => ':attributeは有効なタイムゾーンである必要があります。',
    'unique'               => ':attributeは既に使用されています。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeの形式が無効です。',
    'uuid'                 => ':attributeは有効なUUIDである必要があります。',

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
        'name.required' => 'お名前を入力してください。',
        'email.required' => 'メールアドレスを入力してください。',
        'password.required' => 'パスワードを入力してください。',
        'password.min' => 'パスワードは8文字以上で入力してください。',
        'password_confirmation.confirmed' => 'パスワードと一致しません。',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード確認',
    ],

];
