ファクトリメソッド
User::register()

Userのデフォルトの状態の作成を単純化する


Userクラスの要件
ユーザーは個人情報を保持しており、名前や連絡先などがそこに含まれる
ユーザーの個人情報はユーザー自身、あるいは管理者が変更することができる
ユーザーの認証情報(パスワード)を、変更することができる。
モデリングUser
ユーザーと基本識別子という組み合わせ

ユキビタス言語
UserProfile:個人:ユーザーの個人情報(名前や連絡先など)を保持し管理する。

[User]:Entity
---------------------
username:String
password:String
---------------------
changePassword()...password encrypted
changePersonalName()
changePersonalContactInformation()

[User] → [UserProfile]

UserProfileがEntityか値オブジェクトか判断するには[変更]という言葉だ。
住所が変更されていた場合、UserProfileを置き換える必要はない

[UserProfile]:Entity
---------------------
Name() 値オブジェクト
ContactInformation()　値オブジェクト

Customerクラスの要件
新しい注文を顧客に追加する
顧客を推奨する(具体的なのはここでは述べない)


[Customer]:Entity
---------------------
AddOrder()
MakePreferred()
---------------------

契約設計のアサーション(バリデーション)
モデル内でバリデーションを行う第一の理由は、個別の属性/プロパティやオブジェクト全体、そしてオブジェクト同士の合成が正しく行われているか確認するためだ。
観点
あるドメインオブジェクトのプロパティ自体は正しくても、オブジェクト全体として無効な場合
オブジェクト単体では妥当でもオブジェクトを組み合わせたときに妥当ではない

自己カプセル化