<?php

if (!file_exists('./vote.csv')) {
  # エラー処理
  header('location:error.php');
  exit;
}

if (!file_exists('./student.csv')) {
  # エラー処理
  header('location:error.php');
  exit;
}

if (!file_exists('./work.csv')) {
  # エラー処理
  header('location:error.php');
  exit;
}

$error = [];
$display = 0;

if (!empty($_GET['work_num'])) {
  $work_num = $_GET['work_num'];
  
  $fp = @fopen('./work.csv', 'r');
  if (!$fp) {
    # エラー処理
    header('location:./error.php');
    exit;
  }
  
  $list = [];
  $overview = '';
  
  // 一致作品を取り出す
  while ($row = fgets($fp)) {
    $list = explode(',', $row);
    if ($work_num == $list[0]) {
      $overview = $list[1];
    }
    $list = [];
  }
  // 作品がない時
  if ($overview == '') {
    $error = 2;
  }
  fclose($fp);
  
} else {
  # エラー処理
  header('location:./error.php');
  exit;
}


$fp = @fopen('vote.csv', 'r');
if (!$fp) {
  # エラー処理
  header('location:./error.php');
  exit;
}

// 主キーの最大値を求める
$list = [];
$max_key = 0;
while ($line = fgets($fp)) {
  $list = explode(',', $line);
  if ($list[0] > $max_key) {
    $max_key = $list[0];
  }
  $list = [];
}
fclose($fp);

$student_num = '';
$birthday_num = '';


// 投票処理
if (!empty($_POST) && $_POST['btn'] == 'submit') {
  
  // 入力済みの値を代入
  $student_num = $_POST['student-num'];
  $birthday_num = $_POST['birthday-num'];
  
  // エラー項目の確認
  if($_POST['student-num'] == ''){
    $error['student-num'] = '学籍番号を記入してください';
  } elseif (strlen($_POST['student-num']) != 5){
    $error['student-num'] = '学籍番号は半角数字5桁で入力してください';
  } elseif (!is_numeric($_POST['student-num'])) {
    $error['student-num'] = '学籍番号は半角数字で入力してください';
  }
  
  if($_POST['birthday-num'] == ''){
    $error['birthday-num'] = '生年月日を記入してください';
  } elseif (strlen($_POST['birthday-num']) != 8){
    $error['birthday-num'] = '生年月日は半角数字8桁で入力してください';
  } elseif (!is_numeric($_POST['birthday-num'])) {
    $error['birthday-num'] = '生年月日は半角数字で入力してください';
  }
  
  if (empty($error)) {
    
    $fp = @fopen('student.csv', 'r');
    if (!$fp) {
      # エラー処理
      header('location:./error.php');
      exit;
    }
    
    // 一致生徒の確認
    $list = [];
    $student_match = 1;
    while ($line = fgets($fp)) {
      $list = explode(',', $line);
      if ($student_num == $list[0] && $birthday_num == $list[1]) {
        $student_match = 2;
      break;
    }
    $list = [];
  }
  fclose($fp);
  
  // 一致生徒がいないときの処理
  if ($student_match == 1) {
    $student_num = '';
    $birthday_num = '';
  }
  
  $fp = @fopen('vote.csv', 'r');
  if (!$fp) {
    # エラー処理
    header('location:./error.php');
    exit;
  }
    // 生徒が一致したら追記していく
    if ($student_match == 2) {
      // 入力内容の取得
      $vote_data = [];
      $vote_data[] = $max_key + 1;
      $vote_data[] = $work_num;
      $vote_data[] = $student_num;
      $vote_data[] = date('Y年m月d日 G:i:s');

      
      // 追記
      $fp = fopen('vote.csv','a');
      fputs($fp, implode(',', $vote_data)."\n");
      fclose($fp);
      
      $display = 1;
    }
    
  }
}

if (!isset($error['student-num'])) {
  $error['student-num'] = '';
}
if (!isset($error['birthday-num'])) {
  $error['birthday-num'] = '';
}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEW作品投票</title>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>
    <div class="wrap">
      <h1>作品No.<?php echo $work_num; ?></h1>
      <?php if ($display == 0): ?>
        <div class="overview">
          <p class="overview-top">【作品概要】</p>
          <p class="overview-item"><?php echo $overview; ?></p>
        </div>
        <form action="" method="post">
          <?php if (!empty($student_match) && $student_match == 1): ?>
            <p style="color: red;">学籍番号か生年月日が間違っています。</p>
            <?php endif; ?>
            <div>
              <label for="student-num">学籍番号&nbsp;(ohs抜きの数字下5桁)</label><br>
              <input type="text" name="student-num" id="student-num" value="<?php echo $student_num; ?>" placeholder="99999">
              <?php if (!empty($error['student-num'])): ?>
        <p style="color: red;">*<?php echo $error['student-num']; ?></p>
      <?php endif; ?>
      </div>
      <div>
        <label for="birthday-num">生年月日(例：19990101)</label><br>
        <input type="text" name="birthday-num" id="birthday-num" value="<?php echo $birthday_num; ?>" placeholder="19990101">
      <?php if (!empty($error['birthday-num'])): ?>
        <p style="color: red;">*<?php echo $error['birthday-num']; ?></p>
      <?php endif; ?>
      </div>
      <button type="submit" name="btn" value="submit">投票</button>
    </form>
  <?php elseif ($display == 1): ?>
    <p class="msg">投票が完了しました</p>
  <?php endif; ?>
  </div>
</body>
</html>