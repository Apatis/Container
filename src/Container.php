<?php
/**
 * MIT License
 *
 * Copyright (c) 2017  Pentagonal Development
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Apatis\Container;

use Pimple\Container as PimpleContainer;
use Pimple\Exception\ExpectedInvokableException as PimpleExpectedInvokableException;
use Pimple\Exception\FrozenServiceException as PimpleFrozenServiceException;
use Pimple\Exception\InvalidServiceIdentifierException as PimpleInvalidServiceIdentifierException;
use Pimple\Exception\UnknownIdentifierException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Container
 * @package Apatis\Container
 */
class Container extends PimpleContainer implements ContainerInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!is_numeric($id) && !is_string($id)) {
            throw new InvalidServiceIdentifierException($id);
        }

        try {
            return $this->offsetGet($id);
        } catch (UnknownIdentifierException $e) {
            throw new ContainerNotFoundException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (\Throwable $e) {
            throw new ContainerException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($id) : bool
    {
        return $this->offsetExists($id);
    }

    /**
     * Set Container
     *
     * @param mixed          $id
     * @param callable|mixed $value
     * @throws
     */
    public function set($id, $value)
    {
        if (!is_numeric($id) && !is_string($id)) {
            throw new InvalidServiceIdentifierException($id);
        }

        $this->offsetSet($id, $value);
    }

    /**
     * Remove container by offset
     *
     * @param mixed $id
     */
    public function remove($id)
    {
        $this->offsetUnset($id);
    }

    /**
     * {@inheritdoc}
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function raw($id)
    {
        try {
            return parent::raw($id);
        } catch (UnknownIdentifierException $e) {
            throw new ContainerNotFoundException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (\Throwable $e) {
            throw new ContainerException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     * @throws NotFoundExceptionInterface
     * @throws FrozenServiceException
     * @throws ExpectedInvokableException
     * @throws ContainerExceptionInterface
     */
    public function extend($id, $callable)
    {
        try {
            return parent::extend($id, $callable);
        } catch (UnknownIdentifierException $e) {
            throw new ContainerNotFoundException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (PimpleFrozenServiceException $e) {
            throw new FrozenServiceException($id);
        } catch (PimpleInvalidServiceIdentifierException $e) {
            throw new InvalidServiceIdentifierException($id);
        } catch (PimpleExpectedInvokableException $e) {
            throw new ExpectedInvokableException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (\Throwable $e) {
            throw new ContainerException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * {@inheritdoc}
     * @throws ContainerExceptionInterface
     */
    public function protect($callable)
    {
        try {
            return parent::protect($callable);
        } catch (PimpleExpectedInvokableException $e) {
            throw new ExpectedInvokableException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (\Throwable $e) {
            throw new ContainerException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
