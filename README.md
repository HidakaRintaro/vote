# 投票システム
## 下準備
### ①生徒データの準備
student.csvに学生データを
**学籍番号** , **生年月日** , **名前**
の形式で保存する。
### ②作品データの準備
work.csvに作品データを
**作品番号** , **作品概要**
の形式で保存する。
## 使用する
### ①QRコードを読み取る
ページへのURLに作品番号がGETで受け取れる形で付与されている
という前提のQRコード。
### ②学籍番号と生年月日の入力
学籍番号を数字5桁、
生年月日を数字8桁で入力。
## 処理
- 入力の間違い(空白、文字の型、文字数など)のチェック。
- 学籍番号と生年月日が存在するデータか、一致するデータがあるかの確認。
- エラーがなければvote.csvに投票データを
  **主キー(連番)** , **作品番号** , **投票者学籍番号** , **投票日時**
  の形式で保存する。