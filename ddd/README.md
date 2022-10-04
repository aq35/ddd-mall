ファクトリメソッド
User::register()

Userのデフォルトの状態の作成を単純化する


Userクラスの要件
ユーザーは個人情報を保持しており、名前や連絡先などがそこに含まれる
ユーザーの個人情報はユーザー自身、あるいは管理者が変更することができる
ユーザーの認証情報(パスワード)を、変更することができる。
モデリングUser
ユーザーと基本識別子という組み合わせ
User:Entity
---------------------
username:String
password:String
---------------------
changePassword()...password encrypted
changePersonalName()
changePersonalContactInformation()

ユキビタス言語
Person:個人:ユーザーの個人情報(名前や連絡先など)を保持し管理する