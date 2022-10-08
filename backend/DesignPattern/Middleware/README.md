# Middleware パターンとは

Middleware パターンの目的は、「ミドルウェア」と呼ばれるガワの処理で核となるハンドラを包むことで、プログラム本来の振る舞いを拡張可能にすること。
MVC ウェブフレームワークでは、コントローラの入出力となる HTTP リクエストやレスポンスをアプリケーション全体で統一的に加工する用途などに採用されている。

## ミドルウェアができることは次の 3 つ

クライアントコードとハンドラの間に割って入り、入力を加工する
ハンドラとクライアントコードの間に割って入り、出力を加工する
クライアントコードとハンドラの間に割って入り、入力がハンドラに届く前に、早期に出力を返す

## ミドルウェア用語

### 概念を実装する

入力(Input)と出力(Output)の関係は、HTTP リクエストならブラウザユーザーからのパラメーターを入力、レスポンスのパラメーターを出力という。

もちろん、例にあげた Http リクエストだけじゃなく、システム対システムの API の入力と出力にも利用される。

ぶっちゃけ、拡張性とか必要がない場合、入力(Input)と出力(Output)の実装だけでいいはずだ。

ミドルウェアパターンは、分割した入力の間に処理を挟む手法だ。

Middleware は、入力を入力 1, 入力 2, 入力 3 に分割する。

入力 1 → (ミドルウェア A の処理) → 入力 2 → (ミドルウェア B の処理) → 入力 3

同様に、出力も、分割した入力分存在する。

正常ルート　入力 3 から始まり、入力 1 で終わる。
出力 1 ← (ミドルウェア A の処理) ← 入力 2 ← (ミドルウェア B の処理) ← 入力 3

ミドルウェアは途中で中断できる。

入力 1 → (ミドルウェア A) → 入力 2 → (ミドルウェア B 中断)　出力 3 へはいかない。
出力 1 → (ミドルウェア A) → 出力 2 ←

Middleware パターンの概念である Input、Output、Middleware、Handler をコードに起こす。

#### 入力(概念)

class Input
{
}

#### 出力(概念)

class Output
{
}

#### ミドルウェア(概念)

// Middleware は、パイプラインを継続する場合、$next->process()を呼ぶ。
// 中断する場合は、呼ばずに Output を return する。
interface Middleware
{
public function process(Input $input, Handler $next): Output;
}

#### ハンドラ(概念)

interface Handler
{
public function handle(Input $input): Output;
}

### PipelineBuilder

クライアントコードが Middleware パターンを扱いやすくするための補助的なクラス
ミドルウェアとハンドラを結合しパイプラインを形成するコードを宣言的に書けるようにしてくれるクラス

[ミドルウェアを登録するメソッド]　 public function use(Middleware $middleware): self

[登録されたミドルウェア]と[ハンドラを合成したハンドラ]を作るメソッド public function build(Handler $handler): Handler

このクラスがあることでクライアントコードは次のように宣言的に書くことができる。

$pipeline = (new PipelineBuilder)
    ->use(new OuterMiddleware())
    ->use(new InnerMiddleware())
    ->build(new ConcreteHandler());
$output = $pipeline->handle(new Input());

### MiddlewareHandler

パイプラインを形成するためには、Middleware と Handler は同一視できなければならない。

そこで、Middleware のインターフェイスを Handler のインターフェイスに変換するアダプターを実装する。

このクラスは、クライアントコードへ提供するものではなく PipelineBuilder で使うことを想定している。

### ミドルウェアとハンドラの実装

// 外側のミドルウェア
final class OuterMiddleware implements Middleware
{
public function process(Input $input, Handler $next): Output
    {
        return $next->handle($input); // ここで$input や Output を加工する
}
}

// 内側のミドルウェア
final class InnerMiddleware implements Middleware
{
public function process(Input $input, Handler $next): Output
    {
        return $next->handle($input); // ここで$input や Output を加工する
}
}

// ハンドラ
final class ConcreteHandler implements Handler
{
public function handle(Input $input): Output
{
return new Output(); // ここで中核的な処理を行う
}
}

### クライアントコード

クライアントコードでは、作ったミドルウェアクラスとハンドラクラスを組み合わせてパイプラインを作り、パイプラインを実行するコードを書く。

$pipeline = (new PipelineBuilder)
    ->use(new OuterMiddleware())
    ->use(new InnerMiddleware())
    ->build(new ConcreteHandler());
$output = $pipeline->handle(new Input());
