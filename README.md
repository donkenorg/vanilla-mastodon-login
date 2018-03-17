# vanilla-mastodon-login

ver.5  
オープンソースフォーラムシステムVanilla Forumでマストドンログインを実装するためのシステム。  
Mastodon login system for Vanilla Forum.

## 構成

### mastodon-login
マストドンでログインするための基本コード。

### MasPlus
プラグイン。pluginsの中に格納。

## 環境
- ApacheまたはNginx
- PHP 7.0
- Vanilla Forum 2.5.1
- MySQL

## インストール方法
__mastodon-login__ と __plugins__ をVanilla Forumのルート上に展開します。  
上書きの確認が出ますが，Mastodonのロゴ画像です。テーマによっては使用しませんので，スキップしていただいても構いません。  

## 設定方法
まず，VanillaのDashboardに入ります。  
<img src="https://code.cutls.com/wp-content/uploads/2018/03/1-1024x576.png" alt="" width="700" height="394" class="alignnone size-large wp-image-552" />  
右上Settingをクリックし，サイドバーのAddons&gt;Pluginsをクリック。  
<img src="https://code.cutls.com/wp-content/uploads/2018/03/2-1024x576.png" alt="" width="700" height="394" class="alignnone size-large wp-image-550" />  
Oauth2 SSOを有効化し，すぐ左に出てくるボタンで設定を開く。
<img src="https://code.cutls.com/wp-content/uploads/2018/03/3-1024x576.png" alt="" width="700" height="394" class="alignnone size-large wp-image-551" />  
以下の通りに入力する。  
- Client ID:任意の文字列。何でもよい。
- Secret:任意の文字列。何でもよい。
- Authorize Url:**https://[自分のforumのアドレス(donken.org/forum/)]/mastodon-login/**
- Token Url:**https://[自分のforumのアドレス(donken.org/forum/)]/mastodon-login/token/**
- Profile Url:**https://[自分のforumのアドレス(donken.org/forum/)]/mastodon-login/prof/**
- Authorization Code in Header:**チェックを入れる**
- Register Url:**https://[自分のforumのアドレス(donken.org/forum/)]/mastodon-login/**
- Sign Out Url:空欄
- Request Scope:**read**
- Email:**email**
- Photo:**avatar**
- Display Name:**display_name**
- Full Name:**username**
- User ID:**username**
- Make this connection your default signin method.:任意。メールアドレスログインを残す場合はチェックしない。
Saveして終了。  
  
  
VanillaConnectプラグインを有効化する。  
すぐ左の設定ボタンを押す。    
<img src="https://code.cutls.com/wp-content/uploads/2018/03/4-1024x576.png" alt="" width="700" height="394" class="alignnone size-large wp-image-559" />  
Add Providerを選択。  
<img src="https://code.cutls.com/wp-content/uploads/2018/03/5-1024x576.png" alt="" width="700" height="394" class="alignnone size-large wp-image-560" />  
同様に入力。  
- Client ID:任意の文字列。何でもよい。
- Secret:任意の文字列。何でもよい。
- Sign In URL:**https://[自分のforumのアドレス(donken.org/forum/)]/mastodon-login/**
- Registration URL:**https://[自分のforumのアドレス(donken.org/forum/)]/mastodon-login/**
- Sign Out Url:空欄
- Provider Name:**Mastodon**
- This is a trusted provider and it can sync user's information, roles & permissions.:チェックを入れる
Saveして終了。  
  
  
以上です。
