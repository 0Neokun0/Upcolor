<?php
// $aの値がセットされていない場合indexページに戻す関数です
function index($a) {
    if (!isset($a)) {
        header('Location: ../home/index.php');
        exit();
    }
}

function login($a) {
    if (!isset($a)) {
        header('Location: ../signIn/signIn.php');
        exit();
    }
}

function h($s) {
    echo htmlspecialchars($s);
}

// ナビゲーションバーのドロップダウンのトップ表示用
function top($couse_id) {
    $couses = [
        '専攻', '本科', '情報処理ネットワーク専攻', 'ゲーム専攻', 'デザイン専攻', 'ハードウェア専攻'
    ];
    if ($couse_id) {
        echo $couses[$couse_id];
    } else {
        echo $couses[0];
    }
}

// プロフィール編集の専攻選択のドロップダウン
function couse($couse_id) {
    $couses = [
            '専攻を選択してください', '本科', '情報処理ネットワーク専攻', 'ゲーム専攻', 'デザイン専攻', 'ハードウェア専攻'
        ];
    foreach ($couses as $key => $value) {
        if ($key == 0) {
            echo <<<HTML
                    <option value="0" hidden>$value</option>
            HTML;
        } else if ($key == $couse_id) {
            echo <<<HTML
                    <option value="$key" selected>$value</option>
            HTML;
        } else {
            echo <<<HTML
                    <option value="$key">$value</option>
            HTML;
        }
    }
}

// 色識別
function get_text_color( $colorcode ) {
    $rgb = array( 'r' => 255, 'g' => 255, 'b' => 255 );
    $lum = 140;	// 輝度信号 Y の白黒分岐点 (128 がジャスト真ん中)

    // カラーコードを RGB に分割する
    if( stripos( $colorcode, '#' ) !== false ) {
        $colorcode = str_replace( '#', '', $colorcode );

        if( ctype_xdigit( $colorcode ) && strlen( $colorcode ) >= 6 ) {
            $rgb['r'] = hexdec( substr( $colorcode, 0, 2 ) );
            $rgb['g'] = hexdec( substr( $colorcode, 2, 2 ) );
            $rgb['b'] = hexdec( substr( $colorcode, 4, 2 ) );
        }
    }

    // RGB から YUV の輝度信号に変換
    $yuv = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];

    return ( $yuv >= $lum ) ? '#000' : '#fff';
}

function template($template_id) {
    for ($i = 1; $i <= 3; $i ++) {
        if ($i == $template_id) {
            echo <<<HTML
                <div class="col">
                    <input type="radio" id="template_$i" name="template" value="$i" checked>
                    <label for="template_$i"></label>
                </div>
            HTML;
        } else {
            echo <<<HTML
                <div class="col">
                    <input type="radio" id="template_$i" name="template" value="$i">
                    <label for="template_$i"></label>
                </div>
            HTML;
        }
    }
}