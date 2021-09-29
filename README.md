# line-auto-reply

## 概要
公式LINEに自動で記事へのリンクなどを返信するプログラム．

## 実行環境
WordPress上で動作．

## 事前準備
1. LINE公式アカウントの作成
2. Messaging APIの設定
3. チャンネルアクセストークンの取得
4. 必要なら[CodeSnippets](https://ja.wordpress.org/plugins/code-snippets/)などの導入

## 使い方
1. WordPressがインストールされているディレクトリ以下にソースコードを配置
2. LINE公式のWebhook設定に`main.php`へアクセスできるURLを設定
3. 公式LINEにメッセージを送ると，内容に適した返信が送られてくる

## 注意点
天気APIはサービス終了により現在利用不可．
公式LINEのメニューから最新記事とランダム記事を取得するメッセージをワンタップで送信できる．

## ライセンス
"line-auto-reply" is under [MIT license](https://en.wikipedia.org/wiki/MIT_License).

## リンク
- [作者のページ](https://okinotori.net/archives/511)