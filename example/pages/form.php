<!DOCTYPE HTML>

<html>
<head>
<title>IO測試</title>

</head>

<body>

<a href="index.php?op=export1">1.匯出 - 有資料的結構定義物件(簡易模式結構定義物件-範本)</a><br/>
<a href="index.php?op=export2">2.匯出 - 有資料的結構定義物件(複雜模式結構定義物件-範本)</a><br/>
<a href="index.php?op=export3">3.匯出 - 有資料的結構定義物件(物件注入方式)</a><br/>
<a href="index.php?op=export4">4.匯出 - 空的結構定義物件</a><br/>
<a href="index.php?op=export5">5.匯出 - 手動處理 - 簡易模式</a><br/>
<a href="index.php?op=export6">6.匯出 - 手動處理 - 複雜模式</a><br/>
<a href="index.php?op=export7">7.匯出 - 手動處理 - 簡易模式 - 數字key + 回傳spreadsheet做style後處理</a><br/>
<a href="index.php?op=export8">8.匯出 - 有資料的結構定義物件(物件注入方式+回傳spreadsheet後處理)</a><br/>
<a href="index.php?op=export9">9.匯出 - 空資料+長工作表名</a><br/>
<a href="index.php?op=export10">10.匯出 - 二頁</a><br/>
<a href="index.php?op=export11">11.匯出 - 沒有ConfigSheet-無法匯入</a><br/>

<br/>

<form method="post" action="index.php?op=import" enctype="multipart/form-data">
  <input type="file" name="fileupload" />
  <input type="submit" value="Upload">
</form>

</body>
<script
  src="https://code.jquery.com/jquery-1.12.4.min.js"
  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  crossorigin="anonymous"></script>
<script type="text/javascript">

</script>


</html>