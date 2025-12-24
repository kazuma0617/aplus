# Aplus
「Aplus」はアプレンティス生向けに作成した、チーム開発やオリジナルプロダクトの記事のURLとGitHubのURLを共有できるアプリです。

# インフラ構成図
<img src="docs/images/aplus-system-architecture.drawio.png">

# トップページ

# ER図
<img src="docs/images/aplus.drawio.png">

# 使用技術スタック
- PHP 8.4
- Laravel 12
- MySQL
- AWS (EC2/ALB/Route53)
- JavaScript
- HTML/CSS

# 機能・画面

# 技術的に工夫したところ
ユーザーをアプレンティス生のみに絞り込むため、Discord APIを使用して登録可能なユーザーを限定しました。

# ユーザー目線で工夫したところ
記事登録の手間を減らすため、Qiita APIを用いてQiita投稿記事を自動で同期できる仕組みを実装しました。
