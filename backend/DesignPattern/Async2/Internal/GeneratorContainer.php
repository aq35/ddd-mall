<?php

namespace DesignPattern\Async2\Internal;

class GeneratorContainer
{
    const RETURN_WITH = '__RETURN_WITH__';
    const RETURN_ = '__RETURN_WITH__'; // alias
    const RET = '__RETURN_WITH__'; // alias
    const RTN = '__RETURN_WITH__'; // alias
    const SAFE = '__SAFE__';
    const DELAY = '__DELAY__';
    const SLEEP = '__DELAY__'; // alias

    /**
     * Generator.
     * @var \Generator
     */
    private $generator;

    /**
     * Generator object hash.
     * @var string
     */
    private $hashId;

    /**
     * Thrown exception.
     * @var \Throwable|\Exception
     */
    private $exception = null;

    /**
     * Constructor.
     * @param \Generator $g
     */
    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
        $this->hashId = spl_object_hash($generator); // 指定したオブジェクトのハッシュIDを返します。
        $this->isWorkingGenerator();
    }

    /**
     * Return generator hash.
     * @return string
     */
    // __toString
    public function hashId()
    {
        return $this->hashId;
    }

    /**
     * Return whether generator is actually working.
     * @return bool
     */
    public function isWorkingGenerator()
    {
        $exception = null;
        try {
            $this->generator->current();
            return $this->exception === null && $this->generator->valid() && $this->generator->key() !== self::RETURN_WITH;
        } catch (\Throwable $throwable) {
            $exception = $throwable;
        } catch (\Exception $exception) {
            $exception = $exception;
        }
        $this->exception = $exception;
        return false;
    }

    /**
     * Return current key.
     * @return mixed
     */
    public function key()
    {
        $this->validateValidity();
        return $this->generator->key();
    }

    /**
     * Returns whatever was passed to yield or null if nothing was passed or the generator is already closed.
     * @return mixed
     */
    public function current()
    {
        $this->validateValidity();
        return $this->generator->current();
    }

    /**
     * Send value into generator.
     * Sets the return value of the yield expression and resumes the generator (unless the generator is already closed).
     * @param mixed $value
     * @NOTE: This method returns nothing,
     *        while original generator returns something.
     */
    public function send($value): void
    {
        $this->validateValidity();
        try {
            $this->generator->send($value);
            return;
        } catch (\Throwable $e) {
        } catch (\Exception $e) {
        }
        $this->exception = $e;
    }

    /**
     * Throw exception into generator.
     * @param \Throwable|\Exception $e
     * @NOTE: This method returns nothing,
     *        while original generator returns something.
     */
    public function throw_($e)
    {
        $this->validateValidity();
        try {
            $this->generator->throw($e);
            return;
        } catch (\Throwable $e) {
        } catch (\Exception $e) {
        }
        $this->exception = $e;
    }

    /**
     * Return whether Throwable is thrown.
     * @return bool
     */
    public function thrown()
    {
        return $this->exception !== null;
    }

    /**
     * Return value that generator has returned or thrown.
     * @return mixed
     */
    // getReturnOrThrown
    public function currentOrFail()
    {
        if ($this->exception === null && $this->generator->valid() && !$this->isWorkingGenerator()) {
            return $this->generator->current();
        }
        if ($this->exception) {
            return $this->exception;
        }
        return method_exists($this->generator, 'getReturn') ? $this->generator->getReturn() : null;
    }

    /**
     * Validate that generator has finished running.
     * @throws \BadMethodCallException
     */
    private function validateValidity()
    {
        if (!$this->isWorkingGenerator()) {
            throw new \BadMethodCallException('ジェネレーターは無効です | Unreachable here.');
        }
    }

    /**
     * Validate that generator is still running.
     * @throws \BadMethodCallException
     */
    private function validateInvalidity()
    {
        if ($this->isWorkingGenerator()) {
            throw new \BadMethodCallException('ジェネレーターは無効です | Unreachable here.');
        }
    }
}
