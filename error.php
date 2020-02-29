<!doctype html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラー</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>
    <div class="error">
      <p>エラーが発生しました。</p>
      <p>もう一度QRコードを読み込んで下さい。</p>
      <p>下記のURLの最後に<strong>作品番号3桁</strong>を付け足したものからもアクセスできます。</p>
      <input id="copyTarget" type="text" value="vote.php?work_num=" readonly>
      <button onclick="copyToClipboard()">コピー</button>
    </div>
    <script>
      function copyToClipboard() {
        // コピー対象をJavaScript上で変数として定義する
        var copyTarget = document.getElementById("copyTarget");

        // コピー対象のテキストを選択する
        copyTarget.select();

        // 選択しているテキストをクリップボードにコピーする
        document.execCommand("Copy");

        // コピーをお知らせする
        alert("\"vote.php?work_num=\"をコピーしました。 : " + copyTarget.value);
      }
    </script>
  </body>
</html>
