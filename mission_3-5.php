<html>
<html lang = "ja">
<head>
<meta charset = "UTF-8">
</head>
<?php
if (isset($_POST["edit"])) { //編集部分の処理
  if(empty($_POST["edit_num"])) {
    echo "!--------------!" . '<br>';
    print 'Error: Edit-Number is Empty.' . '<br>';
    echo "!--------------!" . '<br>';
  }
  elseif(empty($_POST["password"])) {
    echo "!--------------!" . '<br>';
    print 'Error: Password is Empty.' . '<br>';
    echo "!--------------!" . '<br>';
  }
  else {
    $filename = "mission_3-5.txt";
    $fp = fopen($filename, 'r');//ファイル読み込み
    $ret_array = file($filename);// ファイルを全て配列に入れる
    foreach ($ret_array as $value) {
      $output = explode("<>", $value, 6);
      if ($output[0] == $_POST["edit_num"] && $output[4] == $_POST["password"]) {
        $num = $output[0];
        $name = $output[1];
        $comment = $output[2];
      }
    }
  }
}
?>
<?php if (isset($_POST["edit"])) :?><!--編集番号を受け取った場合とそうでない時の表示のさせ方の区別-->
  <form action = "mission_3-5.php" method = "post">
  <p><input type = "text" name = "name_edit" value = <?php echo $name; ?>></p>
  <p><input type = "text" name = "comment_edit" value = <?php echo $comment; ?>></p>
  <p>
  <input type = "password" name = "password_edit">
  <input type = "submit" name = "send_edit" value = "送信">
  <p>
  <input hidden = "text" name = "num_edit" value = <?php echo $num; ?>>
  </p>
<!--新規投稿-->
<?php else: ?>
 <form action = "mission_3-5.php" method = "post">
  <p><input type = "text" name = "name"  placeholder = "名前"></p>
  <p><input type = "text" name = "comment" placeholder = "コメント"></p>
  <p>
  <input type = "text" name = "password" placeholder = "パスワード">
  <input type = "submit" name = "send">
 </p>
</form>
<!--削除-->
<?php endif; ?>
<form action = "mission_3-5.php" method = "post">
  <p>
  <input hidden = "text" name = "decide_num">
  </p>
  <p><input type = "text" name = "num" placeholder = "削除対象番号"></p>
  <p>
  <input type =  "text" name = "password" placeholder = "パスワード">
  <input type = "submit" name = "delete" value = "削除">
  </p>
</form>
<!--編集-->
<form action = "mission_3-5.php" method = "post">
  <p><input type = "text" name = "edit_num" placeholder = "編集対象番号"></p>
 <p>
  <input type = "text" name = "password" placeholder = "パスワード">
 <input type = "submit" name = "edit" value = "編集">
 </p>
 </form>
</html>
<?php
 //新規投稿の処理
 if (isset($_POST["send"])) {
   $name = $_POST["name"];
   $comment = $_POST["comment"];
   $pass = $_POST["password"];
   if(empty($_POST["name"])) {
     echo "!--------------!" . '<br>';
     print 'Error: Name is Empty.' . '<br>';
     echo "!--------------!" . '<br>';
     }
   elseif(empty($_POST["comment"])) {
     echo "!--------------!" . '<br>';
     print 'Error: Comment is Empty.' . '<br>';
     echo "!--------------!" . '<br>';
     }
   elseif (empty($_POST["password"])) {
     echo "!--------------!" . '<br>';
     print 'Error: Password is Empty.' . '<br>';
     echo "!--------------!" . '<br>';
     }
   else {
     $filename = "mission_3-5.txt";
     $fp = fopen($filename, "a");
     $ret_array = file( $filename);//1行ずつ配列に代入
     $last_array = explode("<>", end($ret_array), 5);//ファイルの最後の行
     if (count( file('mission_3-5.txt')) == 0){
       $num = 1;
     }
     else {
        $num = (int)$last_array[0] + 1;
     }
     fwrite($fp, $num. "<>" . $name . "<>" . $comment . "<>" . date('Y/m/d H:i:s') . "<>" . $pass . "<>" . "\n");
     fclose($fp);
   }
 }
  //新規投稿ここまで

  //削除部分の処理
  if (isset($_POST["delete"])) {//削除部分の処理
    if(empty($_POST["num"])) {
      echo "!--------------!" . '<br>';
      print 'Error: Delete-Number is Empty.' . '<br>';
      echo "!--------------!" . '<br>';
      }
    elseif(empty($_POST["password"])) {
      echo "!--------------!" . '<br>';
      print 'Error: Password is Empty.' . '<br>';
      echo "!--------------!" . '<br>';
      }
    else {
      $filename = "mission_3-5.txt";
      $fp = fopen($filename, 'r');//ファイル読み込み
      $ret_array = file( $filename);//1行ずつ配列に代入
      fclose($fp);
      if(file_exists($filename)) {//毎回"mission_3-5.txt"を削除する
        unlink($filename);
      }
      $fp = fopen($filename, "a");//ファイルに追記
      foreach($ret_array as $value) {
        $output = explode("<>", $value, 6);
        if ($output[0] == $_POST["num"] && $output[4] == $_POST["password"]) {
          continue;
        }
        elseif ($output[0] == $_POST["num"] && $output[4] != $_POST["password"]) {
          echo "!--------------!" . '<br>';
          print 'Error: Password is invalid.' . '<br>';
          echo "!--------------!" . '<br>';
        }
        fwrite($fp, $output[0] . "<>" . $output[1] . "<>" . $output[2] . "<>" . $output[3] . "<>" . $output[4] . "<>" . "\n");
      }
      fclose($fp);
    }
  }
    //削除ここまで

    if (isset($_POST["send_edit"])) {
      $filename = "mission_3-5.txt";
      $fp = fopen($filename, 'r');//ファイル読み込み
      $ret_array = file( $filename);//1行ずつ配列に代入
      fclose($fp);
      if(file_exists($filename)) {//毎回"mission_3-5.txt"を削除する
        unlink($filename);
      }
      $fp = fopen($filename, "a");//ファイルに追記
      $flag = 0;
      foreach($ret_array as $value) {
        $output = explode("<>", $value, 6);
        if ($output[0] == $_POST["num_edit"] && $output[4] == $_POST["password_edit"]) {
          fwrite($fp, $_POST["num_edit"] . "<>" . $_POST["name_edit"] . "<>" . $_POST["comment_edit"] . "<>" . date('Y/m/d H:i:s') . "<>" . $_POST["password_edit"] . "<>" . "\n");
          continue;
        }
        fwrite($fp, $output[0] . "<>" . $output[1] . "<>" . $output[2] . "<>" . $output[3] . "<>" . $output[4] . "<>" . "\n");
      }
      fclose($fp);
    }


    $filename = "mission_3-5.txt";
    $fp = fopen($filename, "r");
    echo "----------------------------" . '<br>';
    echo "【投稿一覧】" . '<br>';
    $ret_array = file($filename);// ファイルを全て配列に入れる
    foreach ($ret_array as $value) {
      $output = explode("<>", $value, 5);
      echo $output[0].' ';
      echo $output[1].' ';
      echo $output[2].' ';
      echo $output[3].'<br>';
    }
    fclose($fp);
?>




<  >   >  <  >>>  
