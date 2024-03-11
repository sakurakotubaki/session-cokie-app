<?php
// ヘッダーを設定
// CORSヘッダーを設定
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// データを作成
$news = [
    ["id" => 1, "title" => "バカ殿 Returns", "content" => "バカ殿がテレビに戻ってきます！"],
    ["id" => 2, "title" => "バカ殿の新エピソード", "content" => "バカ殿の新エピソードが放送されます。"],
    ["id" => 3, "title" => "バカ殿の特別版", "content" => "バカ殿の特別版がDVDでリリースされます。"],
];

// データをJSON形式に変換
$json = json_encode($news);

// JSONを出力
echo $json;
?>